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

namespace Common\Shared\Infrastructure\OpenApi\Iterator;

use ApiPlatform\Core\OpenApi\Model\PathItem;
use ApiPlatform\Core\OpenApi\Model\Paths;

/**
 * Class PathsIterator.
 */
class PathsIterator implements \IteratorAggregate
{
    /**
     * @var array<string, PathItem>
     */
    protected array $paths;

    public function __construct(Paths $paths)
    {
        $this->paths = $paths->getPaths();
    }

    /**
     * @return iterable<string, PathItem>
     */
    public function getIterator(): iterable
    {
        yield from $this->paths;
    }
}
