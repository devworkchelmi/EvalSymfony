<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250630135450 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE document ADD id INT AUTO_INCREMENT NOT NULL, ADD created_at DATETIME NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE document MODIFY id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `PRIMARY` ON document
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE document DROP id, DROP created_at
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE document ADD PRIMARY KEY (file_name)
        SQL);
    }
}
