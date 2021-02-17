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

namespace IdentityAccess\Infrastructure\OpenApi\Factory;

use ApiPlatform\Core\OpenApi\Model\Components;
use ApiPlatform\Core\OpenApi\OpenApi;
use Common\Shared\Infrastructure\OpenApi\Factory\OpenApiDecorator;
use Common\Shared\Infrastructure\OpenApi\Iterator\Component\Schema\PropertiesIterator;
use Common\Shared\Infrastructure\OpenApi\Iterator\Component\SchemasIterator;
use IdentityAccess\Domain\Access\ValueObject\Role;

/**
 * Class RolesEnumSetter.
 */
class RolesEnumSetter extends OpenApiDecorator
{
    protected function decorate(OpenApi $openApi): OpenApi
    {
        $components = $openApi->getComponents();
        $components = $this->setRolesEnum($components);

        return $openApi->withComponents($components);
    }

    protected function setRolesEnum(Components $components): Components
    {
        $roles = Role::toArray();

        foreach (new SchemasIterator($components) as $schemaName => $schema) {
            foreach (new PropertiesIterator($schema) as $propertyName => $property) {
                if ('array' !== $property['type'] ||
                    !isset($property['description']) ||
                    'User roles.' !== $property['description']
                ) {
                    continue;
                }

                $property['items']['enum'] = $roles;
            }
        }

        return $components;
    }
}
