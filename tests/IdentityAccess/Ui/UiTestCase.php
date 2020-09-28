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

use Common\Shared\Ui\UiTestCase as BaseUiTestCase;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UiTestCase.
 */
abstract class UiTestCase extends BaseUiTestCase
{
    protected function registerRootUser(?string $uuid, string $username, string $password): array
    {
        $options = [
            'roles' => 'ROLE_SUPER_ADMIN',
        ];

        if (null !== $uuid) {
            $options['uuid'] = $uuid;
        }

        $pairs = [];

        foreach ($options as $name => $value) {
            $pairs[] = sprintf('--%s=%s', $name, $value);
        }

        $output = $this->executeCommand(sprintf(
            'app:user:register %s %s %s',
            $username,
            $password,
            implode(' ', $pairs)
        ), OutputInterface::VERBOSITY_VERBOSE);

        preg_match('#<metadata>(.+)</metadata>#su', $output, $matches);

        return json_decode($matches[1], true);
    }
}
