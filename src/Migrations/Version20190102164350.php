<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190102164350 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE caisses ADD compte_intercaisse_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE caisses ADD CONSTRAINT FK_41BE1F46F2823676 FOREIGN KEY (compte_intercaisse_id) REFERENCES Comptes (id)');
        $this->addSql('CREATE INDEX IDX_41BE1F46F2823676 ON caisses (compte_intercaisse_id)');
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

        $this->addSql('ALTER TABLE Caisses DROP FOREIGN KEY FK_41BE1F46F2823676');
        $this->addSql('DROP INDEX IDX_41BE1F46F2823676 ON Caisses');
        $this->addSql('ALTER TABLE Caisses DROP compte_intercaisse_id');
        $this->addSql('ALTER TABLE DetteCreditDivers DROP FOREIGN KEY FK_1C5147F2933A183');
        $this->addSql('ALTER TABLE DetteCreditDivers DROP FOREIGN KEY FK_1C5147F2666C2D03');
        $this->addSql('ALTER TABLE DetteCreditDivers DROP FOREIGN KEY FK_1C5147F2954C14F1');
        $this->addSql('DROP INDEX IDX_1C5147F2933A183 ON DetteCreditDivers');
        $this->addSql('DROP INDEX IDX_1C5147F2666C2D03 ON DetteCreditDivers');
        $this->addSql('DROP INDEX IDX_1C5147F2954C14F1 ON DetteCreditDivers');
    }
}
