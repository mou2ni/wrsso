<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180816054402 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE utilisateurs ADD last_caisse_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateurs ADD CONSTRAINT FK_514AEAA6307B21B9 FOREIGN KEY (last_caisse_id) REFERENCES Caisses (id)');
        $this->addSql('CREATE INDEX IDX_514AEAA6307B21B9 ON utilisateurs (last_caisse_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Utilisateurs DROP FOREIGN KEY FK_514AEAA6307B21B9');
        $this->addSql('DROP INDEX IDX_514AEAA6307B21B9 ON Utilisateurs');
        $this->addSql('ALTER TABLE Utilisateurs DROP last_caisse_id');
    }
}
