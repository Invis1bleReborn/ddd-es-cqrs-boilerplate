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
use IdentityAccess\Application\Command\Identity\ChangeEmail\ChangeEmailCommand;
use IdentityAccess\Infrastructure\Identity\Query\User;
use IdentityAccess\Ui\Access\AccessDeniedException;
use IdentityAccess\Ui\Identity\ChangeEmail\ChangeEmailRequest;
use IdentityAccess\Ui\Identity\ChangeEmail\ChangeEmailRequestTransformerInterface;

/**
 * Class ChangeEmailRequestTransformerAdapter.
 */
class ChangeEmailRequestTransformerAdapter implements DataTransformerInterface
{
    private ChangeEmailRequestTransformerInterface $changeEmailRequestTransformer;

    public function __construct(ChangeEmailRequestTransformerInterface $changeEmailRequestTransformer)
    {
        $this->changeEmailRequestTransformer = $changeEmailRequestTransformer;
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
        /* @var $data ChangeEmailRequest */

        return ($this->changeEmailRequestTransformer)($data, $context['object_to_populate']);
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof ChangeEmailCommand) {
            return false;
        }

        return User::class === $to &&
            isset($context['input']['class']) && ChangeEmailRequest::class === $context['input']['class'];
    }
}
