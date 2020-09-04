<?php

declare(strict_types=1);

namespace Common\Shared\Domain\Exception;

/**
 * Class DateTimeException
 *
 * @package Common\Shared\Domain\Exception
 */
class DateTimeException extends \Exception
{
    public function __construct(\Exception $e)
    {
        parent::__construct('Datetime Malformed or not valid', 500, $e);
    }

}
