<?php

/*
 * This file is part of invis1ble/ddd-es-cqrs-boilerplate.
 *
 * (c) Invis1ble <opensource.invis1ble@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace IdentityAccess\Infrastructure\Access\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\JsonType;
use IdentityAccess\Domain\Access\ValueObject\Roles;

/**
 * Class RolesType
 */
class RolesType extends JsonType
{
    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getJsonTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value || $value instanceof Roles) {
            if ($value instanceof Roles) {
                $value = $value->toArray();
            }

            return parent::convertToDatabaseValue($value, $platform);
        }

        throw ConversionException::conversionFailedInvalidType(
            $value,
            $this->getName(),
            ['null', Roles::class]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value || $value instanceof Roles) {
            return $value;
        }

        $value = parent::convertToPHPValue($value, $platform);

        try {
            $roles = Roles::fromArray($value);
        } catch (\UnexpectedValueException $e) {
            throw ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                'string[]'
            );
        }

        return $roles;
    }

    public function getName(): string
    {
        return 'roles';
    }
}


