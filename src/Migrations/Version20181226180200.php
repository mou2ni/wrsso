<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181226180200 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE journeecaisses DROP INDEX UNIQ_EC12D8DF2FFAA428, ADD INDEX IDX_EC12D8DF2FFAA428 (system_elect_invent_ouv_id)');
        $this->addSql('ALTER TABLE utilisateurs DROP FOREIGN KEY FK_514AEAA63B9A683C');
        $this->addSql('ALTER TABLE utilisateurs DROP FOREIGN KEY FK_514AEAA6732C0433');
        $this->addSql('DROP INDEX UNIQ_514AEAA63B9A683C ON utilisateurs');
        $this->addSql('DROP INDEX UNIQ_514AEAA6732C0433 ON utilisateurs');
        $this->addSql('ALTER TABLE utilisateurs DROP dette_credit_crees_id, DROP dette_credit_rembourses_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE JourneeCaisses DROP INDEX IDX_EC12D8DF2FFAA428, ADD UNIQUE INDEX UNIQ_EC12D8DF2FFAA428 (system_elect_invent_ouv_id)');
        $this->addSql('ALTER TABLE Utilisateurs ADD dette_credit_crees_id INT DEFAULT NULL, ADD dette_credit_rembourses_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Utilisateurs ADD CONSTRAINT FK_514AEAA63B9A683C FOREIGN KEY (dette_credit_rembourses_id) REFERENCES dettecreditdivers (id)');
        $this->addSql('ALTER TABLE Utilisateurs ADD CONSTRAINT FK_514AEAA6732C0433 FOREIGN KEY (dette_credit_crees_id) REFERENCES dettecreditdivers (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_514AEAA63B9A683C ON Utilisateurs (dette_credit_rembourses_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_514AEAA6732C0433 ON Utilisateurs (dette_credit_crees_id)');
    }
}
