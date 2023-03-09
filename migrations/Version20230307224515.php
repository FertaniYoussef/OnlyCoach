<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230307224515 extends AbstractMigration
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
        $this->addSql('ALTER TABLE feedback CHANGE sujet sujet VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE description description VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC7CA5B6570');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('ALTER TABLE feedback CHANGE sujet sujet VARCHAR(255) DEFAULT NULL, CHANGE email email VARCHAR(255) DEFAULT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL');
    }
}
