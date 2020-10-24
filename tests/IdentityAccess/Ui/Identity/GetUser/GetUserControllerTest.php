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

namespace IdentityAccess\Ui\Identity\GetUser;

use IdentityAccess\Ui\UiTestCase;

/**
 * Class GetUserControllerTest.
 */
class GetUserControllerTest extends UiTestCase
{
    public function testGetUser(): void
    {
        $client = $this->createClient();

        $response = $this->getResource($client, '/users/some-id');

        $this->assertAuthenticationRequired($response);

        $rootUsername = 'root@acme.com';
        $rootPassword = '111111';

        ['userId' => $rootUserId] = $this->registerRootUser(null, $rootUsername, $rootPassword);
        $this->authenticateClient($client, $rootUsername, $rootPassword);

        $response = $this->getResource($client, "/users/{$this->getUUID4stub()}");

        $this->assertNotFound($response);

        $response = $this->getResource($client, "/users/$rootUserId");

        $this->assertOk($response);
        $this->assertMatchesJsonSchema([
            'properties' => [
                'id' => ['type' => 'string'],
                'email' => ['type' => 'string'],
                'roles' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'string',
                        'pattern' => '^ROLE_[A-Z_]+$',
                    ],
                ],
                'enabled' => ['type' => 'boolean'],
                'registeredById' => ['type' => ['string', 'null']],
                'dateRegistered' => ['type' => 'string'],
            ],
            'additionalProperties' => true,
            'required' => ['id', 'email', 'roles', 'enabled', 'dateRegistered'],
        ]);
    }

    protected function setUp(): void
    {
        $this->setUpStorage();
    }

    protected function tearDown(): void
    {
        $this->tearDownStorage();
    }
}
