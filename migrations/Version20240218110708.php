<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240218110708 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offre ADD image1 VARCHAR(255) NOT NULL, ADD image2 VARCHAR(255) NOT NULL, ADD image3 VARCHAR(255) NOT NULL, DROP photos');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offre ADD photos JSON NOT NULL COMMENT \'(DC2Type:json)\', DROP image1, DROP image2, DROP image3');
    }
}
