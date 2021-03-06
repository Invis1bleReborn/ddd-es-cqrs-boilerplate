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

namespace Common\Shared\Infrastructure\Event\Publisher;

use Broadway\Domain\DomainMessage;

/**
 * Interface EventPublisherInterface.
 */
interface EventPublisherInterface
{
    public function handle(DomainMessage $message): void;

    public function publish(): void;
}
