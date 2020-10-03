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

namespace IdentityAccess\Ui\Identity\ChangeEmail;

use IdentityAccess\Ui\UiTestCase;

/**
 * Class ChangeEmailControllerTest.
 */
class ChangeEmailControllerTest extends UiTestCase
{
    public function testChangeEmail(): void
    {
        $client = $this->createClient();

        $response = $this->updateResource($client, '/users/some-id/email');

        $this->assertAuthenticationRequired($response);

        $rootUsername = 'root@acme.com';
        $rootEmail = '111111';

        $aliceUsername = 'alice@acme.com';
        $aliceEmail = '111111';

        ['userId' => $rootUserId] = $this->registerRootUser(null, $rootUsername, $rootEmail);
        ['userId' => $aliceUserId] = $this->registerUser(null, $aliceUsername, $aliceEmail);
        $this->authenticateClient($client, $aliceUsername, $aliceEmail);

        $response = $this->updateResource($client, '/users/non-existing/email');

        $this->assertNotFound($response);

        $response = $this->updateResource($client, "/users/$rootUserId/email");

        $this->assertForbidden($response);

        $response = $this->updateResource($client, "/users/$aliceUserId/email", [
            'email' => 'invalid email',
        ]);

        $this->assertValidationFailed($response);

        $response = $this->updateResource($client, "/users/$aliceUserId/email", [
            'email' => 'newalice@acme.com',
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
