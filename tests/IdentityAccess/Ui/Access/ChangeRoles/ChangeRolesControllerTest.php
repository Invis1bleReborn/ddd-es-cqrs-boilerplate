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

namespace IdentityAccess\Ui\Access\ChangeRoles;

use IdentityAccess\Ui\UiTestCase;

/**
 * Class ChangeRolesControllerTest.
 */
class ChangeRolesControllerTest extends UiTestCase
{
    public function testChangeRoles(): void
    {
        $client = $this->createClient();

        $response = $this->updateResource($client, '/users/some-id/roles');

        $this->assertAuthenticationRequired($response);

        $rootUsername = 'root@acme.com';
        $rootRoles = '111111';

        $aliceUsername = 'alice@acme.com';
        $aliceRoles = '111111';

        ['userId' => $rootUserId] = $this->registerRootUser(null, $rootUsername, $rootRoles);
        ['userId' => $aliceUserId] = $this->registerUser(null, $aliceUsername, $aliceRoles);
        $this->authenticateClient($client, $aliceUsername, $aliceRoles);

        $response = $this->updateResource($client, '/users/non-existing/roles');

        $this->assertNotFound($response);

        $response = $this->updateResource($client, "/users/$rootUserId/roles");

        $this->assertForbidden($response);

        $this->authenticateClient($client, $rootUsername, $rootRoles);

        $response = $this->updateResource($client, "/users/$aliceUserId/roles", [
            'roles' => ['INVALID_ROLE'],
        ]);

        $this->assertValidationFailed($response);

        $response = $this->updateResource($client, "/users/$aliceUserId/roles", [
            'roles' => ['ROLE_SUPER_ADMIN'],
        ]);

        $this->assertNoContent($response);
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
