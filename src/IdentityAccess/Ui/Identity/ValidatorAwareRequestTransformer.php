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

namespace IdentityAccess\Ui\Identity;

use ApiPlatform\Core\Validator\ValidatorInterface;
use Common\Shared\Ui\ValidatorAwareRequestTransformer as BaseValidatorAwareRequestTransformer;

/**
 * Class ValidatorAwareRequestTransformer
 *
 * @package IdentityAccess\Ui\Identity
 */
abstract class ValidatorAwareRequestTransformer extends BaseValidatorAwareRequestTransformer
{
    private AuthenticatedUserProviderInterface $authenticatedUserProvider;

    public function __construct(
        ValidatorInterface $validator,
        AuthenticatedUserProviderInterface $authenticatedUserProvider
    )
    {
        parent::__construct($validator);

        $this->authenticatedUserProvider = $authenticatedUserProvider;
    }

    protected function getAuthenticatedUserId(): ?string
    {
        $user = $this->authenticatedUserProvider->getUser();

        if (null === $user) {
            return null;
        }

        return $user->getId();
    }

}
