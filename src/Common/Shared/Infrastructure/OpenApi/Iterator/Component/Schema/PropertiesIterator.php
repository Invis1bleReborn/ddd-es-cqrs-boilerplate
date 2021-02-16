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

namespace Common\Shared\Infrastructure\OpenApi\Iterator\Component\Schema;

/**
 * Class PropertiesIterator.
 */
class PropertiesIterator implements \IteratorAggregate
{
    /**
     * @var array<string, array|\ArrayObject>
     */
    protected array $properties;

    public function __construct(\ArrayObject $schema)
    {
        $this->properties = $schema['properties'];
    }

    /**
     * @return iterable<string, array|\ArrayObject>
     */
    public function getIterator(): iterable
    {
        yield from $this->properties;
    }
}
