<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230307230911 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cours ADD id_coach_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9C6CCBBA04 FOREIGN KEY (id_coach_id) REFERENCES coach (id)');
        $this->addSql('CREATE INDEX IDX_FDCA8C9C6CCBBA04 ON cours (id_coach_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9C6CCBBA04');
        $this->addSql('DROP INDEX IDX_FDCA8C9C6CCBBA04 ON cours');
        $this->addSql('ALTER TABLE cours DROP id_coach_id');
    }
}
