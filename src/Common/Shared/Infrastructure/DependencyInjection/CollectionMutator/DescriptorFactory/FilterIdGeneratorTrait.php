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

namespace Common\Shared\Infrastructure\DependencyInjection\CollectionMutator\DescriptorFactory;

use ApiPlatform\Core\Util\Inflector;

/**
 * Trait FilterIdGeneratorTrait.
 */
trait FilterIdGeneratorTrait
{
    protected function generateFilterId(string $modelClass, string $filterClass, string $filterId = null): string
    {
        $id = 'php_' . Inflector::tableize(str_replace('\\', '', $modelClass . $filterClass));

        if (null !== $filterId) {
            $id .= '_' . $filterId;
        }

        return $id;
    }
}
