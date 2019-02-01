<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190130173616 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE collaborateurs (id INT AUTO_INCREMENT NOT NULL, compte_virement_id INT DEFAULT NULL, entreprise_id INT DEFAULT NULL, dernier_salaire_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, date_entree DATE NOT NULL, date_sortie DATE NOT NULL, statut VARCHAR(255) NOT NULL, nb_enfant INT NOT NULL, categorie VARCHAR(255) NOT NULL, n_securite_sociale VARCHAR(255) NOT NULL, m_salaire_base DOUBLE PRECISION NOT NULL, m_indem_logement DOUBLE PRECISION NOT NULL, m_indem_transport DOUBLE PRECISION NOT NULL, m_indem_fonction DOUBLE PRECISION NOT NULL, m_indem_autres DOUBLE PRECISION NOT NULL, m_heure_sup DOUBLE PRECISION NOT NULL, m_securite_sociale_salarie DOUBLE PRECISION NOT NULL, m_securite_sociale_patronale DOUBLE PRECISION NOT NULL, m_impot_salarie DOUBLE PRECISION NOT NULL, m_taxe_patronale DOUBLE PRECISION NOT NULL, INDEX IDX_4A340D9D3AA9837 (compte_virement_id), INDEX IDX_4A340D9A4AEAFEA (entreprise_id), INDEX IDX_4A340D92C2A202F (dernier_salaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entreprises (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE collaborateurs ADD CONSTRAINT FK_4A340D9D3AA9837 FOREIGN KEY (compte_virement_id) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE collaborateurs ADD CONSTRAINT FK_4A340D9A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprises (id)');
        $this->addSql('ALTER TABLE collaborateurs ADD CONSTRAINT FK_4A340D92C2A202F FOREIGN KEY (dernier_salaire_id) REFERENCES Salaires (id)');
        $this->addSql('ALTER TABLE ligne_salaires DROP FOREIGN KEY FK_2E1F3572F2C56620');
        $this->addSql('DROP INDEX IDX_2E1F3572F2C56620 ON ligne_salaires');
        $this->addSql('ALTER TABLE ligne_salaires ADD collaborateur_id INT DEFAULT NULL, ADD m_heure_sup DOUBLE PRECISION NOT NULL, ADD m_securite_sociale_salarie DOUBLE PRECISION NOT NULL, ADD m_securite_sociale_patronale DOUBLE PRECISION NOT NULL, DROP m_sociale_salarie, DROP m_sociale_patronale, CHANGE compte_id compte_virement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ligne_salaires ADD CONSTRAINT FK_2E1F3572D3AA9837 FOREIGN KEY (compte_virement_id) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE ligne_salaires ADD CONSTRAINT FK_2E1F3572A848E3B1 FOREIGN KEY (collaborateur_id) REFERENCES collaborateurs (id)');
        $this->addSql('CREATE INDEX IDX_2E1F3572D3AA9837 ON ligne_salaires (compte_virement_id)');
        $this->addSql('CREATE INDEX IDX_2E1F3572A848E3B1 ON ligne_salaires (collaborateur_id)');
        $this->addSql('ALTER TABLE paramcomptables ADD entreprise_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE paramcomptables ADD CONSTRAINT FK_DED38F08A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprises (id)');
        $this->addSql('CREATE INDEX IDX_DED38F08A4AEAFEA ON paramcomptables (entreprise_id)');
        $this->addSql('ALTER TABLE salaires ADD m_net_total DOUBLE PRECISION NOT NULL, ADD m_taxe_total DOUBLE PRECISION NOT NULL, ADD m_impot_total DOUBLE PRECISION NOT NULL, ADD m_securite_social_total DOUBLE PRECISION NOT NULL, ADD statut VARCHAR(255) NOT NULL, DROP m_charge_totale, DROP m_remuneration_totale');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_88F3461291D72643 ON salaires (periode_salaire)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ligne_salaires DROP FOREIGN KEY FK_2E1F3572A848E3B1');
        $this->addSql('ALTER TABLE collaborateurs DROP FOREIGN KEY FK_4A340D9A4AEAFEA');
        $this->addSql('ALTER TABLE ParamComptables DROP FOREIGN KEY FK_DED38F08A4AEAFEA');
        $this->addSql('DROP TABLE collaborateurs');
        $this->addSql('DROP TABLE entreprises');
        $this->addSql('ALTER TABLE ligne_salaires DROP FOREIGN KEY FK_2E1F3572D3AA9837');
        $this->addSql('DROP INDEX IDX_2E1F3572D3AA9837 ON ligne_salaires');
        $this->addSql('DROP INDEX IDX_2E1F3572A848E3B1 ON ligne_salaires');
        $this->addSql('ALTER TABLE ligne_salaires ADD compte_id INT DEFAULT NULL, ADD m_sociale_salarie DOUBLE PRECISION NOT NULL, ADD m_sociale_patronale DOUBLE PRECISION NOT NULL, DROP compte_virement_id, DROP collaborateur_id, DROP m_heure_sup, DROP m_securite_sociale_salarie, DROP m_securite_sociale_patronale');
        $this->addSql('ALTER TABLE ligne_salaires ADD CONSTRAINT FK_2E1F3572F2C56620 FOREIGN KEY (compte_id) REFERENCES comptes (id)');
        $this->addSql('CREATE INDEX IDX_2E1F3572F2C56620 ON ligne_salaires (compte_id)');
        $this->addSql('DROP INDEX IDX_DED38F08A4AEAFEA ON ParamComptables');
        $this->addSql('ALTER TABLE ParamComptables DROP entreprise_id');
        $this->addSql('DROP INDEX UNIQ_88F3461291D72643 ON Salaires');
        $this->addSql('ALTER TABLE Salaires ADD m_charge_totale DOUBLE PRECISION NOT NULL, ADD m_remuneration_totale DOUBLE PRECISION NOT NULL, DROP m_net_total, DROP m_taxe_total, DROP m_impot_total, DROP m_securite_social_total, DROP statut');
    }
}
