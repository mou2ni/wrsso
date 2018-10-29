<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181022111240 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE dettecreditdivers ADD date_remboursement DATETIME DEFAULT NULL, ADD utilisateurCreat INT DEFAULT NULL, ADD utilisateurRemb INT DEFAULT NULL, CHANGE date_dc date_creation DATETIME NOT NULL');
        $this->addSql('ALTER TABLE dettecreditdivers ADD CONSTRAINT FK_1C5147F2E97147BA FOREIGN KEY (utilisateurCreat) REFERENCES Utilisateurs (id)');
        $this->addSql('ALTER TABLE dettecreditdivers ADD CONSTRAINT FK_1C5147F2BD94A1E9 FOREIGN KEY (utilisateurRemb) REFERENCES Utilisateurs (id)');
        $this->addSql('CREATE INDEX IDX_1C5147F2E97147BA ON dettecreditdivers (utilisateurCreat)');
        $this->addSql('CREATE INDEX IDX_1C5147F2BD94A1E9 ON dettecreditdivers (utilisateurRemb)');
        $this->addSql('ALTER TABLE utilisateurs ADD dette_credit_crees_id INT DEFAULT NULL, ADD dette_credit_rembourses_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateurs ADD CONSTRAINT FK_514AEAA6732C0433 FOREIGN KEY (dette_credit_crees_id) REFERENCES DetteCreditDivers (id)');
        $this->addSql('ALTER TABLE utilisateurs ADD CONSTRAINT FK_514AEAA63B9A683C FOREIGN KEY (dette_credit_rembourses_id) REFERENCES DetteCreditDivers (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_514AEAA6732C0433 ON utilisateurs (dette_credit_crees_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_514AEAA63B9A683C ON utilisateurs (dette_credit_rembourses_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE DetteCreditDivers DROP FOREIGN KEY FK_1C5147F2E97147BA');
        $this->addSql('ALTER TABLE DetteCreditDivers DROP FOREIGN KEY FK_1C5147F2BD94A1E9');
        $this->addSql('DROP INDEX IDX_1C5147F2E97147BA ON DetteCreditDivers');
        $this->addSql('DROP INDEX IDX_1C5147F2BD94A1E9 ON DetteCreditDivers');
        $this->addSql('ALTER TABLE DetteCreditDivers DROP date_remboursement, DROP utilisateurCreat, DROP utilisateurRemb, CHANGE date_creation date_dc DATETIME NOT NULL');
        $this->addSql('ALTER TABLE Utilisateurs DROP FOREIGN KEY FK_514AEAA6732C0433');
        $this->addSql('ALTER TABLE Utilisateurs DROP FOREIGN KEY FK_514AEAA63B9A683C');
        $this->addSql('DROP INDEX UNIQ_514AEAA6732C0433 ON Utilisateurs');
        $this->addSql('DROP INDEX UNIQ_514AEAA63B9A683C ON Utilisateurs');
        $this->addSql('ALTER TABLE Utilisateurs DROP dette_credit_crees_id, DROP dette_credit_rembourses_id');
    }
}
