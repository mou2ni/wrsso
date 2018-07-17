<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180717060046 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE devisejournees DROP FOREIGN KEY FK_282BE5D2EA5532E9');
        $this->addSql('DROP INDEX IDX_282BE5D2EA5532E9 ON devisejournees');
        $this->addSql('ALTER TABLE devisejournees CHANGE idjourneecaisses idJourneeCaisse INT NOT NULL');
        $this->addSql('ALTER TABLE devisejournees ADD CONSTRAINT FK_282BE5D27DA9E4F FOREIGN KEY (idJourneeCaisse) REFERENCES JourneeCaisses (id)');
        $this->addSql('CREATE INDEX IDX_282BE5D27DA9E4F ON devisejournees (idJourneeCaisse)');
        $this->addSql('ALTER TABLE transfertinternationaux DROP FOREIGN KEY FK_CD12576A1CA528D7');
        $this->addSql('DROP INDEX IDX_CD12576A1CA528D7 ON transfertinternationaux');
        $this->addSql('ALTER TABLE transfertinternationaux CHANGE id_journee_caisse_id idJourneeCaisse INT NOT NULL');
        $this->addSql('ALTER TABLE transfertinternationaux ADD CONSTRAINT FK_CD12576A7DA9E4F FOREIGN KEY (idJourneeCaisse) REFERENCES JourneeCaisses (id)');
        $this->addSql('CREATE INDEX IDX_CD12576A7DA9E4F ON transfertinternationaux (idJourneeCaisse)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE DeviseJournees DROP FOREIGN KEY FK_282BE5D27DA9E4F');
        $this->addSql('DROP INDEX IDX_282BE5D27DA9E4F ON DeviseJournees');
        $this->addSql('ALTER TABLE DeviseJournees CHANGE idjourneecaisse IdJourneeCaisses INT NOT NULL');
        $this->addSql('ALTER TABLE DeviseJournees ADD CONSTRAINT FK_282BE5D2EA5532E9 FOREIGN KEY (IdJourneeCaisses) REFERENCES journeecaisses (id)');
        $this->addSql('CREATE INDEX IDX_282BE5D2EA5532E9 ON DeviseJournees (IdJourneeCaisses)');
        $this->addSql('ALTER TABLE TransfertInternationaux DROP FOREIGN KEY FK_CD12576A7DA9E4F');
        $this->addSql('DROP INDEX IDX_CD12576A7DA9E4F ON TransfertInternationaux');
        $this->addSql('ALTER TABLE TransfertInternationaux CHANGE idjourneecaisse id_journee_caisse_id INT NOT NULL');
        $this->addSql('ALTER TABLE TransfertInternationaux ADD CONSTRAINT FK_CD12576A1CA528D7 FOREIGN KEY (id_journee_caisse_id) REFERENCES journeecaisses (id)');
        $this->addSql('CREATE INDEX IDX_CD12576A1CA528D7 ON TransfertInternationaux (id_journee_caisse_id)');
    }
}
