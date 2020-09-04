<?php

declare(strict_types=1);

namespace IdentityAccess\Ui\Access;

use IdentityAccess\Application\Query\Access\RefreshTokenInterface;

/**
 * Interface RefreshTokenCheckerInterface
 *
 * @package IdentityAccess\Ui\Access
 */
interface RefreshTokenCheckerInterface
{
    /**
     * @param RefreshTokenInterface|null $refreshToken
     *
     * @throws AuthenticationExceptionInterface
     */
    public function __invoke(?RefreshTokenInterface $refreshToken): void;

}
