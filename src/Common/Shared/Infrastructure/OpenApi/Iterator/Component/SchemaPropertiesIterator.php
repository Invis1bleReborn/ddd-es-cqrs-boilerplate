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
use Common\Shared\Infrastructure\OpenApi\Iterator\Component\Schema\PropertiesIterator;

/**
 * Class SchemaPropertiesIterator.
 */
class SchemaPropertiesIterator implements \IteratorAggregate
{
    private Components $components;

    public function __construct(Components $components)
    {
        $this->components = $components;
    }

    /**
     * @return iterable<string, \ArrayObject|array>
     */
    public function getIterator(): iterable
    {
        foreach (new SchemasIterator($this->components) as $schemaName => $schema) {
            yield from new PropertiesIterator($schema);
        }
    }
}
