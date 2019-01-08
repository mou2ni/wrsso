<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190104182805 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE caisses ADD compte_attente_compense_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE caisses ADD CONSTRAINT FK_41BE1F46E8526BE8 FOREIGN KEY (compte_attente_compense_id) REFERENCES Comptes (id)');
        $this->addSql('CREATE INDEX IDX_41BE1F46E8526BE8 ON caisses (compte_attente_compense_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Caisses DROP FOREIGN KEY FK_41BE1F46E8526BE8');
        $this->addSql('DROP INDEX IDX_41BE1F46E8526BE8 ON Caisses');
        $this->addSql('ALTER TABLE Caisses DROP compte_attente_compense_id');
    }
}
