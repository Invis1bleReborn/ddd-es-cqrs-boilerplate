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

namespace IdentityAccess\Infrastructure\Access\Query;

use ApiPlatform\Core\Action\NotFoundAction;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Common\Shared\Domain\ValueObject\DateTime;
use IdentityAccess\Application\Query\Access\TokenInterface;
use IdentityAccess\Ui\Access\CreateToken\CreateTokenRequest;
use IdentityAccess\Ui\Access\RefreshToken\RefreshTokenRequest;
use IdentityAccess\Ui\Access\TokenView;
use Symfony\Component\Serializer\Annotation\Ignore;

/**
 * Token.
 *
 * @see http://schema.org/accessCode Documentation on Schema.org
 *
 * @ApiResource(
 *     iri="http://schema.org/Thing",
 *     messenger="input",
 *     collectionOperations={
 *         "create"={
 *             "method"="POST",
 *             "input"=CreateTokenRequest::class,
 *             "output"=TokenView::class,
 *         },
 *         "refresh"={
 *             "method"="POST",
 *             "path"="/tokens/refresh",
 *             "input"=RefreshTokenRequest::class,
 *             "output"=TokenView::class,
 *         },
 *     },
 *     itemOperations={
 *         "get"={
 *             "controller"=NotFoundAction::class,
 *             "read"=false,
 *             "output"=false,
 *         },
 *     },
 * )
 */
class Token implements TokenInterface
{
    private string $accessToken;

    private string $refreshToken;

    private DateTime $refreshTokenDateExpired;

    public function __construct(
        string $accessToken,
        string $refreshToken,
        DateTime $refreshTokenDateExpired
    ) {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->refreshTokenDateExpired = $refreshTokenDateExpired;
    }

    /**
     * @ApiProperty(iri="http://schema.org/accessCode")
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @ApiProperty(iri="http://schema.org/accessCode")
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    /**
     * @Ignore()
     */
    public function getRefreshTokenDateExpired(): \DateTimeImmutable
    {
        $dateExpired = $this->refreshTokenDateExpired->toNative();
        /* @var $dateExpired \DateTimeImmutable */

        return $dateExpired;
    }

    /**
     * @ApiProperty(identifier=true)
     * @Ignore()
     */
    public function getId(): string
    {
        return md5($this->accessToken);
    }
}
