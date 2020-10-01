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
use IdentityAccess\Application\Command\Identity\ChangePassword\ChangePasswordCommand;
use IdentityAccess\Infrastructure\Identity\Query\User;
use IdentityAccess\Ui\Access\AccessDeniedException;
use IdentityAccess\Ui\Identity\ChangePassword\ChangePasswordRequest;
use IdentityAccess\Ui\Identity\ChangePassword\ChangePasswordRequestTransformerInterface;

/**
 * Class ChangePasswordRequestTransformerAdapter.
 */
class ChangePasswordRequestTransformerAdapter implements DataTransformerInterface
{
    private ChangePasswordRequestTransformerInterface $changePasswordRequestTransformer;

    public function __construct(ChangePasswordRequestTransformerInterface $changePasswordRequestTransformer)
    {
        $this->changePasswordRequestTransformer = $changePasswordRequestTransformer;
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
        /* @var $data ChangePasswordRequest */

        return ($this->changePasswordRequestTransformer)($data, $context['object_to_populate']);
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof ChangePasswordCommand) {
            return false;
        }

        return User::class === $to &&
            isset($context['input']['class']) && ChangePasswordRequest::class === $context['input']['class'];
    }
}
