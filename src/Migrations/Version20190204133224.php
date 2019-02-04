<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190204133224 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE clients ADD qualite VARCHAR(255) DEFAULT NULL, ADD est_representant TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE entreprises ADD representant_id INT DEFAULT NULL, ADD adresse VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE entreprises ADD CONSTRAINT FK_56B1B7A96C4A52F0 FOREIGN KEY (representant_id) REFERENCES Clients (id)');
        $this->addSql('CREATE INDEX IDX_56B1B7A96C4A52F0 ON entreprises (representant_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Clients DROP qualite, DROP est_representant');
        $this->addSql('ALTER TABLE entreprises DROP FOREIGN KEY FK_56B1B7A96C4A52F0');
        $this->addSql('DROP INDEX IDX_56B1B7A96C4A52F0 ON entreprises');
        $this->addSql('ALTER TABLE entreprises DROP representant_id, DROP adresse');
    }
}
