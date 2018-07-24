<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180724131933 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE devise_recus (id INT AUTO_INCREMENT NOT NULL, date_recu DATE NOT NULL, nom_prenom VARCHAR(255) DEFAULT NULL, type_piece VARCHAR(50) DEFAULT NULL, numero_piece VARCHAR(50) DEFAULT NULL, expire_le DATE DEFAULT NULL, motif VARCHAR(255) DEFAULT NULL, devise_recus VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE devisemouvements ADD taux DOUBLE PRECISION NOT NULL, CHANGE nombre nombre INT NOT NULL, CHANGE m_cvd m_cvd INT NOT NULL');
        $this->addSql('ALTER TABLE intercaisses DROP FOREIGN KEY FK_F4C41EC6DAB0C5E');
        $this->addSql('ALTER TABLE intercaisses DROP FOREIGN KEY FK_F4C41ECD63DDC48');
        $this->addSql('DROP INDEX IDX_F4C41EC6DAB0C5E ON intercaisses');
        $this->addSql('DROP INDEX IDX_F4C41ECD63DDC48 ON intercaisses');
        $this->addSql('ALTER TABLE intercaisses ADD journeeCaisseSortant INT NOT NULL, ADD journeeCaisseEntrant INT NOT NULL, DROP id_journee_caisse_source_id, DROP id_journee_caisse_destination_id');
        $this->addSql('ALTER TABLE intercaisses ADD CONSTRAINT FK_F4C41EC1F688F76 FOREIGN KEY (journeeCaisseSortant) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE intercaisses ADD CONSTRAINT FK_F4C41EC7A115F5B FOREIGN KEY (journeeCaisseEntrant) REFERENCES JourneeCaisses (id)');
        $this->addSql('CREATE INDEX IDX_F4C41EC1F688F76 ON intercaisses (journeeCaisseSortant)');
        $this->addSql('CREATE INDEX IDX_F4C41EC7A115F5B ON intercaisses (journeeCaisseEntrant)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE devise_recus');
        $this->addSql('ALTER TABLE DeviseMouvements DROP taux, CHANGE nombre nombre DOUBLE PRECISION NOT NULL, CHANGE m_cvd m_cvd DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE InterCaisses DROP FOREIGN KEY FK_F4C41EC1F688F76');
        $this->addSql('ALTER TABLE InterCaisses DROP FOREIGN KEY FK_F4C41EC7A115F5B');
        $this->addSql('DROP INDEX IDX_F4C41EC1F688F76 ON InterCaisses');
        $this->addSql('DROP INDEX IDX_F4C41EC7A115F5B ON InterCaisses');
        $this->addSql('ALTER TABLE InterCaisses ADD id_journee_caisse_source_id INT NOT NULL, ADD id_journee_caisse_destination_id INT NOT NULL, DROP journeeCaisseSortant, DROP journeeCaisseEntrant');
        $this->addSql('ALTER TABLE InterCaisses ADD CONSTRAINT FK_F4C41EC6DAB0C5E FOREIGN KEY (id_journee_caisse_source_id) REFERENCES journeecaisses (id)');
        $this->addSql('ALTER TABLE InterCaisses ADD CONSTRAINT FK_F4C41ECD63DDC48 FOREIGN KEY (id_journee_caisse_destination_id) REFERENCES journeecaisses (id)');
        $this->addSql('CREATE INDEX IDX_F4C41EC6DAB0C5E ON InterCaisses (id_journee_caisse_source_id)');
        $this->addSql('CREATE INDEX IDX_F4C41ECD63DDC48 ON InterCaisses (id_journee_caisse_destination_id)');
    }
}
