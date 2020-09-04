<?php

declare(strict_types=1);

namespace Common\Shared\Domain\ValueObject;

/**
 * Interface UuidGeneratorInterface
 *
 * @package Common\Shared\Domain\ValueObject
 */
interface UuidGeneratorInterface
{
    /**
     * @return string
     */
    public function __invoke(): string;

}
