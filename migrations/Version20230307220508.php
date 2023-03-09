<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230307220508 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, id_feedback_id INT DEFAULT NULL, texte VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_5FB6DEC7CA5B6570 (id_feedback_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC7CA5B6570 FOREIGN KEY (id_feedback_id) REFERENCES feedback (id)');
        $this->addSql('ALTER TABLE coach ADD id_user_id INT DEFAULT NULL, ADD categorie_id INT DEFAULT NULL, ADD picture VARCHAR(255) DEFAULT NULL, ADD description VARCHAR(255) DEFAULT NULL, ADD prix DOUBLE PRECISION DEFAULT NULL, ADD rating DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE coach ADD CONSTRAINT FK_3F596DCC79F37AE5 FOREIGN KEY (id_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE coach ADD CONSTRAINT FK_3F596DCCBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3F596DCC79F37AE5 ON coach (id_user_id)');
        $this->addSql('CREATE INDEX IDX_3F596DCCBCF5E72D ON coach (categorie_id)');
        $this->addSql('ALTER TABLE cours ADD id_coach_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9C6CCBBA04 FOREIGN KEY (id_coach_id) REFERENCES coach (id)');
        $this->addSql('CREATE INDEX IDX_FDCA8C9C6CCBBA04 ON cours (id_coach_id)');
        $this->addSql('ALTER TABLE feedback CHANGE sujet sujet VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE description description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE ressources DROP FOREIGN KEY FK_6A2CD5C7577906E4');
        $this->addSql('ALTER TABLE ressources ADD CONSTRAINT FK_6A2CD5C7577906E4 FOREIGN KEY (sections_id) REFERENCES sections (id)');
        $this->addSql('ALTER TABLE sections DROP FOREIGN KEY FK_2B9643987ECF78B0');
        $this->addSql('ALTER TABLE sections ADD CONSTRAINT FK_2B9643987ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id)');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
        $this->addSql('ALTER TABLE user ADD picture VARCHAR(255) DEFAULT NULL, ADD description LONGTEXT DEFAULT NULL, ADD phone INT DEFAULT NULL, CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE prenom prenom VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC7CA5B6570');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('ALTER TABLE coach DROP FOREIGN KEY FK_3F596DCC79F37AE5');
        $this->addSql('ALTER TABLE coach DROP FOREIGN KEY FK_3F596DCCBCF5E72D');
        $this->addSql('DROP INDEX UNIQ_3F596DCC79F37AE5 ON coach');
        $this->addSql('DROP INDEX IDX_3F596DCCBCF5E72D ON coach');
        $this->addSql('ALTER TABLE coach DROP id_user_id, DROP categorie_id, DROP picture, DROP description, DROP prix, DROP rating');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9C6CCBBA04');
        $this->addSql('DROP INDEX IDX_FDCA8C9C6CCBBA04 ON cours');
        $this->addSql('ALTER TABLE cours DROP id_coach_id');
        $this->addSql('ALTER TABLE feedback CHANGE sujet sujet VARCHAR(255) DEFAULT NULL, CHANGE email email VARCHAR(255) DEFAULT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE ressources DROP FOREIGN KEY FK_6A2CD5C7577906E4');
        $this->addSql('ALTER TABLE ressources ADD CONSTRAINT FK_6A2CD5C7577906E4 FOREIGN KEY (sections_id) REFERENCES sections (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sections DROP FOREIGN KEY FK_2B9643987ECF78B0');
        $this->addSql('ALTER TABLE sections ADD CONSTRAINT FK_2B9643987ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `user` DROP picture, DROP description, DROP phone, CHANGE nom nom VARCHAR(255) DEFAULT NULL, CHANGE prenom prenom VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON `user` (email)');
    }
}
