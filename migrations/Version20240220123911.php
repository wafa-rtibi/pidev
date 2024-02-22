<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240220123911 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE administrateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, mdp VARCHAR(255) NOT NULL, date_inscription DATETIME NOT NULL, photo_profil VARCHAR(255) NOT NULL, rib INT NOT NULL, adresse VARCHAR(255) NOT NULL, tel INT NOT NULL, note INT NOT NULL, statut VARCHAR(255) NOT NULL, role JSON NOT NULL COMMENT \'(DC2Type:json)\', is_active TINYINT(1) NOT NULL, salt VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE agenda (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blog (id INT AUTO_INCREMENT NOT NULL, auteur_id INT DEFAULT NULL, contenu VARCHAR(255) NOT NULL, titre VARCHAR(255) NOT NULL, date_publication DATETIME NOT NULL, langue VARCHAR(255) NOT NULL, statut TINYINT(1) NOT NULL, INDEX IDX_C015514360BB6FE6 (auteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blog_utilisateur (blog_id INT NOT NULL, utilisateur_id INT NOT NULL, INDEX IDX_C42CFF4DDAE07E97 (blog_id), INDEX IDX_C42CFF4DFB88E14F (utilisateur_id), PRIMARY KEY(blog_id, utilisateur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, commenteur_id INT DEFAULT NULL, blog_id INT DEFAULT NULL, contenu VARCHAR(255) NOT NULL, statut TINYINT(1) NOT NULL, INDEX IDX_67F068BCA35D50AD (commenteur_id), INDEX IDX_67F068BCDAE07E97 (blog_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demande_offre (id INT AUTO_INCREMENT NOT NULL, offre_id INT DEFAULT NULL, statut TINYINT(1) NOT NULL, date_creation DATETIME NOT NULL, INDEX IDX_595805464CC8505A (offre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dons (id INT AUTO_INCREMENT NOT NULL, donateur_id INT NOT NULL, organisation_id INT NOT NULL, date DATETIME NOT NULL, compagne_collect VARCHAR(255) NOT NULL, montant DOUBLE PRECISION NOT NULL, INDEX IDX_E4F955FAA9C80E3 (donateur_id), INDEX IDX_E4F955FA9E6B1585 (organisation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, agenda_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, lieu VARCHAR(255) NOT NULL, lien VARCHAR(255) NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, type VARCHAR(255) NOT NULL, type_event VARCHAR(255) NOT NULL, categorie VARCHAR(255) NOT NULL, INDEX IDX_B26681EEA67784A (agenda_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement_utilisateur (evenement_id INT NOT NULL, utilisateur_id INT NOT NULL, INDEX IDX_8C897598FD02F13 (evenement_id), INDEX IDX_8C897598FB88E14F (utilisateur_id), PRIMARY KEY(evenement_id, utilisateur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre (id INT AUTO_INCREMENT NOT NULL, offreur_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, categorie VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, etat VARCHAR(255) NOT NULL, date_publication DATETIME NOT NULL, photos JSON NOT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_AF86866FB05122F8 (offreur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisation (id INT AUTO_INCREMENT NOT NULL, nom_organisation VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, rib VARCHAR(24) NOT NULL, adresse VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, reclamateur_id INT DEFAULT NULL, date_reclamation DATETIME NOT NULL, description_reclamation VARCHAR(2000) NOT NULL, statut_reclamation VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_CE6064046A3605A6 (reclamateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, reclam_reponse_id INT DEFAULT NULL, admin_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, date_reponse DATE NOT NULL, UNIQUE INDEX UNIQ_5FB6DEC76D585A2D (reclam_reponse_id), INDEX IDX_5FB6DEC7642B8210 (admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE roles (id INT AUTO_INCREMENT NOT NULL, role VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, evenement_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, mdp VARCHAR(255) NOT NULL, date_inscription DATETIME NOT NULL, photo_profil VARCHAR(255) NOT NULL, rib INT NOT NULL, adresse VARCHAR(255) NOT NULL, tel INT NOT NULL, note INT NOT NULL, statut VARCHAR(255) NOT NULL, role JSON NOT NULL COMMENT \'(DC2Type:json)\', is_active TINYINT(1) NOT NULL, salt VARCHAR(255) NOT NULL, INDEX IDX_1D1C63B3FD02F13 (evenement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C015514360BB6FE6 FOREIGN KEY (auteur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE blog_utilisateur ADD CONSTRAINT FK_C42CFF4DDAE07E97 FOREIGN KEY (blog_id) REFERENCES blog (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE blog_utilisateur ADD CONSTRAINT FK_C42CFF4DFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCA35D50AD FOREIGN KEY (commenteur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCDAE07E97 FOREIGN KEY (blog_id) REFERENCES blog (id)');
        $this->addSql('ALTER TABLE demande_offre ADD CONSTRAINT FK_595805464CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id)');
        $this->addSql('ALTER TABLE dons ADD CONSTRAINT FK_E4F955FAA9C80E3 FOREIGN KEY (donateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE dons ADD CONSTRAINT FK_E4F955FA9E6B1585 FOREIGN KEY (organisation_id) REFERENCES organisation (id)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681EEA67784A FOREIGN KEY (agenda_id) REFERENCES agenda (id)');
        $this->addSql('ALTER TABLE evenement_utilisateur ADD CONSTRAINT FK_8C897598FD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evenement_utilisateur ADD CONSTRAINT FK_8C897598FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866FB05122F8 FOREIGN KEY (offreur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE6064046A3605A6 FOREIGN KEY (reclamateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC76D585A2D FOREIGN KEY (reclam_reponse_id) REFERENCES reclamation (id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC7642B8210 FOREIGN KEY (admin_id) REFERENCES administrateur (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3FD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C015514360BB6FE6');
        $this->addSql('ALTER TABLE blog_utilisateur DROP FOREIGN KEY FK_C42CFF4DDAE07E97');
        $this->addSql('ALTER TABLE blog_utilisateur DROP FOREIGN KEY FK_C42CFF4DFB88E14F');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCA35D50AD');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCDAE07E97');
        $this->addSql('ALTER TABLE demande_offre DROP FOREIGN KEY FK_595805464CC8505A');
        $this->addSql('ALTER TABLE dons DROP FOREIGN KEY FK_E4F955FAA9C80E3');
        $this->addSql('ALTER TABLE dons DROP FOREIGN KEY FK_E4F955FA9E6B1585');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681EEA67784A');
        $this->addSql('ALTER TABLE evenement_utilisateur DROP FOREIGN KEY FK_8C897598FD02F13');
        $this->addSql('ALTER TABLE evenement_utilisateur DROP FOREIGN KEY FK_8C897598FB88E14F');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866FB05122F8');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE6064046A3605A6');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC76D585A2D');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC7642B8210');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3FD02F13');
        $this->addSql('DROP TABLE administrateur');
        $this->addSql('DROP TABLE agenda');
        $this->addSql('DROP TABLE blog');
        $this->addSql('DROP TABLE blog_utilisateur');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE demande_offre');
        $this->addSql('DROP TABLE dons');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE evenement_utilisateur');
        $this->addSql('DROP TABLE offre');
        $this->addSql('DROP TABLE organisation');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
