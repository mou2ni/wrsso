<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180704101812 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE journeecaisses CHANGE id_system_elect_invent_ouv_id id_system_elect_invent_ouv_id INT DEFAULT NULL, CHANGE id_caisse_id id_caisse_id INT DEFAULT NULL, CHANGE id_utilisateur_id id_utilisateur_id INT DEFAULT NULL, CHANGE id_billet_ouv_id id_billet_ouv_id INT DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE JourneeCaisses CHANGE id_caisse_id id_caisse_id INT NOT NULL, CHANGE id_utilisateur_id id_utilisateur_id INT NOT NULL, CHANGE id_billet_ouv_id id_billet_ouv_id INT NOT NULL, CHANGE id_system_elect_invent_ouv_id id_system_elect_invent_ouv_id INT NOT NULL');
    }
}
