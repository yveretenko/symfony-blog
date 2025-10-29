<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\UserService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-admin-user',
    description: 'Creates a new admin user.',
)]
class CreateAdminUserCommand extends Command
{
    public function __construct(private readonly UserService $userService)
    {
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
        $user = $this->userService->createAndFlush(
            $input->getArgument('username'),
            $input->getArgument('firstName'),
            $input->getArgument('lastName')
        );

        $output->writeln("<info>Admin user created with ID {$user->getId()}</info>");

        return Command::SUCCESS;
    }
}
