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

namespace IdentityAccess\Infrastructure\Access\View;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use IdentityAccess\Application\Query\Access\TokenInterface;
use IdentityAccess\Ui\Access\TokenTransformer;
use IdentityAccess\Ui\Access\TokenView;

/**
 * Class TokenTransformerAdapter.
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
