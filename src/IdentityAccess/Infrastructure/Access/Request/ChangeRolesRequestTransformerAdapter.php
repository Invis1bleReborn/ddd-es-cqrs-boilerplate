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

namespace IdentityAccess\Infrastructure\Access\Request;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\Exception\ValidationException;
use Assert\AssertionFailedException;
use IdentityAccess\Application\Command\Access\ChangeRoles\ChangeRolesCommand;
use IdentityAccess\Infrastructure\Identity\Query\User;
use IdentityAccess\Ui\Access\AccessDeniedException;
use IdentityAccess\Ui\Access\ChangeRoles\ChangeRolesRequest;
use IdentityAccess\Ui\Access\ChangeRoles\ChangeRolesRequestTransformerInterface;

/**
 * Class ChangeRolesRequestTransformerAdapter.
 */
class ChangeRolesRequestTransformerAdapter implements DataTransformerInterface
{
    private ChangeRolesRequestTransformerInterface $changeRolesRequestTransformer;

    public function __construct(ChangeRolesRequestTransformerInterface $changeRolesRequestTransformer)
    {
        $this->changeRolesRequestTransformer = $changeRolesRequestTransformer;
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
        /* @var $data ChangeRolesRequest */

        return ($this->changeRolesRequestTransformer)($data, $context['object_to_populate']);
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof ChangeRolesCommand) {
            return false;
        }

        return User::class === $to &&
            isset($context['input']['class']) && ChangeRolesRequest::class === $context['input']['class'];
    }
}
