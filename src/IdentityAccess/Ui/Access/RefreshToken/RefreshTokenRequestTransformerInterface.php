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

namespace IdentityAccess\Ui\Access\RefreshToken;

use ApiPlatform\Core\Validator\Exception\ValidationException;
use Assert\AssertionFailedException;
use IdentityAccess\Application\Command\Access\RefreshToken\RefreshTokenCommand;
use IdentityAccess\Ui\Access\AuthenticationExceptionInterface;

/**
 * Class RefreshTokenRequestTransformer
 *
 * @package IdentityAccess\Ui\Access\RefreshToken
 */
interface RefreshTokenRequestTransformerInterface
{
    /**
     * @param RefreshTokenRequest $request
     *
     * @return RefreshTokenCommand
     * @throws ValidationException
     * @throws AuthenticationExceptionInterface
     * @throws AssertionFailedException
     */
    public function __invoke(RefreshTokenRequest $request): RefreshTokenCommand;
}