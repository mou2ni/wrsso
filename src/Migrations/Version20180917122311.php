<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180917122311 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE journeecaisses DROP INDEX UNIQ_EC12D8DF2FFAA428, ADD INDEX IDX_EC12D8DF2FFAA428 (system_elect_invent_ouv_id)');
        $this->addSql('ALTER TABLE systemelectinventaires DROP FOREIGN KEY FK_6A61D25AED9240C0');
        $this->addSql('DROP INDEX UNIQ_6A61D25AED9240C0 ON systemelectinventaires');
        $this->addSql('ALTER TABLE systemelectinventaires DROP journee_caisse_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE JourneeCaisses DROP INDEX IDX_EC12D8DF2FFAA428, ADD UNIQUE INDEX UNIQ_EC12D8DF2FFAA428 (system_elect_invent_ouv_id)');
        $this->addSql('ALTER TABLE SystemElectInventaires ADD journee_caisse_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE SystemElectInventaires ADD CONSTRAINT FK_6A61D25AED9240C0 FOREIGN KEY (journee_caisse_id) REFERENCES journeecaisses (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6A61D25AED9240C0 ON SystemElectInventaires (journee_caisse_id)');
    }
}
