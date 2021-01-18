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

namespace Common\Shared\Infrastructure\Bus;

use Common\Shared\Application\Event\EventBusInterface;
use Common\Shared\Application\Event\EventInterface;

/**
 * Class TraceableEventBus.
 */
final class TraceableEventBus implements EventBusInterface
{
    /**
     * @var EventInterface[]
     */
    private array $recorded = [];

    private bool $tracing = false;

    private EventBusInterface $eventBus;

    public function __construct(EventBusInterface $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(EventInterface $event): void
    {
        $this->eventBus->handle($event);

        if (!$this->tracing) {
            return;
        }

        $this->recorded[] = $event;
    }

    public function getRecordedEvents(): array
    {
        return $this->recorded;
    }

    /**
     * Start tracing.
     */
    public function trace(): void
    {
        $this->tracing = true;
    }
}
