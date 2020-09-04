<?php

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
