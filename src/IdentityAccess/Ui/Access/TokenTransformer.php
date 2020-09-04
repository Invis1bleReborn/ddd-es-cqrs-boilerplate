<?php

declare(strict_types=1);

namespace IdentityAccess\Ui\Access;

use IdentityAccess\Application\Query\Access\TokenInterface;

/**
 * Class TokenTransformer
 *
 * @package IdentityAccess\Ui\Access
 */
class TokenTransformer
{
    public function __invoke(TokenInterface $token): TokenView
    {
        return new TokenView(
            $token->getAccessToken(),
            $token->getRefreshToken()
        );
    }

}
