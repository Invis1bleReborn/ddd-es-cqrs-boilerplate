<?php

declare(strict_types=1);

/*
 * This file is part of invis1ble/ddd-es-cqrs-boilerplate.
 *
 * (c) Invis1ble <opensource.invis1ble@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IdentityAccess\Domain\Identity;

use Assert\AssertionFailedException;
use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Common\Shared\Domain\ValueObject\DateTime;
use IdentityAccess\Domain\Access\Event\RolesChanged;
use IdentityAccess\Domain\Access\ValueObject\Roles;
use IdentityAccess\Domain\Identity\Event\EmailChanged;
use IdentityAccess\Domain\Identity\Event\PasswordChanged;
use IdentityAccess\Domain\Identity\Event\UserRegistered;
use IdentityAccess\Domain\Identity\Exception\EmailAlreadyExistsException;
use IdentityAccess\Domain\Identity\Specification\UniqueEmailSpecificationInterface;
use IdentityAccess\Domain\Identity\UserState\Uninitialized;
use IdentityAccess\Domain\Identity\UserState\UserStateInterface;
use IdentityAccess\Domain\Identity\ValueObject\Email;
use IdentityAccess\Domain\Identity\ValueObject\HashedPassword;
use IdentityAccess\Domain\Identity\ValueObject\UserId;

/**
 * Class User
 *
 * @package IdentityAccess\Domain\Identity
 */
final class User extends EventSourcedAggregateRoot
{
    private ?UserId $id;

    private ?Email $email;

    private ?HashedPassword $hashedPassword;

    private ?Roles $roles;

    private ?UserStateInterface $state;

    private ?UserId $registeredBy;

    private ?DateTime $dateRegistered;

    private function __construct() {}

    /**
     * @param UserId                            $id
     * @param Email                             $email
     * @param HashedPassword                    $hashedPassword
     * @param Roles                             $roles
     * @param bool                              $enabled
     * @param UserId|null                       $registeredBy
     * @param UniqueEmailSpecificationInterface $uniqueEmailSpecification
     *
     * @return self
     */
    public static function register(
        UserId $id,
        Email $email,
        HashedPassword $hashedPassword,
        Roles $roles,
        bool $enabled,
        ?UserId $registeredBy,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification
    ): self
    {
        $user = new self();

        $user->assertUniqueEmail($email, $uniqueEmailSpecification);

        $user->apply(new UserRegistered(
            $id,
            $email,
            $hashedPassword,
            $roles,
            $enabled,
            $registeredBy,
            DateTime::now()
        ));

        return $user;
    }

    /**
     * @param Email                             $email
     * @param UserId|null                       $changedBy
     * @param DateTime                          $dateChanged
     * @param UniqueEmailSpecificationInterface $uniqueEmailSpecification
     *
     * @throws EmailAlreadyExistsException
     */
    public function changeEmail(
        Email $email,
        ?UserId $changedBy,
        DateTime $dateChanged,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification
    ): void
    {
        if ($this->email->equals($email)) {
            return;
        }

        $this->assertUniqueEmail($email, $uniqueEmailSpecification);

        $this->apply(new EmailChanged(
            $this->id,
            $email,
            $this->email,
            $changedBy,
            $dateChanged
        ));
    }

    /**
     * @param HashedPassword $hashedPassword
     * @param UserId|null    $changedBy
     * @param DateTime       $dateChanged
     */
    public function changePassword(
        HashedPassword $hashedPassword,
        ?UserId $changedBy,
        DateTime $dateChanged
    ): void
    {
        if ($this->hashedPassword->equals($hashedPassword)) {
            return;
        }

        $this->apply(new PasswordChanged(
            $this->id,
            $hashedPassword,
            $changedBy,
            $dateChanged
        ));
    }

    /**
     * @param Roles       $roles
     * @param UserId|null $changedBy
     * @param DateTime    $dateChanged
     */
    public function changeRoles(
        Roles $roles,
        ?UserId $changedBy,
        DateTime $dateChanged
    ): void
    {
        if ($this->roles->equals($roles)) {
            return;
        }

        $this->apply(new RolesChanged(
            $this->id,
            $roles,
            $this->roles,
            $changedBy,
            $dateChanged
        ));
    }

    /**
     * @param UserId|null $disabledBy
     */
    public function disable(?UserId $disabledBy): void
    {
        $this->state->setDisabled($disabledBy, DateTime::now());
    }

    /**
     * @param UserId|null $enabledBy
     */
    public function enable(?UserId $enabledBy): void
    {
        $this->state->setEnabled($enabledBy, DateTime::now());
    }

    /**
     * @throws AssertionFailedException
     */
    public function assertEnabled(): void
    {
        $this->state->assertEnabled();
    }

    public function id(): ?UserId
    {
        return $this->id;
    }

    public function email(): ?Email
    {
        return $this->email;
    }

    /**
     * @param Email                             $email
     * @param UniqueEmailSpecificationInterface $uniqueEmailSpecification
     *
     * @throws EmailAlreadyExistsException
     */
    public function assertUniqueEmail(
        Email $email,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification
    ): void
    {
        $result = $uniqueEmailSpecification->isUnique($email);

        if ($result) {
            return;
        }

        throw new EmailAlreadyExistsException($email);
    }

    public function getAggregateRootId(): string
    {
        return $this->id->toString();
    }

    protected function getChildEntities(): array
    {
        return [
            $this->state,
        ];
    }

    public function changeState(UserStateInterface $state): void
    {
        $this->state = $state;
    }

    protected function applyUserRegistered(UserRegistered $event): void
    {
        $this->id = $event->id();
        $this->email = $event->email();
        $this->hashedPassword = $event->hashedPassword();
        $this->roles = $event->roles();
        $this->registeredBy = $event->registeredBy();
        $this->dateRegistered = $event->dateRegistered();
        $this->state = new Uninitialized($this);
    }

    protected function applyEmailChanged(EmailChanged $event): void
    {
        $this->email = $event->email();
    }

    protected function applyPasswordChanged(PasswordChanged $event): void
    {
        $this->hashedPassword = $event->hashedPassword();
    }

    protected function applyRolesChanged(RolesChanged $event): void
    {
        $this->roles = $event->roles();
    }

}
