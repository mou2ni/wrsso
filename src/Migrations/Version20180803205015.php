<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180803205015 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE deviseintercaisses DROP FOREIGN KEY FK_D7381A146DAB0C5E');
        $this->addSql('ALTER TABLE deviseintercaisses DROP FOREIGN KEY FK_D7381A14A1D63ED8');
        $this->addSql('DROP INDEX IDX_D7381A146DAB0C5E ON deviseintercaisses');
        $this->addSql('DROP INDEX IDX_D7381A14A1D63ED8 ON deviseintercaisses');
        $this->addSql('ALTER TABLE deviseintercaisses ADD journee_caisse_source_id INT NOT NULL, ADD journee_caisse_destination_id INT NOT NULL, DROP id_journee_caisse_source_id, DROP id_journee_caisse_partenaire_id, CHANGE date_intercaisse date_intercaisse DATETIME NOT NULL');
        $this->addSql('ALTER TABLE deviseintercaisses ADD CONSTRAINT FK_D7381A146AE367D FOREIGN KEY (journee_caisse_source_id) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE deviseintercaisses ADD CONSTRAINT FK_D7381A144F08E1DE FOREIGN KEY (journee_caisse_destination_id) REFERENCES JourneeCaisses (id)');
        $this->addSql('CREATE INDEX IDX_D7381A146AE367D ON deviseintercaisses (journee_caisse_source_id)');
        $this->addSql('CREATE INDEX IDX_D7381A144F08E1DE ON deviseintercaisses (journee_caisse_destination_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE DeviseIntercaisses DROP FOREIGN KEY FK_D7381A146AE367D');
        $this->addSql('ALTER TABLE DeviseIntercaisses DROP FOREIGN KEY FK_D7381A144F08E1DE');
        $this->addSql('DROP INDEX IDX_D7381A146AE367D ON DeviseIntercaisses');
        $this->addSql('DROP INDEX IDX_D7381A144F08E1DE ON DeviseIntercaisses');
        $this->addSql('ALTER TABLE DeviseIntercaisses ADD id_journee_caisse_source_id INT NOT NULL, ADD id_journee_caisse_partenaire_id INT NOT NULL, DROP journee_caisse_source_id, DROP journee_caisse_destination_id, CHANGE date_intercaisse date_intercaisse DATE NOT NULL');
        $this->addSql('ALTER TABLE DeviseIntercaisses ADD CONSTRAINT FK_D7381A146DAB0C5E FOREIGN KEY (id_journee_caisse_source_id) REFERENCES journeecaisses (id)');
        $this->addSql('ALTER TABLE DeviseIntercaisses ADD CONSTRAINT FK_D7381A14A1D63ED8 FOREIGN KEY (id_journee_caisse_partenaire_id) REFERENCES journeecaisses (id)');
        $this->addSql('CREATE INDEX IDX_D7381A146DAB0C5E ON DeviseIntercaisses (id_journee_caisse_source_id)');
        $this->addSql('CREATE INDEX IDX_D7381A14A1D63ED8 ON DeviseIntercaisses (id_journee_caisse_partenaire_id)');
    }
}
