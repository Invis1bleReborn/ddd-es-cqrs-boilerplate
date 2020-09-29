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

namespace IdentityAccess\Ui\Identity\ChangeUserStatus;

use IdentityAccess\Ui\UiTestCase;

/**
 * Class ChangeUserStatusControllerTest.
 */
class ChangeUserStatusControllerTest extends UiTestCase
{
    public function testChangeUserStatus(): void
    {
        $client = $this->createClient();

        $response = $this->updateResource($client, '/users/some-id/status');

        $this->assertUnauthorized($response);

        $rootUsername = 'root@acme.com';
        $rootPassword = '111111';

        $aliceUsername = 'alice@acme.com';
        $alicePassword = '111111';

        ['userId' => $rootUserId] = $this->registerRootUser(null, $rootUsername, $rootPassword);
        ['userId' => $aliceUserId] = $this->registerUser(null, $aliceUsername, $alicePassword);
        $this->authenticateClient($client, $aliceUsername, $alicePassword);

        $response = $this->updateResource($client, '/users/non-existing/status');

        $this->assertNotFound($response);

        $response = $this->updateResource($client, "/users/$rootUserId/status");

        $this->assertValidationFailed($response);

        $response = $this->updateResource($client, "/users/$rootUserId/status", [
            'enabled' => false,
        ]);

        $this->assertForbidden($response);

        $this->authenticateClient($client, $rootUsername, $rootPassword);

        $response = $this->updateResource($client, "/users/$aliceUserId/status", [
            'enabled' => false,
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
