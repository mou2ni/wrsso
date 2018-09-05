<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180831114717 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE dettecreditdivers DROP FOREIGN KEY FK_1C5147F2C8127358');
        $this->addSql('DROP INDEX IDX_1C5147F2C8127358 ON dettecreditdivers');
        $this->addSql('ALTER TABLE dettecreditdivers DROP journeeCaisseRemb');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE DetteCreditDivers ADD journeeCaisseRemb INT NOT NULL');
        $this->addSql('ALTER TABLE DetteCreditDivers ADD CONSTRAINT FK_1C5147F2C8127358 FOREIGN KEY (journeeCaisseRemb) REFERENCES journeecaisses (id)');
        $this->addSql('CREATE INDEX IDX_1C5147F2C8127358 ON DetteCreditDivers (journeeCaisseRemb)');
    }
}
