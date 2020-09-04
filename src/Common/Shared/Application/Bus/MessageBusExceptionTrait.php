<?php

declare(strict_types=1);

namespace Common\Shared\Application\Bus;

use Symfony\Component\Messenger\Exception\HandlerFailedException;

/**
 * Trait MessageBusExceptionTrait
 *
 * @package Common\Shared\Application\Bus
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
