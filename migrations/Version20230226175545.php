<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230226175545 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE feedback CHANGE email email VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY reponse_ibfk_1');
        $this->addSql('DROP INDEX id_feedback ON reponse');
        $this->addSql('ALTER TABLE reponse ADD id_feedback_id INT DEFAULT NULL, DROP id_feedback, CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC7CA5B6570 FOREIGN KEY (id_feedback_id) REFERENCES feedback (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5FB6DEC7CA5B6570 ON reponse (id_feedback_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE feedback CHANGE email email VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC7CA5B6570');
        $this->addSql('DROP INDEX UNIQ_5FB6DEC7CA5B6570 ON reponse');
        $this->addSql('ALTER TABLE reponse ADD id_feedback INT NOT NULL, DROP id_feedback_id, CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT reponse_ibfk_1 FOREIGN KEY (id_feedback) REFERENCES feedback (id)');
        $this->addSql('CREATE INDEX id_feedback ON reponse (id_feedback)');
    }
}
