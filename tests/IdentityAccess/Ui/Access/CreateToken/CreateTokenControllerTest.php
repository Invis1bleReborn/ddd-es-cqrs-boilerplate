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

namespace IdentityAccess\Ui\Access\CreateToken;

use IdentityAccess\Ui\UiTestCase;

/**
 * Class CreateTokenControllerTest.
 */
class CreateTokenControllerTest extends UiTestCase
{
    public function testCreateToken(): void
    {
        $client = $this->createClient();

        $response = $this->createResource($client, '/tokens');

        $this->assertValidationFailed($response);

        $response = $this->createResource($client, '/tokens', [
            'username' => 'non-existing user',
            'password' => '111111',
        ]);

        $this->assertBadCredentials($response);

        $aliceUsername = 'alice@acme.com';
        $alicePassword = '111111';

        $this->registerUser(null, $aliceUsername, $alicePassword, [], false);

        $response = $this->createResource($client, '/tokens', [
            'username' => $aliceUsername,
            'password' => $alicePassword,
        ]);

        $this->assertAccountDisabled($response);

        $bobUsername = 'bob@acme.com';
        $bobPassword = '111111';

        $this->registerUser(null, $bobUsername, $bobPassword);

        $this->createResource($client, '/tokens', [
            'username' => $bobUsername,
            'password' => $bobPassword,
        ]);

        $this->assertCreated();
        $this->assertMatchesJsonSchema([
            '@type' => 'Token',
            'properties' => [
                'accessToken' => ['pattern' => '^[\w.-]+$'],
                'refreshToken' => ['pattern' => '^\w+$'],
            ],
            'additionalProperties' => true,
            'required' => ['accessToken', 'refreshToken'],
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
