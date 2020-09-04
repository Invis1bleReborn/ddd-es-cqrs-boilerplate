<?php

declare(strict_types=1);

namespace Common\Shared\Domain\Specification;

/**
 * Class AbstractSpecification
 *
 * @package Common\Shared\Domain\Specification
 */
abstract class AbstractSpecification
{
    abstract public function isSatisfiedBy($value): bool;

}
