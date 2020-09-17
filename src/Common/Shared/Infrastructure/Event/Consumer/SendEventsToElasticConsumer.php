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

namespace Common\Shared\Infrastructure\Event\Consumer;

use Common\Shared\Application\Bus\Event\Event;
use Common\Shared\Application\Bus\Event\EventHandlerInterface;
use Common\Shared\Infrastructure\Event\Query\EventElasticRepository;

/**
 * Class SendEventsToElasticConsumer
 *
 * @package Common\Shared\Infrastructure\Event\Consumer
 */
class SendEventsToElasticConsumer implements EventHandlerInterface
{
    private EventElasticRepository $eventElasticRepository;

    public function __construct(EventElasticRepository $eventElasticRepository)
    {
        $this->eventElasticRepository = $eventElasticRepository;
    }

    public function __invoke(Event $event): void
    {
        $this->eventElasticRepository->store(
            $event->getDomainMessage()
        );
    }
}
