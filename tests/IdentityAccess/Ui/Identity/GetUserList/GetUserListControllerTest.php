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

namespace IdentityAccess\Ui\Identity\GetUserList;

use IdentityAccess\Ui\UiTestCase;

/**
 * Class GetUserListControllerTest.
 */
class GetUserListControllerTest extends UiTestCase
{
    public function testGetUserList(): void
    {
        $client = $this->createClient();

        $response = $this->getResource($client, '/users');

        $this->assertAuthenticationRequired($response);

        $rootUsername = 'root@acme.com';
        $rootPassword = '111111';

        $this->registerRootUser(null, $rootUsername, $rootPassword);
        $this->authenticateClient($client, $rootUsername, $rootPassword);

        $response = $this->getResource(
            $client,
            '/users?email=acme&enabled=1&dateRegistered[after]=01-01-2021&' .
            '_order[email]=asc&_order[enabled]=desc&_order[dateRegistered]=desc'
        );

        $this->assertOk($response);

        $this->assertListItemMatchesJsonSchema([
            'type' => 'object',
            'properties' => [
                'id' => ['type' => 'string'],
                'email' => ['type' => 'email'],
                'roles' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'string',
                        'pattern' => '^ROLE_[A-Z_]+$',
                    ],
                ],
                'enabled' => ['type' => 'boolean'],
                'dateRegistered' => ['type' => 'string'],
            ],
            'additionalProperties' => true,
            'required' => ['id', 'email', 'roles', 'enabled', 'dateRegistered'],
        ], '/users', [
            '_order[email]' => 'email',
            '_order[enabled]' => 'enabled',
            '_order[dateRegistered]' => 'dateRegistered',
            '_properties[]' => null,
            'email' => 'email',
            'enabled' => 'enabled',
            'dateRegistered[before]' => 'dateRegistered',
            'dateRegistered[strictly_before]' => 'dateRegistered',
            'dateRegistered[after]' => 'dateRegistered',
            'dateRegistered[strictly_after]' => 'dateRegistered',
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
