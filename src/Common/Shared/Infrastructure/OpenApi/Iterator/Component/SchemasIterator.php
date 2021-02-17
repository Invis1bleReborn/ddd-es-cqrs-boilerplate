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

namespace Common\Shared\Infrastructure\OpenApi\Iterator\Component;

use ApiPlatform\Core\OpenApi\Model\Components;

/**
 * Class SchemasIterator.
 */
class SchemasIterator implements \IteratorAggregate
{
    /**
     * @var \ArrayObject<string, \ArrayObject>
     */
    protected \ArrayObject $schemas;

    public function __construct(Components $components)
    {
        $this->schemas = $components->getSchemas() ?? new \ArrayObject();
    }

    /**
     * @return iterable<string, \ArrayObject>
     */
    public function getIterator(): iterable
    {
        yield from $this->schemas;
    }
}
