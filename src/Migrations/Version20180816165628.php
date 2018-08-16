<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180816165628 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE journeecaisses DROP FOREIGN KEY FK_EC12D8DF27B4FEBF');
        $this->addSql('ALTER TABLE journeecaisses CHANGE caisse_id caisse_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE journeecaisses ADD CONSTRAINT FK_EC12D8DF27B4FEBF FOREIGN KEY (caisse_id) REFERENCES Caisses (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE JourneeCaisses DROP FOREIGN KEY FK_EC12D8DF27B4FEBF');
        $this->addSql('ALTER TABLE JourneeCaisses CHANGE caisse_id caisse_id INT NOT NULL');
        $this->addSql('ALTER TABLE JourneeCaisses ADD CONSTRAINT FK_EC12D8DF27B4FEBF FOREIGN KEY (caisse_id) REFERENCES caisses (id)');
    }
}
