<?php

declare(strict_types=1);

namespace Common\Shared\Application\Bus\Event;

/**
 * Interface EventBusInterface
 *
 * @package Common\Shared\Application\Bus\Event
 */
interface EventBusInterface
{
    /**
     * @param EventInterface $event
     *
     * @throws \Throwable
     */
    public function handle(EventInterface $event): void;

}
