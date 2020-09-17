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

namespace IdentityAccess\Domain\Identity\Event;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Broadway\Serializer\Serializable;
use IdentityAccess\Domain\Identity\ValueObject\UserId;

/**
 * Class UserIdAwareEvent
 *
 */
abstract class UserIdAwareEvent implements Serializable
{
    protected UserId $id;

    public function __construct(UserId $id)
    {
        $this->id = $id;
    }

    public function id(): UserId
    {
        return $this->id;
    }

    /**
     * @param array $data
     *
     * @return static
     * @throws AssertionFailedException
     */
    public static function deserialize(array $data)
    {
        Assertion::keyExists($data, 'id');

        return new static(
            UserId::fromString($data['id'])
        );
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id->toString(),
        ];
    }
}
