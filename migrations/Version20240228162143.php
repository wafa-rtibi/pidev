<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240228162143 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reclamation CHANGE description_reclamation description_reclamation VARCHAR(200) NOT NULL');
        $this->addSql('ALTER TABLE reponse ADD vu TINYINT(1) NOT NULL, CHANGE description description VARCHAR(2000) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reclamation CHANGE description_reclamation description_reclamation VARCHAR(2000) NOT NULL');
        $this->addSql('ALTER TABLE reponse DROP vu, CHANGE description description VARCHAR(255) NOT NULL');
    }
}
