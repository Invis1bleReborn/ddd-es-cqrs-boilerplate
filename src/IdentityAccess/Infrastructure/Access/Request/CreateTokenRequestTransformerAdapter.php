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
use IdentityAccess\Application\Command\Access\CreateToken\CreateTokenCommand;
use IdentityAccess\Infrastructure\Access\Query\Token;
use IdentityAccess\Ui\Access\AccountStatusExceptionInterface;
use IdentityAccess\Ui\Access\CreateToken\CreateTokenRequest;
use IdentityAccess\Ui\Access\CreateToken\CreateTokenRequestTransformerInterface;

/**
 * Class CreateTokenRequestTransformerAdapter.
 */
class CreateTokenRequestTransformerAdapter implements DataTransformerInterface
{
    private CreateTokenRequestTransformerInterface $createTokenRequestTransformer;

    public function __construct(CreateTokenRequestTransformerInterface $createTokenRequestTransformer)
    {
        $this->createTokenRequestTransformer = $createTokenRequestTransformer;
    }

    /**
     * {@inheritdoc}
     *
     * @throws ValidationException
     * @throws AccountStatusExceptionInterface
     * @throws AssertionFailedException
     */
    public function transform($data, string $to, array $context = [])
    {
        /* @var $data CreateTokenRequest */

        return ($this->createTokenRequestTransformer)($data);
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof CreateTokenCommand) {
            return false;
        }

        return Token::class === $to &&
            isset($context['input']['class']) && CreateTokenRequest::class === $context['input']['class']
        ;
    }
}
