<?php

declare(strict_types=1);

namespace Common\Shared\Infrastructure\Bus;

use Common\Shared\Application\Bus\Event\EventBusInterface;
use Common\Shared\Application\Bus\Event\EventInterface;
use Common\Shared\Application\Bus\MessageBusExceptionTrait;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class EventBus
 *
 * @package ommon\Shared\Infrastructure\Bus
 */
final class EventBus implements EventBusInterface
{
    use MessageBusExceptionTrait;

    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(EventInterface $event): void
    {
        try {
            $this->messageBus->dispatch($event);
        } catch (HandlerFailedException $e) {
            $this->throwException($e);
        }
    }

}
