<?php

declare(strict_types=1);

namespace Common\Shared\Infrastructure\Event\Publisher;

use Broadway\Domain\DomainMessage;

/**
 * Interface EventPublisherInterface
 *
 * @package Common\Shared\Infrastructure\Event\Publisher
 */
interface EventPublisherInterface
{
    public function handle(DomainMessage $message): void;

    public function publish(): void;

}
