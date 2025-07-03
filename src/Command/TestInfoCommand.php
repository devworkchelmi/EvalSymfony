<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:test-info',
    description: 'Demande quelques infos et affiche le résultat coloré.',
)]
class TestInfoCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('🧪 Test de saisie utilisateur');

        $prenom = $io->ask('Quel est ton prénom ?');
        $nom = $io->ask('Quel est ton nom ?');
        $age = $io->ask('Quel est ton âge ?');

        $io->success('Merci pour les informations !');

        $io->section('📋 Résumé :');
        $io->text([
            "<fg=cyan>Prénom :</> $prenom",
            "<fg=cyan>Nom :</> $nom",
            "<fg=cyan>Âge :</> $age ans",
        ]);

        $io->newLine();
        $io->success("🎉 Bienvenue $prenom $nom !");

        return Command::SUCCESS;
    }
}
