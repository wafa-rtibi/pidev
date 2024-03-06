<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240305233016 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appel_au_don ADD dons_id INT NOT NULL, ADD organisation_id INT NOT NULL');
        $this->addSql('ALTER TABLE appel_au_don ADD CONSTRAINT FK_E2212D5DDDBFD07B FOREIGN KEY (dons_id) REFERENCES dons (id)');
        $this->addSql('ALTER TABLE appel_au_don ADD CONSTRAINT FK_E2212D5D9E6B1585 FOREIGN KEY (organisation_id) REFERENCES organisation (id)');
        $this->addSql('CREATE INDEX IDX_E2212D5DDDBFD07B ON appel_au_don (dons_id)');
        $this->addSql('CREATE INDEX IDX_E2212D5D9E6B1585 ON appel_au_don (organisation_id)');
        $this->addSql('ALTER TABLE organisation ADD image_organisation VARCHAR(255) NOT NULL, CHANGE rib rib VARCHAR(12) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appel_au_don DROP FOREIGN KEY FK_E2212D5DDDBFD07B');
        $this->addSql('ALTER TABLE appel_au_don DROP FOREIGN KEY FK_E2212D5D9E6B1585');
        $this->addSql('DROP INDEX IDX_E2212D5DDDBFD07B ON appel_au_don');
        $this->addSql('DROP INDEX IDX_E2212D5D9E6B1585 ON appel_au_don');
        $this->addSql('ALTER TABLE appel_au_don DROP dons_id, DROP organisation_id');
        $this->addSql('ALTER TABLE organisation DROP image_organisation, CHANGE rib rib VARCHAR(24) NOT NULL');
    }
}
