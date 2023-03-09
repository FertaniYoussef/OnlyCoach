<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230308233725 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abonnement ADD is_fav TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE categorie CHANGE type type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE coach CHANGE prenom prenom VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abonnement DROP is_fav');
        $this->addSql('ALTER TABLE categorie CHANGE type type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE coach CHANGE prenom prenom VARCHAR(255) DEFAULT NULL');
    }
}
