<?php

namespace App\Command;

use App\Security\User;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Authentication\RememberMe\PersistentToken;
use Symfony\Component\Security\Core\Authentication\RememberMe\TokenProviderInterface;
use Symfony\Component\Security\Core\User\InMemoryUser;
use Symfony\Component\Security\Http\RememberMe\RememberMeDetails;

#[AsCommand(name: 'app:cookie:create')]
class CreateRememberCookieCommand extends Command
{
    public function __construct(private readonly TokenProviderInterface $tokenProvider)
    {
        parent::__construct(self::getDefaultName());
    }

    protected function configure(): void
    {
        $this->addArgument('username', InputArgument::REQUIRED);
        $this->addArgument('lifetime', InputArgument::OPTIONAL, default: 604800);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $series = random_bytes(66);
        $tokenValue = strtr(base64_encode(substr($series, 33)), '+/=', '-_~');
        $series = strtr(base64_encode(substr($series, 0, 33)), '+/=', '-_~');
        $token = new PersistentToken(InMemoryUser::class, $input->getArgument('username'), $series, $tokenValue, new \DateTimeImmutable());

        $this->tokenProvider->createNewToken($token);
        $rememberMeDetails = RememberMeDetails::fromPersistentToken($token, time() + $input->getArgument('lifetime'));

        $cookieFragments = explode(':', $rememberMeDetails->toString());


        $io->info('Complete cookie: ' . str_replace(':', '%3A', $rememberMeDetails->toString()));
        $io->info('User fragment: '.$cookieFragments[1]);
        $io->info('Serie fragment: '.$cookieFragments[3]);

        return Command::SUCCESS;
    }
}