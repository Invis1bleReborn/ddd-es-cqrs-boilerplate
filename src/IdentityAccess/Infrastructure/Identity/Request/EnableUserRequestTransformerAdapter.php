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
use ApiPlatform\Core\Validator\Exception\ValidationException;
use Assert\AssertionFailedException;
use IdentityAccess\Application\Command\Identity\EnableUser\EnableUserCommand;
use IdentityAccess\Infrastructure\Identity\Query\User;
use IdentityAccess\Ui\Access\AccessDeniedException;
use IdentityAccess\Ui\Identity\ChangeUserStatus\ChangeUserStatusRequest;
use IdentityAccess\Ui\Identity\EnableUser\EnableUserRequestTransformerInterface;

/**
 * Class EnableUserRequestTransformerAdapter.
 */
class EnableUserRequestTransformerAdapter implements DataTransformerInterface
{
    private EnableUserRequestTransformerInterface $enableUserRequestTransformer;

    public function __construct(EnableUserRequestTransformerInterface $enableUserRequestTransformer)
    {
        $this->enableUserRequestTransformer = $enableUserRequestTransformer;
    }

    /**
     * {@inheritdoc}
     *
     * @throws AccessDeniedException
     * @throws ValidationException
     * @throws AssertionFailedException
     */
    public function transform($data, string $to, array $context = [])
    {
        /* @var $data ChangeUserStatusRequest */

        return ($this->enableUserRequestTransformer)($data, $context['object_to_populate']);
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof EnableUserCommand) {
            return false;
        }

        return User::class === $to &&
            isset($context['input']['class']) && ChangeUserStatusRequest::class === $context['input']['class'] &&
            is_array($data) && isset($data['enabled']) && true === $data['enabled']
        ;
    }
}
