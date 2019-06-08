<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190605161744 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ligne_frais_hors_forfait CHANGE fiche_frais_id fiche_frais_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ligne_frais_forfait ADD date_creation DATE NOT NULL');
        $this->addSql('ALTER TABLE fiche_frais CHANGE etat_id etat_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fiche_frais CHANGE etat_id etat_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ligne_frais_forfait DROP date_creation');
        $this->addSql('ALTER TABLE ligne_frais_hors_forfait CHANGE fiche_frais_id fiche_frais_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
    }
}
