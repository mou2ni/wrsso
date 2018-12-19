<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181219153653 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE transfertinternationaux DROP FOREIGN KEY FK_CD12576A7DA9E4F');
        $this->addSql('DROP INDEX IDX_CD12576A7DA9E4F ON transfertinternationaux');
        $this->addSql('ALTER TABLE transfertinternationaux ADD journeeCaisseEmi INT DEFAULT NULL, ADD journeeCaisseRecu INT DEFAULT NULL, CHANGE idjourneecaisse journeeCaisse INT NOT NULL');
        $this->addSql('ALTER TABLE transfertinternationaux ADD CONSTRAINT FK_CD12576ACD3EC261 FOREIGN KEY (journeeCaisse) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE transfertinternationaux ADD CONSTRAINT FK_CD12576A1C955392 FOREIGN KEY (journeeCaisseEmi) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE transfertinternationaux ADD CONSTRAINT FK_CD12576AD542DB11 FOREIGN KEY (journeeCaisseRecu) REFERENCES JourneeCaisses (id)');
        $this->addSql('CREATE INDEX IDX_CD12576ACD3EC261 ON transfertinternationaux (journeeCaisse)');
        $this->addSql('CREATE INDEX IDX_CD12576A1C955392 ON transfertinternationaux (journeeCaisseEmi)');
        $this->addSql('CREATE INDEX IDX_CD12576AD542DB11 ON transfertinternationaux (journeeCaisseRecu)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE TransfertInternationaux DROP FOREIGN KEY FK_CD12576ACD3EC261');
        $this->addSql('ALTER TABLE TransfertInternationaux DROP FOREIGN KEY FK_CD12576A1C955392');
        $this->addSql('ALTER TABLE TransfertInternationaux DROP FOREIGN KEY FK_CD12576AD542DB11');
        $this->addSql('DROP INDEX IDX_CD12576ACD3EC261 ON TransfertInternationaux');
        $this->addSql('DROP INDEX IDX_CD12576A1C955392 ON TransfertInternationaux');
        $this->addSql('DROP INDEX IDX_CD12576AD542DB11 ON TransfertInternationaux');
        $this->addSql('ALTER TABLE TransfertInternationaux DROP journeeCaisseEmi, DROP journeeCaisseRecu, CHANGE journeecaisse idJourneeCaisse INT NOT NULL');
        $this->addSql('ALTER TABLE TransfertInternationaux ADD CONSTRAINT FK_CD12576A7DA9E4F FOREIGN KEY (idJourneeCaisse) REFERENCES journeecaisses (id)');
        $this->addSql('CREATE INDEX IDX_CD12576A7DA9E4F ON TransfertInternationaux (idJourneeCaisse)');
    }
}
