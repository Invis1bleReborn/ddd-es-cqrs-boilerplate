<?php

declare(strict_types=1);


namespace Common\Shared\Application\Bus\Command;


/**
 * Class CommandBus
 *
 * @package Common\Shared\Application\Bus\Command
 */
interface CommandBusInterface
{
    /**
     * @param CommandInterface $command
     *
     * @throws \Throwable
     */
    public function handle(CommandInterface $command): void;

}