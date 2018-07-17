<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180713093342 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE devisejournees DROP FOREIGN KEY FK_282BE5D21CA528D7');
        $this->addSql('DROP INDEX IDX_282BE5D21CA528D7 ON devisejournees');
        $this->addSql('ALTER TABLE devisejournees CHANGE id_journee_caisse_id IdJourneeCaisses INT NOT NULL');
        $this->addSql('ALTER TABLE devisejournees ADD CONSTRAINT FK_282BE5D2EA5532E9 FOREIGN KEY (IdJourneeCaisses) REFERENCES JourneeCaisses (id)');
        $this->addSql('CREATE INDEX IDX_282BE5D2EA5532E9 ON devisejournees (IdJourneeCaisses)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE DeviseJournees DROP FOREIGN KEY FK_282BE5D2EA5532E9');
        $this->addSql('DROP INDEX IDX_282BE5D2EA5532E9 ON DeviseJournees');
        $this->addSql('ALTER TABLE DeviseJournees CHANGE idjourneecaisses id_journee_caisse_id INT NOT NULL');
        $this->addSql('ALTER TABLE DeviseJournees ADD CONSTRAINT FK_282BE5D21CA528D7 FOREIGN KEY (id_journee_caisse_id) REFERENCES journeecaisses (id)');
        $this->addSql('CREATE INDEX IDX_282BE5D21CA528D7 ON DeviseJournees (id_journee_caisse_id)');
    }
}
