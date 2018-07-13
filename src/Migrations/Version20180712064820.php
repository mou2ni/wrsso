<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180712064820 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE utilisateurs DROP FOREIGN KEY FK_514AEAA6B2EACDB5');
        $this->addSql('DROP INDEX IDX_514AEAA6B2EACDB5 ON utilisateurs');
        $this->addSql('ALTER TABLE utilisateurs DROP id_cpt_compense');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Utilisateurs ADD id_cpt_compense INT NOT NULL');
        $this->addSql('ALTER TABLE Utilisateurs ADD CONSTRAINT FK_514AEAA6B2EACDB5 FOREIGN KEY (id_cpt_compense) REFERENCES comptes (id)');
        $this->addSql('CREATE INDEX IDX_514AEAA6B2EACDB5 ON Utilisateurs (id_cpt_compense)');
    }
}
