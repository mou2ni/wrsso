<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180711122111 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE utilisateurs ADD IdCompte INT NOT NULL');
        $this->addSql('ALTER TABLE utilisateurs ADD CONSTRAINT FK_514AEAA6559198AC FOREIGN KEY (IdCompte) REFERENCES Comptes (id)');
        $this->addSql('CREATE INDEX IDX_514AEAA6559198AC ON utilisateurs (IdCompte)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Utilisateurs DROP FOREIGN KEY FK_514AEAA6559198AC');
        $this->addSql('DROP INDEX IDX_514AEAA6559198AC ON Utilisateurs');
        $this->addSql('ALTER TABLE Utilisateurs DROP IdCompte');
    }
}
