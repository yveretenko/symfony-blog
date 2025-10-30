<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use App\Service\UserService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(
    name: 'app:user:create-admin',
    description: 'Creates a new admin user.',
)]
class CreateAdminUserCommand extends Command
{
    public function __construct(
        private readonly UserService $userService,
        private readonly ValidatorInterface $validator
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

        $username  = $input->getArgument('username');
        $firstName = $input->getArgument('firstName');
        $lastName  = $input->getArgument('lastName');

        $user = (new User())
            ->setUsername($username)
            ->setFirstName($firstName)
            ->setLastName($lastName);

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            $io->error('Validation failed:');

            foreach ($errors as $error) {
                $io->writeln(sprintf(
                    ' - <comment>%s</comment>: %s',
                    $error->getPropertyPath(),
                    $error->getMessage()
                ));
            }

            return Command::FAILURE;
        }

        $user = $this->userService->createAndFlush($username, $firstName, $lastName);

        $io->success(sprintf('Admin user created successfully (ID: %d)', $user->getId()));

        return Command::SUCCESS;
    }
}
