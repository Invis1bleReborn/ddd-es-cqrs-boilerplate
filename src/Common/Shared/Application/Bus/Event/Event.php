<?php

declare(strict_types=1);

namespace Common\Shared\Application\Bus\Event;

use Broadway\Domain\DomainMessage;

/**
 * Class Event
 *
 * @package Common\Shared\Application\Bus\Event
 */
final class Event implements EventInterface
{
    private DomainMessage $domainMessage;

    public function __construct(DomainMessage $domainMessage)
    {
        $this->domainMessage = $domainMessage;
    }

    public function getDomainMessage(): DomainMessage
    {
        return $this->domainMessage;
    }

}
