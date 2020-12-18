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

use Common\Shared\Application\Query\QueryBusInterface;
use Common\Shared\Application\Query\QueryInterface;
use Common\Shared\Application\MessageBusExceptionTrait;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class QueryBus.
 */
final class QueryBus implements QueryBusInterface
{
    use HandleTrait;
    use MessageBusExceptionTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function ask(QueryInterface $query)
    {
        return $this->handle($query);
    }
}
