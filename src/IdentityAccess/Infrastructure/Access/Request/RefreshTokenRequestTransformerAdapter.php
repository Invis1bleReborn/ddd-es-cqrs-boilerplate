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
use IdentityAccess\Application\Command\Access\RefreshToken\RefreshTokenCommand;
use IdentityAccess\Infrastructure\Access\Query\Token;
use IdentityAccess\Ui\Access\AuthenticationExceptionInterface;
use IdentityAccess\Ui\Access\RefreshToken\RefreshTokenRequest;
use IdentityAccess\Ui\Access\RefreshToken\RefreshTokenRequestTransformerInterface;

/**
 * Class RefreshTokenRequestTransformerAdapter.
 */
class RefreshTokenRequestTransformerAdapter implements DataTransformerInterface
{
    private RefreshTokenRequestTransformerInterface $refreshTokenRequestTransformer;

    public function __construct(RefreshTokenRequestTransformerInterface $refreshTokenRequestTransformer)
    {
        $this->refreshTokenRequestTransformer = $refreshTokenRequestTransformer;
    }

    /**
     * {@inheritdoc}
     *
     * @throws ValidationException
     * @throws AuthenticationExceptionInterface
     * @throws AssertionFailedException
     */
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
