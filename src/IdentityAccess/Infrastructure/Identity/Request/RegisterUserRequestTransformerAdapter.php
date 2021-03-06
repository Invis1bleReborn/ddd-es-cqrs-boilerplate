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
use IdentityAccess\Application\Command\Identity\RegisterUser\RegisterUserCommand;
use IdentityAccess\Infrastructure\Identity\Query\User;
use IdentityAccess\Ui\Access\AccessDeniedException;
use IdentityAccess\Ui\Identity\RegisterUser\RegisterUserRequest;
use IdentityAccess\Ui\Identity\RegisterUser\RegisterUserRequestTransformerInterface;

/**
 * Class RegisterUserRequestTransformerAdapter.
 */
class RegisterUserRequestTransformerAdapter implements DataTransformerInterface
{
    private RegisterUserRequestTransformerInterface $registerUserRequestTransformer;

    public function __construct(RegisterUserRequestTransformerInterface $registerUserRequestTransformer)
    {
        $this->registerUserRequestTransformer = $registerUserRequestTransformer;
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
        /* @var $data RegisterUserRequest */

        return ($this->registerUserRequestTransformer)($data);
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof RegisterUserCommand) {
            return false;
        }

        return User::class === $to &&
            isset($context['input']['class']) && RegisterUserRequest::class === $context['input']['class']
        ;
    }
}
