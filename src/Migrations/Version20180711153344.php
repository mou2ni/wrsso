<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180711153344 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE utilisateurs DROP FOREIGN KEY FK_514AEAA6559198AC');
        $this->addSql('DROP INDEX IDX_514AEAA6559198AC ON utilisateurs');
        $this->addSql('ALTER TABLE utilisateurs ADD id_cpt_compense INT NOT NULL, CHANGE idcompte id_cpt_ecart INT NOT NULL');
        $this->addSql('ALTER TABLE utilisateurs ADD CONSTRAINT FK_514AEAA6314865F4 FOREIGN KEY (id_cpt_ecart) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE utilisateurs ADD CONSTRAINT FK_514AEAA6B2EACDB5 FOREIGN KEY (id_cpt_compense) REFERENCES Comptes (id)');
        $this->addSql('CREATE INDEX IDX_514AEAA6314865F4 ON utilisateurs (id_cpt_ecart)');
        $this->addSql('CREATE INDEX IDX_514AEAA6B2EACDB5 ON utilisateurs (id_cpt_compense)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Utilisateurs DROP FOREIGN KEY FK_514AEAA6314865F4');
        $this->addSql('ALTER TABLE Utilisateurs DROP FOREIGN KEY FK_514AEAA6B2EACDB5');
        $this->addSql('DROP INDEX IDX_514AEAA6314865F4 ON Utilisateurs');
        $this->addSql('DROP INDEX IDX_514AEAA6B2EACDB5 ON Utilisateurs');
        $this->addSql('ALTER TABLE Utilisateurs ADD IdCompte INT NOT NULL, DROP id_cpt_ecart, DROP id_cpt_compense');
        $this->addSql('ALTER TABLE Utilisateurs ADD CONSTRAINT FK_514AEAA6559198AC FOREIGN KEY (IdCompte) REFERENCES comptes (id)');
        $this->addSql('CREATE INDEX IDX_514AEAA6559198AC ON Utilisateurs (IdCompte)');
    }
}
