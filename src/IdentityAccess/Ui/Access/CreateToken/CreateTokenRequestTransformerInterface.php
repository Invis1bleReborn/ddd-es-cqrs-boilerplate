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

namespace IdentityAccess\Ui\Access\CreateToken;

use ApiPlatform\Core\Validator\Exception\ValidationException;
use Assert\AssertionFailedException;
use IdentityAccess\Application\Command\Access\CreateToken\CreateTokenCommand;

/**
 * Class CreateTokenRequestTransformer.
 */
interface CreateTokenRequestTransformerInterface
{
    /**
     * @throws ValidationException
     * @throws \IdentityAccess\Ui\Access\AccountStatusExceptionInterface
     * @throws AssertionFailedException
     */
    public function __invoke(CreateTokenRequest $request): CreateTokenCommand;
}