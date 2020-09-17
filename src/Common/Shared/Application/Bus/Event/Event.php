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

namespace Common\Shared\Application\Bus\Event;

use Broadway\Domain\DomainMessage;

/**
 * Class Event.
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
