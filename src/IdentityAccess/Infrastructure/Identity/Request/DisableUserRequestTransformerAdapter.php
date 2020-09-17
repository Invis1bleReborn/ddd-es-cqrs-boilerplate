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

namespace IdentityAccess\Infrastructure\Identity\Request;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use IdentityAccess\Application\Command\Identity\DisableUser\DisableUserCommand;
use IdentityAccess\Infrastructure\Identity\Query\User;
use IdentityAccess\Ui\Identity\ChangeUserStatus\ChangeUserStatusRequest;
use IdentityAccess\Ui\Identity\DisableUser\DisableUserRequestTransformerInterface;

/**
 * Class DisableUserRequestTransformerAdapter
 */
class DisableUserRequestTransformerAdapter implements DataTransformerInterface
{
    private DisableUserRequestTransformerInterface $disableUserRequestTransformer;

    public function __construct(DisableUserRequestTransformerInterface $disableUserRequestTransformer)
    {
        $this->disableUserRequestTransformer = $disableUserRequestTransformer;
    }

    public function transform($data, string $to, array $context = [])
    {
        /* @var $data ChangeUserStatusRequest */

        return ($this->disableUserRequestTransformer)($data, $context['object_to_populate']);
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof DisableUserCommand) {
            return false;
        }

        return User::class === $to &&
            isset($context['input']['class']) && ChangeUserStatusRequest::class === $context['input']['class'] &&
            is_array($data) && isset($data['enabled']) && false === $data['enabled']
        ;
    }
}
