<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240222162555 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande_offre ADD demandeur_id INT DEFAULT NULL, CHANGE statut statut VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE demande_offre ADD CONSTRAINT FK_5958054695A6EE59 FOREIGN KEY (demandeur_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_5958054695A6EE59 ON demande_offre (demandeur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande_offre DROP FOREIGN KEY FK_5958054695A6EE59');
        $this->addSql('DROP INDEX IDX_5958054695A6EE59 ON demande_offre');
        $this->addSql('ALTER TABLE demande_offre DROP demandeur_id, CHANGE statut statut TINYINT(1) NOT NULL');
    }
}
