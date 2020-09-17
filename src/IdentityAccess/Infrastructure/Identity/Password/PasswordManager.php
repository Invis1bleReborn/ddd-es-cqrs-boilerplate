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

namespace IdentityAccess\Infrastructure\Identity\Password;

use Assert\AssertionFailedException;
use IdentityAccess\Domain\Identity\PasswordEncoderInterface;
use IdentityAccess\Domain\Identity\PasswordCheckerInterface;
use IdentityAccess\Domain\Identity\ValueObject\HashedPassword;
use IdentityAccess\Domain\Identity\ValueObject\PlainPassword;
use IdentityAccess\Infrastructure\Identity\Query\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\SelfSaltingEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

/**
 * Class PasswordManager
 */
class PasswordManager implements PasswordEncoderInterface, PasswordCheckerInterface
{
    private \Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface $encoder;

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $encoder = $encoderFactory->getEncoder(User::class);

        if (!$encoder instanceof SelfSaltingEncoderInterface) {
            throw new \RuntimeException(sprintf('Expected self-salting (%s) encoder, instance of %s given.',
                SelfSaltingEncoderInterface::class,
                get_class($encoder)
            ));
        }

        $this->encoder = $encoder;
    }

    /**
     * @throws AssertionFailedException
     */
    public function encode(PlainPassword $plainPassword): HashedPassword
    {
        $hashedPassword = $this->encoder->encodePassword($plainPassword->toString(), null);

        return HashedPassword::fromString($hashedPassword);
    }

    public function check(HashedPassword $hashedPassword, PlainPassword $plainPassword, ?string $salt): void
    {
        if (!$this->encoder->isPasswordValid($hashedPassword->toString(), $plainPassword->toString(), null)) {
            throw new BadCredentialsException('The presented password is invalid.');
        }
    }
}
