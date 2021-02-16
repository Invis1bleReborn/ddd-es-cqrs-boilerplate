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

namespace Common\Shared\Infrastructure\OpenApi\Factory;

use ApiPlatform\Core\OpenApi\Model\Paths;
use Common\Shared\Infrastructure\OpenApi\Iterator\PathItem\OperationsIterator;
use Common\Shared\Infrastructure\OpenApi\Iterator\PathsIterator;

/**
 * Trait WalkOperationsTrait.
 */
trait WalkOperationsTrait
{
    protected function walkOperations(Paths $paths, callable $function): void
    {
        foreach (new PathsIterator($paths) as $uri => $item) {
            foreach (new OperationsIterator($item) as $httpMethod => $operation) {
                $function($operation, $uri, $httpMethod);
            }
        }
    }
}
