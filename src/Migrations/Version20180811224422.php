<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180811224422 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE utilisateurs DROP FOREIGN KEY FK_514AEAA6314865F4');
        $this->addSql('DROP INDEX IDX_514AEAA6314865F4 ON utilisateurs');
        $this->addSql('ALTER TABLE utilisateurs CHANGE id_cpt_ecart idCompteEcartCaisse INT NOT NULL');
        $this->addSql('ALTER TABLE utilisateurs ADD CONSTRAINT FK_514AEAA680788604 FOREIGN KEY (idCompteEcartCaisse) REFERENCES Comptes (id)');
        $this->addSql('CREATE INDEX IDX_514AEAA680788604 ON utilisateurs (idCompteEcartCaisse)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Utilisateurs DROP FOREIGN KEY FK_514AEAA680788604');
        $this->addSql('DROP INDEX IDX_514AEAA680788604 ON Utilisateurs');
        $this->addSql('ALTER TABLE Utilisateurs CHANGE idcompteecartcaisse id_cpt_ecart INT NOT NULL');
        $this->addSql('ALTER TABLE Utilisateurs ADD CONSTRAINT FK_514AEAA6314865F4 FOREIGN KEY (id_cpt_ecart) REFERENCES comptes (id)');
        $this->addSql('CREATE INDEX IDX_514AEAA6314865F4 ON Utilisateurs (id_cpt_ecart)');
    }
}
