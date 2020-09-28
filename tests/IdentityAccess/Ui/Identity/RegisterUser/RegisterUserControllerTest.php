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

namespace IdentityAccess\Ui\Identity\RegisterUser;

use IdentityAccess\Ui\UiTestCase;

/**
 * Class RegisterUserControllerTest.
 */
class RegisterUserControllerTest extends UiTestCase
{
    public function testRegisterUser(): void
    {
        $anonymous = $this->createClient();

        $email = 'alice@acme.com';
        $password = '111111';


        $response = $this->createResource($anonymous, '/users', [
            'password' => $password,
            'passwordConfirmation' => '222222',
        ]);

        $this->assertValidationFailed($response);

        $this->createResource($anonymous, '/users', [
            'email' => $email,
            'password' => $password,
            'passwordConfirmation' => $password,
            'enabled' => true,
        ]);

        $this->assertCreated();
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
