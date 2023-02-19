<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230216133449 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coach ADD id_user_id INT DEFAULT NULL, ADD categorie_id INT DEFAULT NULL, ADD picture VARCHAR(255) DEFAULT NULL, ADD description VARCHAR(255) DEFAULT NULL, ADD prix DOUBLE PRECISION DEFAULT NULL, ADD rating DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE coach ADD CONSTRAINT FK_3F596DCC79F37AE5 FOREIGN KEY (id_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE coach ADD CONSTRAINT FK_3F596DCCBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3F596DCC79F37AE5 ON coach (id_user_id)');
        $this->addSql('CREATE INDEX IDX_3F596DCCBCF5E72D ON coach (categorie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coach DROP FOREIGN KEY FK_3F596DCC79F37AE5');
        $this->addSql('ALTER TABLE coach DROP FOREIGN KEY FK_3F596DCCBCF5E72D');
        $this->addSql('DROP INDEX UNIQ_3F596DCC79F37AE5 ON coach');
        $this->addSql('DROP INDEX IDX_3F596DCCBCF5E72D ON coach');
        $this->addSql('ALTER TABLE coach DROP id_user_id, DROP categorie_id, DROP picture, DROP description, DROP prix, DROP rating');
    }
}
