<?php

namespace App\Command;

use App\Security\User;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(name: 'app:password:hash')]
class HashPasswordCommand extends Command
{
    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
        parent::__construct(self::getDefaultName());
    }

    protected function configure(): void
    {
        $this->addArgument('username', InputArgument::REQUIRED);
        $this->addArgument('password', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $password = $this->hasher->hashPassword(new User($input->getArgument('username'), ''), $input->getArgument('password'));
        $io->success(sprintf('Password hashed: %s', $password));

        return Command::SUCCESS;
    }
}