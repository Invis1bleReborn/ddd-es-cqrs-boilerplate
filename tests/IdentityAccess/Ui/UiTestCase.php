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

namespace IdentityAccess\Ui;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Response;
use Common\Shared\Ui\UiTestCase as BaseUiTestCase;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UiTestCase.
 */
abstract class UiTestCase extends BaseUiTestCase
{
    protected function registerRootUser(?string $uuid, string $username, string $password): array
    {
        return $this->registerUser($uuid, $username, $password, ['ROLE_SUPER_ADMIN']);
    }

    protected function registerUser(
        ?string $uuid,
        string $username,
        string $password,
        array $roles = [],
        bool $enabled = true
    ): array {
        $options = [
            'roles' => $roles,
        ];

        if (null !== $uuid) {
            $options['uuid'] = $uuid;
        }

        $pairs = [];

        foreach ($options as $name => $value) {
            if (!is_array($value)) {
                $value = [$value];
            }

            $pairs = array_merge(
                $pairs,
                array_map(fn (string $v): string => sprintf('--%s=%s', $name, $v), $value)
            );
        }

        $command = sprintf(
            'app:user:register %s %s %s --output=data',
            $username,
            $password,
            implode(' ', $pairs)
        );

        if (!$enabled) {
            $command .= ' --disabled';
        }

        return json_decode(
            $this->executeCommand($command, OutputInterface::VERBOSITY_VERBOSE),
            true
        );
    }

    protected function createAuthenticatedClient(string $username, string $password): Client
    {
        $client = $this->createClient();

        $this->authenticateClient($client, $username, $password);

        return $client;
    }

    protected function authenticateClient(Client $client, string $username, string $password): void
    {
        $this->setTokenToClient(
            $client,
            $this->requestToken($client, $username, $password)->toArray()['accessToken']
        );
    }

    protected function resetClientAuthentication(Client $client): void
    {
        $this->removeTokenFromClient($client);
    }

    protected function setTokenToClient(Client $client, string $token): void
    {
        $client->setDefaultOptions(['auth_bearer' => $token]);
    }

    protected function removeTokenFromClient(Client $client): void
    {
        $client->setDefaultOptions(['auth_bearer' => null]);
    }

    protected function requestToken(Client $client, string $username, string $password): Response
    {
        return $this->createResource($client, '/tokens', [
            'username' => $username,
            'password' => $password,
        ]);
    }
}
