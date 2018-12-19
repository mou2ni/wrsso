<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181218164214 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE utilisateurs ADD CONSTRAINT FK_514AEAA6B451A8DB FOREIGN KEY (journee_caisse_active_id) REFERENCES JourneeCaisses (id)');
        $this->addSql('CREATE INDEX IDX_514AEAA6B451A8DB ON utilisateurs (journee_caisse_active_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Utilisateurs DROP FOREIGN KEY FK_514AEAA6B451A8DB');
        $this->addSql('DROP INDEX IDX_514AEAA6B451A8DB ON Utilisateurs');
    }
}
