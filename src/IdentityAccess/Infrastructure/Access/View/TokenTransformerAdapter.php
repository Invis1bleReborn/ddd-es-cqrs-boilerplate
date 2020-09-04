<?php

declare(strict_types=1);

namespace IdentityAccess\Infrastructure\Access\View;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use IdentityAccess\Application\Query\Access\TokenInterface;
use IdentityAccess\Ui\Access\TokenView;
use IdentityAccess\Ui\Access\TokenTransformer;

/**
 * Class TokenTransformerAdapter
 *
 * @package IdentityAccess\Infrastructure\Access\View
 */
final class TokenTransformerAdapter implements DataTransformerInterface
{
    private TokenTransformer $tokenTransformer;

    public function __construct(TokenTransformer $tokenTransformer)
    {
        $this->tokenTransformer = $tokenTransformer;
    }

    public function transform($data, string $to, array $context = [])
    {
        /* @var $data TokenInterface */

        return ($this->tokenTransformer)($data);
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return TokenView::class === $to && $data instanceof TokenInterface;
    }

}
