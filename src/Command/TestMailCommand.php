<?php

// src/Command/TestMailCommand.php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mime\Address;

#[AsCommand(
    name: 'app:test-mail',
    description: 'Envoie un email HTML stylisé avec une pièce jointe PDF.',
)]
class TestMailCommand extends Command
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        parent::__construct();
        $this->mailer = $mailer;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Chemin vers une pièce jointe fictive (fichier à adapter)
        $pdfPath = __DIR__ . '/../../public/sample.pdf';
        if (!file_exists($pdfPath)) {
            file_put_contents($pdfPath, '%PDF-1.4 Exemple PDF');
        }

        $email = (new Email())
            ->from(new Address('angelique.chelmi@livecampus.net', 'Bot Forum'))
            ->to('angelique.chelmi@livecampus.net') // À adapter
            ->subject('🎉 Test Mail Symfony avec PJ et HTML')
            ->html("
                <div style='background-color: #f8f9fa; padding: 30px; text-align: center;'>
                    <h1 style='color: #007BFF;'>Ceci est un test 🎉</h1>
                    <p>Mail stylisé envoyé depuis une commande Symfony.</p>
                </div>
            ")
            ->attachFromPath($pdfPath, 'document.pdf');

        $this->mailer->send($email);

        $io->success('📧 Email envoyé avec succès !');

        return Command::SUCCESS;
    }
}
