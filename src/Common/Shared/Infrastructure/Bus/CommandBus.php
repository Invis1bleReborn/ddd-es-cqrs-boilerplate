<?php

declare(strict_types=1);

namespace Common\Shared\Infrastructure\Bus;

use Common\Shared\Application\Bus\Command\CommandBusInterface;
use Common\Shared\Application\Bus\Command\CommandInterface;
use Common\Shared\Application\Bus\MessageBusExceptionTrait;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class CommandBus
 *
 * @package Common\Shared\Application\Bus\Command
 */
final class CommandBus implements CommandBusInterface
{
    use MessageBusExceptionTrait;

    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @param CommandInterface $command
     *
     * @throws \Throwable
     */
    public function handle(CommandInterface $command): void
    {
        try {
            $this->messageBus->dispatch($command);
        } catch (HandlerFailedException $e) {
            $this->throwException($e);
        }
    }

}
