<?php

declare(strict_types=1);

namespace IdentityAccess\Infrastructure\Access\Request;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use IdentityAccess\Application\Command\Access\CreateToken\CreateTokenCommand;
use IdentityAccess\Infrastructure\Access\Query\Token;
use IdentityAccess\Ui\Access\CreateToken\CreateTokenRequest;
use IdentityAccess\Ui\Access\CreateToken\CreateTokenRequestTransformerInterface;

/**
 * Class CreateTokenRequestTransformerAdapter
 *
 * @package IdentityAccess\Infrastructure\Access\Request
 */
class CreateTokenRequestTransformerAdapter implements DataTransformerInterface
{
    private CreateTokenRequestTransformerInterface $createTokenRequestTransformer;

    public function __construct(CreateTokenRequestTransformerInterface $createTokenRequestTransformer)
    {
        $this->createTokenRequestTransformer = $createTokenRequestTransformer;
    }

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
