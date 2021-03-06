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

namespace IdentityAccess\Ui\Identity\ChangePassword;

use IdentityAccess\Ui\UiTestCase;

/**
 * Class ChangePasswordControllerTest.
 */
class ChangePasswordControllerTest extends UiTestCase
{
    public function testChangePassword(): void
    {
        $client = $this->createClient();

        $response = $this->updateResource($client, '/users/some-id/password');

        $this->assertAuthenticationRequired($response);

        $rootUsername = 'root@acme.com';
        $rootPassword = '111111';

        $aliceUsername = 'alice@acme.com';
        $alicePassword = '111111';

        ['userId' => $rootUserId] = $this->registerRootUser(null, $rootUsername, $rootPassword);
        ['userId' => $aliceUserId] = $this->registerUser(null, $aliceUsername, $alicePassword);
        $this->authenticateClient($client, $aliceUsername, $alicePassword);

        $response = $this->updateResource($client, "/users/{$this->getUUID4stub()}/password");

        $this->assertNotFound($response);

        $response = $this->updateResource($client, "/users/$rootUserId/password");

        $this->assertForbidden($response);

        $response = $this->updateResource($client, "/users/$aliceUserId/password", [
            'currentPassword' => 'wrong password',
            'password' => 'new password',
            'passwordConfirmation' => 'new password',
        ]);

        $this->assertValidationFailed($response);

        $response = $this->updateResource($client, "/users/$aliceUserId/password", [
            'currentPassword' => $alicePassword,
            'password' => 'new password',
            'passwordConfirmation' => 'new password',
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
