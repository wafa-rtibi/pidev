<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240222210142 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE administrateur ADD photoprofil_file VARCHAR(255) DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL, CHANGE photoprofil photoprofil VARCHAR(255) DEFAULT NULL, CHANGE role roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE utilisateur ADD photoprofil_file VARCHAR(255) DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL, CHANGE photoprofil photoprofil VARCHAR(255) DEFAULT NULL, CHANGE role roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE administrateur DROP photoprofil_file, DROP updated_at, CHANGE photoprofil photoprofil VARCHAR(255) NOT NULL, CHANGE roles role JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE utilisateur DROP photoprofil_file, DROP updated_at, CHANGE photoprofil photoprofil VARCHAR(255) NOT NULL, CHANGE roles role JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }
}
