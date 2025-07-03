<?php
namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:test-cli',
    description: 'Test des couleurs et formats dans la console Symfony',
)]
class TestCliCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('🎨 Test CLI Stylisé');
        $io->text('Un simple texte...');
        $io->section('Section importante');
        $io->note('Ceci est une note');
        $io->caution('Attention ! Ceci est une mise en garde.');
        $io->success('Tout fonctionne parfaitement !');
        $io->error('Une erreur pour tester le rouge 🔴');

        return Command::SUCCESS;
    }
}
