<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181227122049 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE dettecreditdivers DROP FOREIGN KEY FK_1C5147F227B4FEBF');
        $this->addSql('ALTER TABLE dettecreditdivers DROP FOREIGN KEY FK_1C5147F2C22CD53D');
        $this->addSql('DROP INDEX IDX_1C5147F2C22CD53D ON dettecreditdivers');
        $this->addSql('DROP INDEX IDX_1C5147F227B4FEBF ON dettecreditdivers');
        $this->addSql('ALTER TABLE dettecreditdivers ADD journee_caisse_creation_id INT NOT NULL, ADD journee_caisse_remb_id INT DEFAULT NULL, ADD journeeCaisseActive INT NOT NULL, DROP caisse_id, DROP journeeCaissesCreation');
        $this->addSql('ALTER TABLE dettecreditdivers ADD CONSTRAINT FK_1C5147F2933A183 FOREIGN KEY (journee_caisse_creation_id) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE dettecreditdivers ADD CONSTRAINT FK_1C5147F2666C2D03 FOREIGN KEY (journee_caisse_remb_id) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE dettecreditdivers ADD CONSTRAINT FK_1C5147F2954C14F1 FOREIGN KEY (journeeCaisseActive) REFERENCES JourneeCaisses (id)');
        $this->addSql('CREATE INDEX IDX_1C5147F2933A183 ON dettecreditdivers (journee_caisse_creation_id)');
        $this->addSql('CREATE INDEX IDX_1C5147F2666C2D03 ON dettecreditdivers (journee_caisse_remb_id)');
        $this->addSql('CREATE INDEX IDX_1C5147F2954C14F1 ON dettecreditdivers (journeeCaisseActive)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE DetteCreditDivers DROP FOREIGN KEY FK_1C5147F2933A183');
        $this->addSql('ALTER TABLE DetteCreditDivers DROP FOREIGN KEY FK_1C5147F2666C2D03');
        $this->addSql('ALTER TABLE DetteCreditDivers DROP FOREIGN KEY FK_1C5147F2954C14F1');
        $this->addSql('DROP INDEX IDX_1C5147F2933A183 ON DetteCreditDivers');
        $this->addSql('DROP INDEX IDX_1C5147F2666C2D03 ON DetteCreditDivers');
        $this->addSql('DROP INDEX IDX_1C5147F2954C14F1 ON DetteCreditDivers');
        $this->addSql('ALTER TABLE DetteCreditDivers ADD caisse_id INT NOT NULL, ADD journeeCaissesCreation INT NOT NULL, DROP journee_caisse_creation_id, DROP journee_caisse_remb_id, DROP journeeCaisseActive');
        $this->addSql('ALTER TABLE DetteCreditDivers ADD CONSTRAINT FK_1C5147F227B4FEBF FOREIGN KEY (caisse_id) REFERENCES caisses (id)');
        $this->addSql('ALTER TABLE DetteCreditDivers ADD CONSTRAINT FK_1C5147F2C22CD53D FOREIGN KEY (journeeCaissesCreation) REFERENCES journeecaisses (id)');
        $this->addSql('CREATE INDEX IDX_1C5147F2C22CD53D ON DetteCreditDivers (journeeCaissesCreation)');
        $this->addSql('CREATE INDEX IDX_1C5147F227B4FEBF ON DetteCreditDivers (caisse_id)');
    }
}
