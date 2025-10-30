<?php

namespace App\Command;

use App\Exception\ValidationException;
use App\Service\UserService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-admin-user',
    description: 'Creates a new admin user.',
)]
class CreateAdminUserCommand extends Command
{
    public function __construct(
        private readonly UserService $userService,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('username', InputArgument::REQUIRED, 'Username of the admin')
            ->addArgument('firstName', InputArgument::REQUIRED, 'First name')
            ->addArgument('lastName', InputArgument::REQUIRED, 'Last name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $user = $this->userService->validateAndFlush(
                $input->getArgument('username'),
                $input->getArgument('firstName'),
                $input->getArgument('lastName'),
            );
        } catch (ValidationException $e) {
            $io->error('Validation failed:');

            foreach ($e->getErrors() as $error) {
                $io->writeln(sprintf(
                    ' - <comment>%s</comment>: %s',
                    $error->getPropertyPath(),
                    $error->getMessage()
                ));
            }

            return Command::FAILURE;
        }

        $io->success(sprintf('Admin user created successfully (ID: %d)', $user->getId()));

        return Command::SUCCESS;
    }
}
