<?php

declare(strict_types=1);

namespace IdentityAccess\Infrastructure\Identity\View;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Ui\Identity\UserView;
use IdentityAccess\Ui\Identity\UserTransformer;

/**
 * Class UserTransformerAdapter
 *
 * @package IdentityAccess\Infrastructure\Identity\View
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
