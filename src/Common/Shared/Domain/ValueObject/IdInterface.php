<?php

declare(strict_types=1);

namespace Common\Shared\Domain\ValueObject;

/**
 * Interface IdInterface
 *
 * @package Common\Shared\Domain\ValueObject
 */
interface IdInterface
{
    /**
     * @return string
     */
    public function __toString(): string;

}
