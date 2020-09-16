<?php

declare(strict_types=1);

/*
 * This file is part of invis1ble/ddd-es-cqrs-boilerplate.
 *
 * (c) Invis1ble <opensource.invis1ble@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IdentityAccess\Ui\Identity\RegisterUser;

use ApiPlatform\Core\Validator\ValidatorInterface;
use Common\Shared\Application\Bus\Command\CommandBusInterface;
use Common\Shared\Domain\ValueObject\UuidGeneratorInterface;
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
 * Class RegisterUserCommand
 *
 * @package IdentityAccess\Ui\Identity\RegisterUser
 */
class RegisterUserCommand extends Command
{
    private ValidatorInterface $validator;

    private UserProviderInterface $userProvider;

    private UuidGeneratorInterface $uuidGenerator;

    private CommandBusInterface $commandBus;

    public function __construct(
        ValidatorInterface $validator,
        UuidGeneratorInterface $uuidGenerator,
        UserProviderInterface $userProvider,
        CommandBusInterface $commandBus,
        string $name = null
    )
    {
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
            ->setDescription('Given a credentials, registers a new user. Reads and interprets stdin content as password in non-interactive mode.')
            ->addArgument('email', InputArgument::REQUIRED, 'User email')
            ->addArgument('password', InputArgument::OPTIONAL, 'User password')
            ->addOption('uuid', 'id', InputArgument::OPTIONAL, 'User UUID')
            ->addOption('roles', 'r', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'User roles', ['ROLE_USER'])
            ->addOption('disabled', 'd', InputOption::VALUE_NONE, 'Disabled user')
            ->addOption('registered-by', 'by', InputOption::VALUE_OPTIONAL, 'Registered by (email)')
            ->addOption('show-metadata', null, InputOption::VALUE_NONE, 'Output resulting metadata (e.g. generated user id)')
        ;
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('User registration');

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

        $io->newLine();

        $request = new RegisterUserRequest(
            $input->getArgument('email'),
            $password,
            $password,
            !$input->getOption('disabled'),
            $input->getOption('roles')
        );


        $this->validator->validate($request);

        $userId = $input->getOption('uuid') ?? ($this->uuidGenerator)();

        $metadata = [
            'userId' => $userId,
        ];

        $registeredBy = $input->getOption('registered-by');

        if (null !== $registeredBy) {
            try {
                $registeredBy = $this->userProvider->loadUserByUsername($registeredBy);
            } catch (UsernameNotFoundException $exception) {
                $io->error($exception->getMessage());

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

        $this->commandBus->handle($command);

        if ($input->getOption('show-metadata')) {
            $io->text(sprintf('[metadata]%s[/metadata]', json_encode($metadata)));
            $io->newLine();
        }

        $io->success(sprintf('User "%s" successfully registered.', $request->email));

        return 0;
    }

}
