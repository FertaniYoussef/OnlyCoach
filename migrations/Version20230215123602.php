<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230215123602 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ressources DROP FOREIGN KEY FK_6A2CD5C7577906E4');
        $this->addSql('ALTER TABLE ressources ADD CONSTRAINT FK_6A2CD5C7577906E4 FOREIGN KEY (sections_id) REFERENCES sections (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ressources DROP FOREIGN KEY FK_6A2CD5C7577906E4');
        $this->addSql('ALTER TABLE ressources ADD CONSTRAINT FK_6A2CD5C7577906E4 FOREIGN KEY (sections_id) REFERENCES sections (id)');
    }
}
