<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230215123538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sections DROP FOREIGN KEY FK_2B9643987ECF78B0');
        $this->addSql('ALTER TABLE sections ADD CONSTRAINT FK_2B9643987ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sections DROP FOREIGN KEY FK_2B9643987ECF78B0');
        $this->addSql('ALTER TABLE sections ADD CONSTRAINT FK_2B9643987ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id)');
    }
}
