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

use ApiPlatform\Core\OpenApi\Model\Operation;
use ApiPlatform\Core\OpenApi\Model\Paths;
use Common\Shared\Infrastructure\OpenApi\Iterator\PathItem\Operation\ResponsesIterator;

/**
 * Trait WalkResponsesTrait.
 */
trait WalkResponsesTrait
{
    use WalkOperationsTrait;

    protected function walkResponses(Paths $paths, callable $function): void
    {
        $this->walkOperations($paths, function (Operation $operation) use ($function): void {
            foreach (new ResponsesIterator($operation) as $statusCode => $response) {
                $function($statusCode, $response);
            }
        });
    }
}
