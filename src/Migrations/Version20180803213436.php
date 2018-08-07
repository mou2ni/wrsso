<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180803213436 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE devisemouvements ADD journee_caisse_id INT NOT NULL');
        $this->addSql('ALTER TABLE devisemouvements ADD CONSTRAINT FK_F832A381ED9240C0 FOREIGN KEY (journee_caisse_id) REFERENCES JourneeCaisses (id)');
        $this->addSql('CREATE INDEX IDX_F832A381ED9240C0 ON devisemouvements (journee_caisse_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE DeviseMouvements DROP FOREIGN KEY FK_F832A381ED9240C0');
        $this->addSql('DROP INDEX IDX_F832A381ED9240C0 ON DeviseMouvements');
        $this->addSql('ALTER TABLE DeviseMouvements DROP journee_caisse_id');
    }
}
