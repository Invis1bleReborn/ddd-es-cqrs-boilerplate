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

namespace Common\Shared\Application\Bus;

use Symfony\Component\Messenger\Exception\HandlerFailedException;

/**
 * Trait MessageBusExceptionTrait
 */
trait MessageBusExceptionTrait
{
    /**
     * @param HandlerFailedException $exception
     *
     * @throws \Throwable
     */
    public function throwException(HandlerFailedException $exception): void
    {
        while ($exception instanceof HandlerFailedException) {
            /** @var \Throwable $exception */
            $exception = $exception->getPrevious();
        }

        throw $exception;
    }
}
