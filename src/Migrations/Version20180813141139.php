<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180813141139 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE dettecreditdivers DROP FOREIGN KEY FK_1C5147F2AE727C59');
        $this->addSql('ALTER TABLE dettecreditdivers DROP FOREIGN KEY FK_1C5147F2EBCE7B9A');
        $this->addSql('DROP INDEX IDX_1C5147F2AE727C59 ON dettecreditdivers');
        $this->addSql('DROP INDEX IDX_1C5147F2EBCE7B9A ON dettecreditdivers');
        $this->addSql('ALTER TABLE dettecreditdivers ADD journeeCaissesCreation INT NOT NULL, ADD journeeCaisseRemb INT NOT NULL, DROP id_utilisateur_creation_id, DROP id_utilisateur_remb_id');
        $this->addSql('ALTER TABLE dettecreditdivers ADD CONSTRAINT FK_1C5147F2C22CD53D FOREIGN KEY (journeeCaissesCreation) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE dettecreditdivers ADD CONSTRAINT FK_1C5147F2C8127358 FOREIGN KEY (journeeCaisseRemb) REFERENCES JourneeCaisses (id)');
        $this->addSql('CREATE INDEX IDX_1C5147F2C22CD53D ON dettecreditdivers (journeeCaissesCreation)');
        $this->addSql('CREATE INDEX IDX_1C5147F2C8127358 ON dettecreditdivers (journeeCaisseRemb)');
        $this->addSql('ALTER TABLE caisses DROP FOREIGN KEY FK_41BE1F464F6FD7D');
        $this->addSql('DROP INDEX IDX_41BE1F464F6FD7D ON caisses');
        $this->addSql('ALTER TABLE caisses DROP statut, CHANGE compte_cv_devise_id id_cpt_cv_devise INT DEFAULT NULL');
        $this->addSql('ALTER TABLE caisses ADD CONSTRAINT FK_41BE1F468A8E563 FOREIGN KEY (id_cpt_cv_devise) REFERENCES Comptes (id)');
        $this->addSql('CREATE INDEX IDX_41BE1F468A8E563 ON caisses (id_cpt_cv_devise)');
        $this->addSql('ALTER TABLE utilisateurs DROP FOREIGN KEY FK_514AEAA680788604');
        $this->addSql('DROP INDEX IDX_514AEAA680788604 ON utilisateurs');
        $this->addSql('ALTER TABLE utilisateurs CHANGE idcompteecartcaisse id_cpt_ecart INT NOT NULL');
        $this->addSql('ALTER TABLE utilisateurs ADD CONSTRAINT FK_514AEAA6314865F4 FOREIGN KEY (id_cpt_ecart) REFERENCES Comptes (id)');
        $this->addSql('CREATE INDEX IDX_514AEAA6314865F4 ON utilisateurs (id_cpt_ecart)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Caisses DROP FOREIGN KEY FK_41BE1F468A8E563');
        $this->addSql('DROP INDEX IDX_41BE1F468A8E563 ON Caisses');
        $this->addSql('ALTER TABLE Caisses ADD statut VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE id_cpt_cv_devise compte_cv_devise_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Caisses ADD CONSTRAINT FK_41BE1F464F6FD7D FOREIGN KEY (compte_cv_devise_id) REFERENCES comptes (id)');
        $this->addSql('CREATE INDEX IDX_41BE1F464F6FD7D ON Caisses (compte_cv_devise_id)');
        $this->addSql('ALTER TABLE DetteCreditDivers DROP FOREIGN KEY FK_1C5147F2C22CD53D');
        $this->addSql('ALTER TABLE DetteCreditDivers DROP FOREIGN KEY FK_1C5147F2C8127358');
        $this->addSql('DROP INDEX IDX_1C5147F2C22CD53D ON DetteCreditDivers');
        $this->addSql('DROP INDEX IDX_1C5147F2C8127358 ON DetteCreditDivers');
        $this->addSql('ALTER TABLE DetteCreditDivers ADD id_utilisateur_creation_id INT NOT NULL, ADD id_utilisateur_remb_id INT NOT NULL, DROP journeeCaissesCreation, DROP journeeCaisseRemb');
        $this->addSql('ALTER TABLE DetteCreditDivers ADD CONSTRAINT FK_1C5147F2AE727C59 FOREIGN KEY (id_utilisateur_creation_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE DetteCreditDivers ADD CONSTRAINT FK_1C5147F2EBCE7B9A FOREIGN KEY (id_utilisateur_remb_id) REFERENCES utilisateurs (id)');
        $this->addSql('CREATE INDEX IDX_1C5147F2AE727C59 ON DetteCreditDivers (id_utilisateur_creation_id)');
        $this->addSql('CREATE INDEX IDX_1C5147F2EBCE7B9A ON DetteCreditDivers (id_utilisateur_remb_id)');
        $this->addSql('ALTER TABLE Utilisateurs DROP FOREIGN KEY FK_514AEAA6314865F4');
        $this->addSql('DROP INDEX IDX_514AEAA6314865F4 ON Utilisateurs');
        $this->addSql('ALTER TABLE Utilisateurs CHANGE id_cpt_ecart idCompteEcartCaisse INT NOT NULL');
        $this->addSql('ALTER TABLE Utilisateurs ADD CONSTRAINT FK_514AEAA680788604 FOREIGN KEY (idCompteEcartCaisse) REFERENCES comptes (id)');
        $this->addSql('CREATE INDEX IDX_514AEAA680788604 ON Utilisateurs (idCompteEcartCaisse)');
    }
}
