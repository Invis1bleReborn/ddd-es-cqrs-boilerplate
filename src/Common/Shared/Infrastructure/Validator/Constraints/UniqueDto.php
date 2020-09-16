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

namespace Common\Shared\Infrastructure\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueDto
 *
 * @package Common\Shared\Infrastructure\Validator\Constraints
 *
 * @Annotation
 * @Target({"CLASS", "ANNOTATION"})
 */
class UniqueDto extends Constraint
{
    const NOT_UNIQUE_ERROR = '2a53e0b9-7357-493a-b728-74c9e3bf678b';

    protected static $errorNames = [
        self::NOT_UNIQUE_ERROR => 'NOT_UNIQUE_ERROR',
    ];

    public string $message = 'This value is already used.';

    public ?string $em = null;

    public ?string $entityClass = null;

    /**
     * @var string[]
     */
    public array $fieldMapping = [];

    public ?string $errorPath = null;

    public bool $ignoreNull = true;

    public string $repositoryMethod = 'findBy';

    /**
     * {@inheritdoc}
     */
    public function getRequiredOptions()
    {
        return ['entityClass', 'fieldMapping'];
    }

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

}
