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

namespace Common\Shared\Ui;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Response;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UiTestCase.
 */
abstract class UiTestCase extends ApiTestCase
{
    public static function assertCreated(string $contentType = 'application/ld+json; charset=utf-8'): void
    {
        static::assertResponseStatusCodeSame(201);
        static::assertContentType($contentType);
        static::assertContainsLocation();
    }

    public static function assertValidationFailed(
        Response $response,
        string $description = null,
        array $violations = null
    ): void {
        static::assertResponseStatusCodeSame(400);
        static::assertJsonResponse($response);

        $subset = [
            '@context' => static::getApiUriPrefix() . '/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
        ];

        if (null !== $description) {
            $subset['hydra:description'] = $description;
        }

        if (null !== $violations) {
            $subset['violations'] = $violations;
        }

        static::assertJsonContains($subset);
    }

    public static function assertUnauthorized(string $contentType = 'application/ld+json; charset=utf-8'): void
    {
        static::assertResponseStatusCodeSame(401);
        static::assertContentType($contentType);
    }

    public static function assertJsonResponse(
        Response $response,
        string $contentType = 'application/ld+json; charset=utf-8'
    ): void {
        static::assertContentType($contentType);
        static::assertJson($response->getContent(false), 'Response content is not a valid JSON document.');
    }

    public static function assertContentType(string $type, string $message = ''): void
    {
        static::assertResponseHeaderSame('Content-Type', $type, $message);
    }

    public static function assertContainsLocation(string $message = null): void
    {
        static::assertResponseHasHeader('Location', $message ?? 'Response does not contain Location header.');
    }

    protected function getResource(
        Client $client,
        string $uri,
        array $options = [],
        bool $useApiUriPrefix = true
    ): Response {
        return $this->requestResource($client, 'GET', $uri, $options, null, $useApiUriPrefix);
    }

    protected function createResource(
        Client $client,
        string $uri,
        array $content = null,
        array $options = [],
        bool $useApiUriPrefix = true
    ): Response {
        return $this->requestResource($client, 'POST', $uri, $options, $content, $useApiUriPrefix);
    }

    protected function updateResource(
        Client $client,
        string $uri,
        array $content = null,
        array $options = [],
        bool $useApiUriPrefix = true
    ): Response {
        return $this->requestResource($client, 'PUT', $uri, $options, $content, $useApiUriPrefix);
    }

    protected function deleteResource(
        Client $client,
        string $uri,
        array $options = [],
        bool $useApiUriPrefix = true
    ): Response {
        return $this->requestResource($client, 'DELETE', $uri, $options, null, $useApiUriPrefix);
    }

    protected function requestResource(
        Client $client,
        string $method,
        string $uri,
        array $options = [],
        array $content = null,
        bool $useApiUriPrefix = true
    ): Response {
        if (null !== $content) {
            $options['json'] = $content;
        }

        return $client->request(
            $method,
            ($useApiUriPrefix ? $this->getApiUriPrefix() : '') . $uri,
            $options + [
                'headers' => [
                    'Accept' => 'application/ld+json',
                    'Content-Type' => 'application/ld+json',
                ],
            ]
        );
    }

    protected static function getApiUriPrefix(): string
    {
        return '/api';
    }

    protected function setUpStorage(): void
    {
        foreach ($this->storageSetUpCommandProvider() as $command) {
            $this->executeCommand($command);
        }
    }

    protected function tearDownStorage(): void
    {
        foreach ($this->storageTearDownCommandProvider() as $command) {
            $this->executeCommand($command);
        }
    }

    protected function storageSetUpCommandProvider(): iterable
    {
        yield 'doctrine:database:create';
        yield 'doctrine:migrations:migrate';
        yield 'doctrine:schema:create';
    }

    protected function storageTearDownCommandProvider(): iterable
    {
        yield 'doctrine:database:drop --if-exists --force';
    }

    protected function executeCommand(string $command, int $verbosityLevel = OutputInterface::VERBOSITY_QUIET): string
    {
        if (null !== static::$kernel) {
            static::$kernel->shutdown();
        }

        $kernel = static::$kernel = static::createKernel(['environment' => 'test']);
        $kernel->boot();

        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new StringInput($command);
        $input->setInteractive(false);

        $output = new BufferedOutput($verbosityLevel);

        $application->run($input, $output);

        return $output->fetch();
    }
}
