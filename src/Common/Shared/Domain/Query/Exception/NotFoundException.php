<?php

declare(strict_types=1);

namespace Common\Shared\Domain\Query\Exception;

/**
 * Class NotFoundException
 *
 * @package Common\Shared\Domain\Query\Exception
 */
class NotFoundException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Resource not found');
    }

}
