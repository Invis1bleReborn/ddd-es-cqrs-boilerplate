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

namespace Common\Shared\Infrastructure\Uuid;

use Common\Shared\Domain\ValueObject\UuidGeneratorInterface;

/**
 * Class UuidGenerator.
 */
class UuidGenerator implements UuidGeneratorInterface
{
    private \Broadway\UuidGenerator\UuidGeneratorInterface $uuidGenerator;

    public function __construct(\Broadway\UuidGenerator\UuidGeneratorInterface $uuidGenerator)
    {
        $this->uuidGenerator = $uuidGenerator;
    }

    public function __invoke(): string
    {
        return $this->uuidGenerator->generate();
    }
}
