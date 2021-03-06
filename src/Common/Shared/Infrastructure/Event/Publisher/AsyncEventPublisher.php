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
use Broadway\EventHandling\EventListener;
use Common\Shared\Application\Event\Event;
use Common\Shared\Application\Event\EventBusInterface;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class AsyncEventPublisher.
 */
class AsyncEventPublisher implements EventPublisherInterface, EventSubscriberInterface, EventListener
{
    /**
     * @var DomainMessage[]
     */
    private array $events = [];

    private EventBusInterface $bus;

    public function __construct(EventBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public function handle(DomainMessage $message): void
    {
        $this->events[] = $message;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::TERMINATE => 'publish',
            ConsoleEvents::TERMINATE => 'publish',
        ];
    }

    /**
     * @throws \Throwable
     */
    public function publish(): void
    {
        if (empty($this->events)) {
            return;
        }

        foreach ($this->events as $event) {
            $this->bus->handle(new Event($event));
        }
    }
}
