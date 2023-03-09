<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230308133943 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abonnement ADD is_fav TINYINT(1) NOT NULL, DROP isFav');
        $this->addSql('ALTER TABLE coach CHANGE prenom prenom VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abonnement ADD isFav TINYINT(1) DEFAULT 0 NOT NULL, DROP is_fav');
        $this->addSql('ALTER TABLE coach CHANGE prenom prenom VARCHAR(255) DEFAULT NULL');
    }
}
