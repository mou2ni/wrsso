<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181226103358 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE caisses CHANGE journee_ouverte_id last_journee_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE caisses ADD CONSTRAINT FK_41BE1F4626720BA2 FOREIGN KEY (last_journee_id) REFERENCES JourneeCaisses (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_41BE1F4626720BA2 ON caisses (last_journee_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Caisses DROP FOREIGN KEY FK_41BE1F4626720BA2');
        $this->addSql('DROP INDEX UNIQ_41BE1F4626720BA2 ON Caisses');
        $this->addSql('ALTER TABLE Caisses CHANGE last_journee_id journee_ouverte_id INT DEFAULT NULL');
    }
}
