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

namespace Common\Shared\Infrastructure\OpenApi\Iterator\PathItem;

use ApiPlatform\Core\OpenApi\Model\Operation;
use ApiPlatform\Core\OpenApi\Model\PathItem;

/**
 * Class OperationsIterator.
 */
class OperationsIterator implements \IteratorAggregate
{
    protected PathItem $pathItem;

    public function __construct(PathItem $pathItem)
    {
        $this->pathItem = $pathItem;
    }

    /**
     * @return iterable>string, Operation>
     */
    public function getIterator(): iterable
    {
        foreach (['Get', 'Put', 'Post', 'Delete', 'Patch', 'Options', 'Head', 'Trace'] as $httpMethodSuffix) {
            $operation = $this->pathItem->{'get' . $httpMethodSuffix}();
            /* @var Operation|null $operation */

            if (null === $operation) {
                continue;
            }

            yield lcfirst($httpMethodSuffix) => $operation;
        }
    }
}
