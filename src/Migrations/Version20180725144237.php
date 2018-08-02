<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180725144237 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comptes DROP FOREIGN KEY FK_99CE619D5D23CE99');
        $this->addSql('DROP INDEX IDX_99CE619D5D23CE99 ON comptes');
        $this->addSql('ALTER TABLE comptes CHANGE idclient client INT NOT NULL');
        $this->addSql('ALTER TABLE comptes ADD CONSTRAINT FK_99CE619DC7440455 FOREIGN KEY (client) REFERENCES Clients (id)');
        $this->addSql('CREATE INDEX IDX_99CE619DC7440455 ON comptes (client)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Comptes DROP FOREIGN KEY FK_99CE619DC7440455');
        $this->addSql('DROP INDEX IDX_99CE619DC7440455 ON Comptes');
        $this->addSql('ALTER TABLE Comptes CHANGE client IdClient INT NOT NULL');
        $this->addSql('ALTER TABLE Comptes ADD CONSTRAINT FK_99CE619D5D23CE99 FOREIGN KEY (IdClient) REFERENCES clients (id)');
        $this->addSql('CREATE INDEX IDX_99CE619D5D23CE99 ON Comptes (IdClient)');
    }
}
