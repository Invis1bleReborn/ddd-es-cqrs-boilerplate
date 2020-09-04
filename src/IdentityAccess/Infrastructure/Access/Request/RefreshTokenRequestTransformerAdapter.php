<?php

declare(strict_types=1);

namespace IdentityAccess\Infrastructure\Access\Request;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use IdentityAccess\Application\Command\Access\RefreshToken\RefreshTokenCommand;
use IdentityAccess\Infrastructure\Access\Query\Token;
use IdentityAccess\Ui\Access\RefreshToken\RefreshTokenRequest;
use IdentityAccess\Ui\Access\RefreshToken\RefreshTokenRequestTransformerInterface;

/**
 * Class RefreshTokenRequestTransformerAdapter
 *
 * @package IdentityAccess\Infrastructure\Access\Request
 */
class RefreshTokenRequestTransformerAdapter implements DataTransformerInterface
{
    private RefreshTokenRequestTransformerInterface $refreshTokenRequestTransformer;

    public function __construct(RefreshTokenRequestTransformerInterface $refreshTokenRequestTransformer)
    {
        $this->refreshTokenRequestTransformer = $refreshTokenRequestTransformer;
    }

    public function transform($data, string $to, array $context = [])
    {
        /* @var $data RefreshTokenRequest */

        return ($this->refreshTokenRequestTransformer)($data);
    }


    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof RefreshTokenCommand) {
            return false;
        }

        return Token::class === $to &&
            isset($context['input']['class']) && RefreshTokenRequest::class === $context['input']['class']
        ;
    }

}
