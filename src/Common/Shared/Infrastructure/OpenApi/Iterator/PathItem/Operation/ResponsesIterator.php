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

namespace Common\Shared\Infrastructure\OpenApi\Iterator\PathItem\Operation;

use ApiPlatform\Core\OpenApi\Model\Operation;
use ApiPlatform\Core\OpenApi\Model\Response;

/**
 * Class OperationResponsesIterator.
 */
class ResponsesIterator implements \IteratorAggregate
{
    /**
     * @var array<int, Response>
     */
    protected array $responses;

    public function __construct(Operation $operation)
    {
        $this->responses = $operation->getResponses();
    }

    /**
     * @return iterable<string, Response>
     */
    public function getIterator(): iterable
    {
        yield from $this->responses;
    }
}
