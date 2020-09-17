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

namespace IdentityAccess\Infrastructure\Identity\View;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Ui\Identity\UserView;
use IdentityAccess\Ui\Identity\UserTransformer;

/**
 * Class UserTransformerAdapter
 */
final class UserTransformerAdapter implements DataTransformerInterface
{
    private UserTransformer $userTransformer;

    public function __construct(UserTransformer $userTransformer)
    {
        $this->userTransformer = $userTransformer;
    }

    public function transform($data, string $to, array $context = [])
    {
        /* @var $data UserInterface */

        return ($this->userTransformer)($data);
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return UserView::class === $to && $data instanceof UserInterface;
    }
}
