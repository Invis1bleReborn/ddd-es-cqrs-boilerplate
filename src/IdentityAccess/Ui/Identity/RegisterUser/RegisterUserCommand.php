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

use ApiPlatform\Core\Validator\Exception\ValidationException;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Common\Shared\Application\Bus\Command\CommandBusInterface;
use Common\Shared\Domain\ValueObject\UuidGeneratorInterface;
use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Application\Query\Identity\UserProviderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

/**
 * Class RegisterUserCommand.
 */
class RegisterUserCommand extends Command
{
    public const OUTPUT_MODE = [
        'UI' => 'ui',
        'DATA' => 'data',
    ];

    private ValidatorInterface $validator;

    private UserProviderInterface $userProvider;

    private UuidGeneratorInterface $uuidGenerator;

    private CommandBusInterface $commandBus;

    private SymfonyStyle $io;

    private bool $outputUi;

    private bool $outputData;

    public function __construct(
        ValidatorInterface $validator,
        UuidGeneratorInterface $uuidGenerator,
        UserProviderInterface $userProvider,
        CommandBusInterface $commandBus,
        string $name = null
    ) {
        parent::__construct($name);

        $this->validator = $validator;
        $this->userProvider = $userProvider;
        $this->uuidGenerator = $uuidGenerator;
        $this->commandBus = $commandBus;
    }

    protected function configure(): void
    {
        $this
            ->setName('app:user:register')
            ->setDescription(
                <<<'DESCRIPTION'
Given a credentials, registers a new user. Reads and interprets stdin content as password in non-interactive mode.
DESCRIPTION
            )
            ->addArgument('username', InputArgument::REQUIRED, 'Username')
            ->addArgument('password', InputArgument::OPTIONAL, 'User password')
            ->addOption('uuid', 'id', InputArgument::OPTIONAL, 'User UUID')
            ->addOption(
                'roles',
                'r',
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'User roles',
                ['ROLE_USER']
            )
            ->addOption('disabled', 'd', InputOption::VALUE_NONE, 'Disabled user')
            ->addOption('registered-by', 'by', InputOption::VALUE_OPTIONAL, 'Registered by (username)')
            ->addOption(
                'output',
                'o',
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                sprintf(
                    'Output mode (available modes are %s)',
                    implode(', ', array_map(fn (string $mode): string => '"' . $mode . '"', self::OUTPUT_MODE))
                ),
                [self::OUTPUT_MODE['UI']]
            )
        ;
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $this->io = new SymfonyStyle($input, $output);

        $outputMode = $input->getOption('output');
        $this->outputUi = in_array(self::OUTPUT_MODE['UI'], $outputMode);
        $this->outputData = in_array(self::OUTPUT_MODE['DATA'], $outputMode);

        if ($this->outputUi) {
            $this->io->title('User registration');
        }

        $password = $input->getArgument('password');

        if (null === $password) {
            if ($input->isInteractive()) {
                $helper = $this->getHelper('question');

                $question = new Question('User password: ');
                $question->setHidden(true);
                $question->setHiddenFallback(false);

                $password = $helper->ask($input, $output, $question);
            } else {
                $password = file_get_contents('php://stdin');
            }
        }

        $request = new RegisterUserRequest(
            $input->getArgument('username'),
            $password,
            $password,
            !$input->getOption('disabled'),
            $input->getOption('roles')
        );

        try {
            $this->wrap(function () use ($request) {
                $this->validator->validate($request);
            });
        } catch (ValidationException $exception) {
            return 1;
        }

        $userId = $input->getOption('uuid') ?? ($this->uuidGenerator)();

        $metadata = [
            'userId' => $userId,
        ];

        $registeredBy = $input->getOption('registered-by');

        if (null !== $registeredBy) {
            try {
                $registeredBy = $this->wrap(
                    fn (): UserInterface => $this->userProvider->loadUserByUsername($registeredBy)
                );
            } catch (UsernameNotFoundException $exception) {
                return 1;
            }

            $registeredBy = $registeredBy->getId();

            $metadata['registeredById'] = $registeredBy;
        }

        $command = new \IdentityAccess\Application\Command\Identity\RegisterUser\RegisterUserCommand(
            $userId,
            $request->email,
            $request->password,
            $request->enabled,
            $request->roles,
            $registeredBy
        );

        try {
            $this->wrap(function () use ($command) {
                $this->commandBus->handle($command);
            });
        } catch (\Throwable $exception) {
            return 1;
        }

        if ($this->outputData) {
            $this->io->writeln(json_encode($metadata));

            if ($this->outputUi) {
                $this->io->newLine();
            }
        }

        if ($this->outputUi) {
            $this->io->success(sprintf('User "%s" successfully registered.', $request->email));
        }

        return 0;
    }

    protected function wrap(callable $codeBlock)
    {
        try {
            return $codeBlock();
        } catch (\Throwable $exception) {
            $errorText = $exception->getMessage();

            if ($this->outputData) {
                $this->io->writeln(json_encode(['error' => $errorText]));
            }

            if ($this->outputUi) {
                $this->io->error($errorText);
            }

            throw $exception;
        }
    }
}
