<?php

declare(strict_types=1);

/*
 * This file is part of invis1ble/ddd-es-cqrs-boilerplate.
 *
 * (c) Invis1ble <opensource.invis1ble@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Common\Shared\Domain\Exception;

/**
 * Class StateTransitionException
 *
 * @package Common\Shared\Domain\Exception
 */
class StateTransitionException extends \DomainException
{
    public function __construct(string $message = null, int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message ?? 'Invalid state transition.', $code, $previous);
    }

}
