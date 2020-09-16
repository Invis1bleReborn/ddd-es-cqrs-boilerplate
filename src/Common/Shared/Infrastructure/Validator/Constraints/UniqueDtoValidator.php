<?php

declare(strict_types=1);

/*
 * This file is part of invis1ble/ddd-es-cqrs-boilerplate.
 *
 * (c) Invis1ble <opensource.invis1ble@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Common\Shared\Infrastructure\Validator\Constraints;

//use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueDtoValidator extends ConstraintValidator
{
    private ?Constraint $constraint;

    private ?ObjectManager $em = null;

    private $entityMeta;

    private $registry;

    private $repository;

    private $validationObject;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function validate($object, Constraint $constraint)
    {
        // Set arguments as class variables
        $this->validationObject = $object;
        $this->constraint       = $constraint;
        $this->checkTypes();

        // Map types to criteria
        $this->entityMeta = $this->getEntityManager()->getClassMetadata($this->constraint->entityClass);
        $criteria         = $this->getCriteria();
        // skip validation if there are no criteria (this can happen when the
        // "ignoreNull" option is enabled and fields to be checked are null
        if (empty($criteria)) {
            return;
        }

        $result = $this->checkConstraint($criteria);
        // If no entity matched the query criteria or a single entity matched,
        // which is the same as the entity being validated, the criteria is
        // unique.
        if (!$result || (1 === \count($result) && current($result) === $this->entityMeta)) {
            return;
        }

        // Property to which to return the violation
        $objectFields = array_keys($this->constraint->fieldMapping);
        $errorPath    = null !== $this->constraint->errorPath
            ? $this->constraint->errorPath
            : $objectFields[0];

        // Value that caused the violation
        $invalidValue = isset($criteria[$this->constraint->fieldMapping[$errorPath]])
            ? $criteria[$this->constraint->fieldMapping[$errorPath]]
            : $criteria[$this->constraint->fieldMapping[0]];

        // Build violation
        $this->context->buildViolation($this->constraint->message)
            ->atPath($errorPath)
            ->setParameter('{{ value }}', $this->formatWithIdentifiers($invalidValue))
            ->setInvalidValue($invalidValue)
            ->setCode(UniqueDto::NOT_UNIQUE_ERROR)
            ->setCause($result)
            ->addViolation();
    }

    private function checkTypes()
    {
        if (!$this->constraint instanceof UniqueDto) {
            throw new UnexpectedTypeException($this->constraint, UniqueDto::class);
        }

        if (null === $this->constraint->entityClass || !\class_exists($this->constraint->entityClass)) {
            throw new UnexpectedTypeException($this->constraint->entityClass, Entity::class);
        }

        if (!\is_array($this->constraint->fieldMapping) || 0 === \count($this->constraint->fieldMapping)) {
            throw new UnexpectedTypeException($this->constraint->fieldMapping, '[objectProperty => entityProperty]');
        }

        if (null !== $this->constraint->errorPath && !is_string($this->constraint->errorPath)) {
            throw new UnexpectedTypeException($this->constraint->errorPath, 'string or null');
        }
    }

    private function getEntityManager()
    {
        if (null !== $this->em) {
            return $this->em;
        }

        if ($this->constraint->em) {
            $this->em = $this->registry->getManager($this->constraint->em);

            if (!$this->em) {
                throw new ConstraintDefinitionException(sprintf('Object manager "%s" does not exist.',
                    $this->constraint->em
                ));
            }
        } else {
            $this->em = $this->registry->getManagerForClass($this->constraint->entityClass);

            if (!$this->em) {
                throw new ConstraintDefinitionException(sprintf(
                    'Unable to find the object manager associated with an entity of class "%s".',
                    $this->constraint->entityClass
                ));
            }
        }

        return $this->em;
    }

    private function getCriteria()
    {
        $validationClass = new \ReflectionClass($this->validationObject);

        $criteria = [];
        foreach ($this->constraint->fieldMapping as $objectField => $entityField) {

            // DTO Property (key) should exist on DataTransferObject
            if (!$validationClass->hasProperty($objectField)) {
                throw new ConstraintDefinitionException(sprintf(
                    'Property for fieldMapping key "%s" does not exist on this Object.',
                    $objectField
                ));
            }

            // Entity Property (value) should exist in the ORM
            if (!$this->entityMeta->hasField($entityField) && !$this->entityMeta->hasAssociation($entityField)) {
                throw new ConstraintDefinitionException(sprintf(
                    'The field "%s" is not mapped by Doctrine, so it cannot be validated for uniqueness.',
                    $entityField
                ));
            }

            $fieldValue = $validationClass->getProperty($objectField)->getValue($this->validationObject);

            // validation doesn't fail if one of the fields is null and if null values should be ignored
            if (null === $fieldValue && !$this->constraint->ignoreNull) {
                throw new \UnexpectedValueException('Unique value can not be NULL');
            }

            $criteria[$entityField] = $fieldValue;

            if (null !== $criteria[$entityField] && $this->entityMeta->hasAssociation($entityField)) {
                /* Ensure the Proxy is initialized before using reflection to
                 * read its identifiers. This is necessary because the wrapped
                 * getter methods in the Proxy are being bypassed.
                 */
                $this->getEntityManager()->initializeObject($criteria[$entityField]);
            }
        }

        return $criteria;
    }

    private function checkConstraint($criteria)
    {
        $result = $this->getRepository()->{$this->constraint->repositoryMethod}($criteria);

        if ($result instanceof \IteratorAggregate) {
            $result = $result->getIterator();
        }

        /* If the result is a MongoCursor, it must be advanced to the first
         * element. Rewinding should have no ill effect if $result is another
         * iterator implementation.
         */
        if ($result instanceof \Iterator) {
            $result->rewind();
            if ($result instanceof \Countable && 1 < \count($result)) {
                $result = [$result->current(), $result->current()];
            } else {
                $result = $result->current();
                $result = null === $result ? [] : [$result];
            }
        } elseif (\is_array($result)) {
            reset($result);
        } else {
            $result = null === $result ? [] : [$result];
        }

        return $result;
    }

    private function formatWithIdentifiers($value)
    {
        if (!is_object($value) || $value instanceof \DateTimeInterface) {
            return $this->formatValue($value, self::PRETTY_DATE);
        }

        if ($this->entityMeta->getName() !== $idClass = get_class($value)) {
            // non unique value might be a composite PK that consists of other entity objects
            if ($this->getEntityManager()->getMetadataFactory()->hasMetadataFor($idClass)) {
                $identifiers = $this->getEntityManager()->getClassMetadata($idClass)->getIdentifierValues($value);
            } else {
                // this case might happen if the non unique column has a custom doctrine type and its value is an object
                // in which case we cannot get any identifiers for it
                $identifiers = [];
            }
        } else {
            $identifiers = $this->entityMeta->getIdentifierValues($value);
        }

        if (!$identifiers) {
            return sprintf('object("%s")', $idClass);
        }

        array_walk($identifiers, function (&$id, $field) {
            if (!is_object($id) || $id instanceof \DateTimeInterface) {
                $idAsString = $this->formatValue($id, self::PRETTY_DATE);
            } else {
                $idAsString = sprintf('object("%s")', get_class($id));
            }

            $id = sprintf('%s => %s', $field, $idAsString);
        });

        return sprintf('object("%s") identified by (%s)', $idClass, implode(', ', $identifiers));
    }

    private function getRepository()
    {
        if (null === $this->repository) {
            $this->repository = $this->getEntityManager()->getRepository($this->constraint->entityClass);
        }

        return $this->repository;
    }

}
