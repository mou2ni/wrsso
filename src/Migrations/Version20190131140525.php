<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190131140525 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE deviseintercaisses ADD journee_caisse_initiateur_id INT NOT NULL');
        $this->addSql('ALTER TABLE deviseintercaisses ADD CONSTRAINT FK_D7381A1453B5438B FOREIGN KEY (journee_caisse_initiateur_id) REFERENCES JourneeCaisses (id)');
        $this->addSql('CREATE INDEX IDX_D7381A1453B5438B ON deviseintercaisses (journee_caisse_initiateur_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE DeviseIntercaisses DROP FOREIGN KEY FK_D7381A1453B5438B');
        $this->addSql('DROP INDEX IDX_D7381A1453B5438B ON DeviseIntercaisses');
        $this->addSql('ALTER TABLE DeviseIntercaisses DROP journee_caisse_initiateur_id');
    }
}
