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

namespace IdentityAccess\Ui\Access\RefreshToken;

use IdentityAccess\Ui\UiTestCase;

/**
 * Class RefreshTokenControllerTest.
 */
class RefreshTokenControllerTest extends UiTestCase
{
    public function testRefreshToken(): void
    {
        $client = $this->createClient();

        $response = $this->createResource($client, '/refresh_tokens');

        $this->assertValidationFailed($response);

        $response = $this->createResource($client, '/refresh_tokens', [
            'refreshToken' => 'non-existing token',
        ]);

        $this->assertUnauthorized($response, 'Refresh token does not exist.');

        $aliceUsername = 'alice@acme.com';
        $alicePassword = '111111';

        $this->registerUser(null, $aliceUsername, $alicePassword);

        $response = $this->requestToken($client, $aliceUsername, $alicePassword)
            ->toArray();

        $this->createResource($client, '/refresh_tokens', [
            'refreshToken' => $response['refreshToken'],
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
