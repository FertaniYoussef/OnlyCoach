<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230309031101 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adherents (id INT AUTO_INCREMENT NOT NULL, cours_id INT DEFAULT NULL, user_id INT DEFAULT NULL, date DATE DEFAULT NULL, INDEX IDX_562C7DA3A76ED395 (user_id), INDEX IDX_562C7DA37ECF78B0 (cours_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE coach (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, categorie_id INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, prix DOUBLE PRECISION DEFAULT NULL, rating DOUBLE PRECISION DEFAULT NULL, INDEX IDX_3F596DCCBCF5E72D (categorie_id), UNIQUE INDEX UNIQ_3F596DCC79F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, id_coures INT DEFAULT NULL, auteur VARCHAR(255) NOT NULL, date DATETIME NOT NULL, contenu VARCHAR(255) NOT NULL, INDEX fk77 (id_coures), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cours (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, date_creation DATE DEFAULT NULL, nb_vues INT DEFAULT NULL, cours_photo VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feedback (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, sujet VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, date_feedback DATE DEFAULT NULL, status INT DEFAULT NULL, INDEX IDX_D2294458A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E016BA31DB (delivered_at), INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre (id INT AUTO_INCREMENT NOT NULL, id_coach_id INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, prix DOUBLE PRECISION DEFAULT NULL, discount DOUBLE PRECISION DEFAULT NULL, prix_fin DOUBLE PRECISION DEFAULT NULL, date_deb DATE DEFAULT NULL, date_fin DATE DEFAULT NULL, UNIQUE INDEX UNIQ_AF86866F6CCBBA04 (id_coach_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, cours_id INT DEFAULT NULL, note INT DEFAULT NULL, INDEX IDX_D8892622A76ED395 (user_id), INDEX IDX_D88926227ECF78B0 (cours_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ressources (id INT AUTO_INCREMENT NOT NULL, sections_id INT DEFAULT NULL, lien VARCHAR(255) DEFAULT NULL, index_ressources INT DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_6A2CD5C7577906E4 (sections_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sections (id INT AUTO_INCREMENT NOT NULL, cours_id INT DEFAULT NULL, index_section INT DEFAULT NULL, titre VARCHAR(255) DEFAULT NULL, nbresources INT DEFAULT NULL, INDEX IDX_2B9643987ECF78B0 (cours_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adherents ADD CONSTRAINT FK_562C7DA37ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id)');
        $this->addSql('ALTER TABLE adherents ADD CONSTRAINT FK_562C7DA3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE coach ADD CONSTRAINT FK_3F596DCC79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE coach ADD CONSTRAINT FK_3F596DCCBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCD8E5DAD2 FOREIGN KEY (id_coures) REFERENCES cours (id)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D2294458A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866F6CCBBA04 FOREIGN KEY (id_coach_id) REFERENCES coach (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D8892622A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D88926227ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id)');
        $this->addSql('ALTER TABLE ressources ADD CONSTRAINT FK_6A2CD5C7577906E4 FOREIGN KEY (sections_id) REFERENCES sections (id)');
        $this->addSql('ALTER TABLE sections ADD CONSTRAINT FK_2B9643987ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adherents DROP FOREIGN KEY FK_562C7DA37ECF78B0');
        $this->addSql('ALTER TABLE adherents DROP FOREIGN KEY FK_562C7DA3A76ED395');
        $this->addSql('ALTER TABLE coach DROP FOREIGN KEY FK_3F596DCC79F37AE5');
        $this->addSql('ALTER TABLE coach DROP FOREIGN KEY FK_3F596DCCBCF5E72D');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCD8E5DAD2');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D2294458A76ED395');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866F6CCBBA04');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D8892622A76ED395');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D88926227ECF78B0');
        $this->addSql('ALTER TABLE ressources DROP FOREIGN KEY FK_6A2CD5C7577906E4');
        $this->addSql('ALTER TABLE sections DROP FOREIGN KEY FK_2B9643987ECF78B0');
        $this->addSql('DROP TABLE adherents');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE coach');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE cours');
        $this->addSql('DROP TABLE feedback');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('DROP TABLE offre');
        $this->addSql('DROP TABLE rating');
        $this->addSql('DROP TABLE ressources');
        $this->addSql('DROP TABLE sections');
        $this->addSql('DROP TABLE user');
    }
}
