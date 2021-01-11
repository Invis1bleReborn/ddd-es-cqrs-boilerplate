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

namespace IdentityAccess\Infrastructure\Identity\Query\ListUsers\Context\Filter;

use Common\Shared\Infrastructure\Query\Context\AbstractFilterFactory;
use IdentityAccess\Infrastructure\Identity\Query\UserModelClassSupportedTrait;

/**
 * Class BaseFilterFactory.
 */
abstract class BaseFilterFactory extends AbstractFilterFactory
{
    use UserModelClassSupportedTrait;
}
