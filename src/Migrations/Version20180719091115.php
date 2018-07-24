<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180719091115 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE intercaisses DROP FOREIGN KEY FK_F4C41EC6DAB0C5E');
        $this->addSql('ALTER TABLE intercaisses DROP FOREIGN KEY FK_F4C41ECD63DDC48');
        $this->addSql('DROP INDEX IDX_F4C41EC6DAB0C5E ON intercaisses');
        $this->addSql('DROP INDEX IDX_F4C41ECD63DDC48 ON intercaisses');
        $this->addSql('ALTER TABLE intercaisses ADD journeeCaisseSource INT NOT NULL, ADD journeeCaisseDestination INT NOT NULL, DROP id_journee_caisse_source_id, DROP id_journee_caisse_destination_id');
        $this->addSql('ALTER TABLE intercaisses ADD CONSTRAINT FK_F4C41EC81D89780 FOREIGN KEY (journeeCaisseSource) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE intercaisses ADD CONSTRAINT FK_F4C41EC521A6FBF FOREIGN KEY (journeeCaisseDestination) REFERENCES JourneeCaisses (id)');
        $this->addSql('CREATE INDEX IDX_F4C41EC81D89780 ON intercaisses (journeeCaisseSource)');
        $this->addSql('CREATE INDEX IDX_F4C41EC521A6FBF ON intercaisses (journeeCaisseDestination)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE InterCaisses DROP FOREIGN KEY FK_F4C41EC81D89780');
        $this->addSql('ALTER TABLE InterCaisses DROP FOREIGN KEY FK_F4C41EC521A6FBF');
        $this->addSql('DROP INDEX IDX_F4C41EC81D89780 ON InterCaisses');
        $this->addSql('DROP INDEX IDX_F4C41EC521A6FBF ON InterCaisses');
        $this->addSql('ALTER TABLE InterCaisses ADD id_journee_caisse_source_id INT NOT NULL, ADD id_journee_caisse_destination_id INT NOT NULL, DROP journeeCaisseSource, DROP journeeCaisseDestination');
        $this->addSql('ALTER TABLE InterCaisses ADD CONSTRAINT FK_F4C41EC6DAB0C5E FOREIGN KEY (id_journee_caisse_source_id) REFERENCES journeecaisses (id)');
        $this->addSql('ALTER TABLE InterCaisses ADD CONSTRAINT FK_F4C41ECD63DDC48 FOREIGN KEY (id_journee_caisse_destination_id) REFERENCES journeecaisses (id)');
        $this->addSql('CREATE INDEX IDX_F4C41EC6DAB0C5E ON InterCaisses (id_journee_caisse_source_id)');
        $this->addSql('CREATE INDEX IDX_F4C41ECD63DDC48 ON InterCaisses (id_journee_caisse_destination_id)');
    }
}
