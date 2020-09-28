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

namespace Common\Shared\Infrastructure\View;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use Broadway\ReadModel\Identifiable;
use Common\Shared\Ui\IdAwareView;
use Common\Shared\Ui\IdentifiableObjectTransformer;

/**
 * Class IdentifiableObjectTransformerAdapter.
 */
final class IdentifiableObjectTransformerAdapter implements DataTransformerInterface
{
    private IdentifiableObjectTransformer $identifiableObjectTransformer;

    public function __construct(IdentifiableObjectTransformer $identifiableObjectTransformer)
    {
        $this->identifiableObjectTransformer = $identifiableObjectTransformer;
    }

    /**
     * {@inheritdoc}
     *
     * @param Identifiable $object
     */
    public function transform($object, string $to, array $context = [])
    {
        return ($this->identifiableObjectTransformer)($object);
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return IdAwareView::class === $to && $data instanceof Identifiable;
    }
}
