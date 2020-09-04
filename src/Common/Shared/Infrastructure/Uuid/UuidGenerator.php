<?php

declare(strict_types=1);

namespace Common\Shared\Infrastructure\Uuid;

use Common\Shared\Domain\ValueObject\UuidGeneratorInterface;

/**
 * Class UuidGenerator
 *
 * @package Common\Shared\Infrastructure\Uuid
 */
class UuidGenerator implements UuidGeneratorInterface
{
    private \Broadway\UuidGenerator\UuidGeneratorInterface $uuidGenerator;

    public function __construct(\Broadway\UuidGenerator\UuidGeneratorInterface $uuidGenerator)
    {
        $this->uuidGenerator = $uuidGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(): string
    {
        return $this->uuidGenerator->generate();
    }

}
