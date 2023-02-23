<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230223090440 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coach DROP INDEX id_user, ADD UNIQUE INDEX UNIQ_3F596DCC79F37AE5 (id_user_id)');
        $this->addSql('ALTER TABLE coach DROP FOREIGN KEY coach_ibfk_4');
        $this->addSql('ALTER TABLE coach DROP FOREIGN KEY coach_ibfk_3');
        $this->addSql('DROP INDEX id_abonnement ON coach');
        $this->addSql('DROP INDEX offre ON coach');
        $this->addSql('ALTER TABLE coach DROP FOREIGN KEY coach_ibfk_2');
        $this->addSql('ALTER TABLE coach DROP offre, DROP id_abonnement');
        $this->addSql('DROP INDEX categorie ON coach');
        $this->addSql('CREATE INDEX IDX_3F596DCCBCF5E72D ON coach (categorie_id)');
        $this->addSql('ALTER TABLE coach ADD CONSTRAINT coach_ibfk_2 FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE ressources DROP FOREIGN KEY FK_6A2CD5C7577906E4');
        $this->addSql('ALTER TABLE ressources ADD CONSTRAINT FK_6A2CD5C7577906E4 FOREIGN KEY (sections_id) REFERENCES sections (id)');
        $this->addSql('ALTER TABLE sections DROP FOREIGN KEY FK_2B9643987ECF78B0');
        $this->addSql('ALTER TABLE sections ADD CONSTRAINT FK_2B9643987ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coach DROP INDEX UNIQ_3F596DCC79F37AE5, ADD INDEX id_user (id_user_id)');
        $this->addSql('ALTER TABLE coach DROP FOREIGN KEY FK_3F596DCCBCF5E72D');
        $this->addSql('ALTER TABLE coach ADD offre INT DEFAULT NULL, ADD id_abonnement INT NOT NULL');
        $this->addSql('ALTER TABLE coach ADD CONSTRAINT coach_ibfk_4 FOREIGN KEY (offre) REFERENCES offre (id)');
        $this->addSql('ALTER TABLE coach ADD CONSTRAINT coach_ibfk_3 FOREIGN KEY (id_abonnement) REFERENCES abonnement (id)');
        $this->addSql('CREATE INDEX id_abonnement ON coach (id_abonnement)');
        $this->addSql('CREATE INDEX offre ON coach (offre)');
        $this->addSql('DROP INDEX idx_3f596dccbcf5e72d ON coach');
        $this->addSql('CREATE INDEX categorie ON coach (categorie_id)');
        $this->addSql('ALTER TABLE coach ADD CONSTRAINT FK_3F596DCCBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE ressources DROP FOREIGN KEY FK_6A2CD5C7577906E4');
        $this->addSql('ALTER TABLE ressources ADD CONSTRAINT FK_6A2CD5C7577906E4 FOREIGN KEY (sections_id) REFERENCES sections (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sections DROP FOREIGN KEY FK_2B9643987ECF78B0');
        $this->addSql('ALTER TABLE sections ADD CONSTRAINT FK_2B9643987ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id) ON DELETE CASCADE');
    }
}
