<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240308121305 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE evenement_utilisateur (evenement_id INT NOT NULL, utilisateur_id INT NOT NULL, INDEX IDX_8C897598FD02F13 (evenement_id), INDEX IDX_8C897598FB88E14F (utilisateur_id), PRIMARY KEY(evenement_id, utilisateur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE evenement_utilisateur ADD CONSTRAINT FK_8C897598FD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evenement_utilisateur ADD CONSTRAINT FK_8C897598FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE administrateur CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE prenom prenom VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE username username VARCHAR(255) NOT NULL, CHANGE mdp mdp VARCHAR(255) NOT NULL, CHANGE dateinscription dateinscription DATETIME NOT NULL, CHANGE adresse adresse VARCHAR(255) NOT NULL, CHANGE tel tel INT NOT NULL, CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\', CHANGE is_active is_active TINYINT(1) NOT NULL, CHANGE salt salt VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE evenement ADD agenda_id INT DEFAULT NULL, ADD type_event VARCHAR(255) NOT NULL, ADD categorie VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681EEA67784A FOREIGN KEY (agenda_id) REFERENCES agenda (id)');
        $this->addSql('CREATE INDEX IDX_B26681EEA67784A ON evenement (agenda_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenement_utilisateur DROP FOREIGN KEY FK_8C897598FD02F13');
        $this->addSql('ALTER TABLE evenement_utilisateur DROP FOREIGN KEY FK_8C897598FB88E14F');
        $this->addSql('DROP TABLE evenement_utilisateur');
        $this->addSql('ALTER TABLE administrateur CHANGE nom nom VARCHAR(255) DEFAULT NULL, CHANGE prenom prenom VARCHAR(255) DEFAULT NULL, CHANGE email email VARCHAR(255) DEFAULT NULL, CHANGE username username VARCHAR(255) DEFAULT NULL, CHANGE mdp mdp VARCHAR(255) DEFAULT NULL, CHANGE dateinscription dateinscription DATETIME DEFAULT NULL, CHANGE adresse adresse VARCHAR(255) DEFAULT NULL, CHANGE tel tel INT DEFAULT NULL, CHANGE roles roles JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE is_active is_active TINYINT(1) DEFAULT NULL, CHANGE salt salt VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681EEA67784A');
        $this->addSql('DROP INDEX IDX_B26681EEA67784A ON evenement');
        $this->addSql('ALTER TABLE evenement DROP agenda_id, DROP type_event, DROP categorie');
    }
}
