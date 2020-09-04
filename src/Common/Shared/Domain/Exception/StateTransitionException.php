<?php

declare(strict_types=1);

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
