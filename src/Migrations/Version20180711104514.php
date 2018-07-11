<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180711104514 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE BilletageLignes (id INT AUTO_INCREMENT NOT NULL, id_billetage_id INT NOT NULL, valeur_billet_id INT NOT NULL, nb_billet DOUBLE PRECISION NOT NULL, valeur_ligne DOUBLE PRECISION NOT NULL, INDEX IDX_612BF20E3B06492F (id_billetage_id), INDEX IDX_612BF20EDE5B902F (valeur_billet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE billetages (id INT AUTO_INCREMENT NOT NULL, valeur_total DOUBLE PRECISION NOT NULL, date_billettage DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE billets (id INT AUTO_INCREMENT NOT NULL, valeur DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Caisses (id INT AUTO_INCREMENT NOT NULL, id_compte_operation_id INT NOT NULL, id_compte_ecart_id INT NOT NULL, libelle VARCHAR(255) NOT NULL, INDEX IDX_41BE1F466E6C2D2F (id_compte_operation_id), INDEX IDX_41BE1F46117E3302 (id_compte_ecart_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Clients (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Comptes (id INT AUTO_INCREMENT NOT NULL, num_compte VARCHAR(255) NOT NULL, intitule VARCHAR(255) NOT NULL, solde_courant INT DEFAULT 0, type_compte VARCHAR(255) NOT NULL, IdClient INT NOT NULL, INDEX IDX_99CE619D5D23CE99 (IdClient), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Utilisateurs (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(255) NOT NULL, mdp VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, est_caissier TINYINT(1) NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE DetteCreditDivers (id INT AUTO_INCREMENT NOT NULL, id_caisse_id INT NOT NULL, id_utilisateur_creation_id INT NOT NULL, id_utilisateur_remb_id INT NOT NULL, date_dc DATETIME NOT NULL, libelle VARCHAR(255) NOT NULL, statut VARCHAR(255) NOT NULL, m_credit DOUBLE PRECISION NOT NULL, m_dette DOUBLE PRECISION NOT NULL, INDEX IDX_1C5147F2A7814298 (id_caisse_id), INDEX IDX_1C5147F2AE727C59 (id_utilisateur_creation_id), INDEX IDX_1C5147F2EBCE7B9A (id_utilisateur_remb_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE DeviseIntercaisses (id INT AUTO_INCREMENT NOT NULL, id_journee_caisse_source_id INT NOT NULL, id_journee_caisse_partenaire_id INT NOT NULL, id_devise_id INT NOT NULL, m_intercaisse DOUBLE PRECISION NOT NULL, statut VARCHAR(255) NOT NULL, observations VARCHAR(255) NOT NULL, INDEX IDX_D7381A146DAB0C5E (id_journee_caisse_source_id), INDEX IDX_D7381A14A1D63ED8 (id_journee_caisse_partenaire_id), INDEX IDX_D7381A147471EC71 (id_devise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE DeviseJournees (id INT AUTO_INCREMENT NOT NULL, id_journee_caisse_id INT NOT NULL, id_devise_id INT NOT NULL, id_billet_ouv_id INT NOT NULL, id_billet_ferm_id INT NOT NULL, qte_ouv DOUBLE PRECISION NOT NULL, ecart_ouv DOUBLE PRECISION NOT NULL, qte_achat DOUBLE PRECISION NOT NULL, qte_vente DOUBLE PRECISION NOT NULL, m_cvd_achat DOUBLE PRECISION NOT NULL, m_cvd_vente DOUBLE PRECISION NOT NULL, qte_intercaisse DOUBLE PRECISION NOT NULL, qte_ferm DOUBLE PRECISION NOT NULL, ecart_ferm DOUBLE PRECISION NOT NULL, INDEX IDX_282BE5D21CA528D7 (id_journee_caisse_id), INDEX IDX_282BE5D27471EC71 (id_devise_id), INDEX IDX_282BE5D2FAABDE7 (id_billet_ouv_id), INDEX IDX_282BE5D2A18A167 (id_billet_ferm_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE DeviseMouvements (id INT AUTO_INCREMENT NOT NULL, id_journee_caisse_id INT NOT NULL, id_devise_id INT NOT NULL, sens VARCHAR(255) NOT NULL, nombre DOUBLE PRECISION NOT NULL, m_cvd DOUBLE PRECISION NOT NULL, INDEX IDX_F832A3811CA528D7 (id_journee_caisse_id), INDEX IDX_F832A3817471EC71 (id_devise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Devises (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, libelle VARCHAR(255) NOT NULL, date_modification DATETIME NOT NULL, tx_achat DOUBLE PRECISION NOT NULL, tx_vente DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE InterCaisses (id INT AUTO_INCREMENT NOT NULL, id_journee_caisse_source_id INT NOT NULL, id_journee_caisse_destination_id INT NOT NULL, m_intercaisse DOUBLE PRECISION NOT NULL, statut VARCHAR(255) NOT NULL, observations VARCHAR(255) NOT NULL, INDEX IDX_F4C41EC6DAB0C5E (id_journee_caisse_source_id), INDEX IDX_F4C41ECD63DDC48 (id_journee_caisse_destination_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE JourneeCaisses (id INT AUTO_INCREMENT NOT NULL, id_caisse_id INT DEFAULT NULL, id_utilisateur_id INT DEFAULT NULL, id_journee_suivante_id INT DEFAULT NULL, id_billet_ouv_id INT DEFAULT NULL, id_system_elect_invent_ouv_id INT DEFAULT NULL, id_billet_ferm_id INT DEFAULT NULL, id_system_elect_invent_ferm_id INT DEFAULT NULL, date_ouv DATETIME NOT NULL, statut VARCHAR(255) NOT NULL, valeur_billet BIGINT NOT NULL, solde_elect_ouv BIGINT NOT NULL, ecart_ouv BIGINT NOT NULL, m_cvd BIGINT NOT NULL, m_emission_trans BIGINT NOT NULL, m_reception_trans BIGINT NOT NULL, m_intercaisse BIGINT NOT NULL, m_retrait_client BIGINT NOT NULL, m_depot_client BIGINT NOT NULL, m_credit_divers BIGINT NOT NULL, m_dette_divers BIGINT NOT NULL, date_ferm DATETIME NOT NULL, valeur_billet_ferm BIGINT NOT NULL, solde_elect_ferm BIGINT NOT NULL, m_ecart_ferm BIGINT NOT NULL, INDEX IDX_EC12D8DFA7814298 (id_caisse_id), INDEX IDX_EC12D8DFC6EE5C49 (id_utilisateur_id), INDEX IDX_EC12D8DFC2D9BD12 (id_journee_suivante_id), INDEX IDX_EC12D8DFFAABDE7 (id_billet_ouv_id), INDEX IDX_EC12D8DF575F61FE (id_system_elect_invent_ouv_id), INDEX IDX_EC12D8DFA18A167 (id_billet_ferm_id), INDEX IDX_EC12D8DF6E2BFC7B (id_system_elect_invent_ferm_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ParamComptables (id INT AUTO_INCREMENT NOT NULL, id_cpt_intercaisse INT DEFAULT NULL, id_cpt_cvd INT DEFAULT NULL, id_cpt_compense INT DEFAULT NULL, id_cpt_chrg_salaire INT DEFAULT NULL, id_cpt_ecart INT DEFAULT NULL, code_structure VARCHAR(255) NOT NULL, INDEX IDX_DED38F081A661CCE (id_cpt_intercaisse), INDEX IDX_DED38F08149A7A04 (id_cpt_cvd), INDEX IDX_DED38F08B2EACDB5 (id_cpt_compense), INDEX IDX_DED38F089119D00F (id_cpt_chrg_salaire), INDEX IDX_DED38F08314865F4 (id_cpt_ecart), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Pays (id INT AUTO_INCREMENT NOT NULL, libelle DOUBLE PRECISION NOT NULL, zone DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE RecetteDepenses (id INT AUTO_INCREMENT NOT NULL, id_utilisateur_id INT NOT NULL, id_trans_id INT NOT NULL, date_operation DATETIME NOT NULL, m_recette DOUBLE PRECISION NOT NULL, libelle VARCHAR(255) NOT NULL, m_depense DOUBLE PRECISION NOT NULL, statut VARCHAR(255) NOT NULL, INDEX IDX_2FB66904C6EE5C49 (id_utilisateur_id), INDEX IDX_2FB6690463346B17 (id_trans_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Salaires (id INT AUTO_INCREMENT NOT NULL, id_trans_id INT NOT NULL, periode_salaire DOUBLE PRECISION NOT NULL, m_salaire DOUBLE PRECISION NOT NULL, INDEX IDX_88F3461263346B17 (id_trans_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE SystemElectInventaires (id INT AUTO_INCREMENT NOT NULL, date_inventaire DATETIME NOT NULL, solde_total DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE SystemElectLigneInventaires (id INT AUTO_INCREMENT NOT NULL, id_system_elect_inventaire_id INT NOT NULL, id_system_elect_id INT NOT NULL, solde DOUBLE PRECISION NOT NULL, INDEX IDX_E1A0E8C89D6788D (id_system_elect_inventaire_id), INDEX IDX_E1A0E8CCA7D9DB0 (id_system_elect_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE SystemElects (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE SystemTransfert (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE TransactionComptes (id INT AUTO_INCREMENT NOT NULL, num_compte VARCHAR(255) NOT NULL, m_debit DOUBLE PRECISION NOT NULL, m_credit DOUBLE PRECISION NOT NULL, IdCompte INT NOT NULL, IdTransaction INT NOT NULL, INDEX IDX_4BC64C6E559198AC (IdCompte), INDEX IDX_4BC64C6E8114B15C (IdTransaction), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Transactions (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, date_transaction DATETIME NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, idUtilisateur INT NOT NULL, idUtilisateurLast INT NOT NULL, INDEX IDX_F299C1B45D419CCB (idUtilisateur), INDEX IDX_F299C1B443147D00 (idUtilisateurLast), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE TransfertInternationaux (id INT AUTO_INCREMENT NOT NULL, id_journee_caisse_id INT NOT NULL, id_system_transfert_id INT NOT NULL, id_pays_id INT NOT NULL, sens VARCHAR(255) NOT NULL, m_transfert VARCHAR(255) NOT NULL, m_frais_ht VARCHAR(255) NOT NULL, m_tva VARCHAR(255) NOT NULL, m_autres_taxes VARCHAR(255) NOT NULL, INDEX IDX_CD12576A1CA528D7 (id_journee_caisse_id), INDEX IDX_CD12576A6945FF6B (id_system_transfert_id), INDEX IDX_CD12576A7879EB34 (id_pays_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE BilletageLignes ADD CONSTRAINT FK_612BF20E3B06492F FOREIGN KEY (id_billetage_id) REFERENCES billetages (id)');
        $this->addSql('ALTER TABLE BilletageLignes ADD CONSTRAINT FK_612BF20EDE5B902F FOREIGN KEY (valeur_billet_id) REFERENCES billets (id)');
        $this->addSql('ALTER TABLE Caisses ADD CONSTRAINT FK_41BE1F466E6C2D2F FOREIGN KEY (id_compte_operation_id) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE Caisses ADD CONSTRAINT FK_41BE1F46117E3302 FOREIGN KEY (id_compte_ecart_id) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE Comptes ADD CONSTRAINT FK_99CE619D5D23CE99 FOREIGN KEY (IdClient) REFERENCES Clients (id)');
        $this->addSql('ALTER TABLE DetteCreditDivers ADD CONSTRAINT FK_1C5147F2A7814298 FOREIGN KEY (id_caisse_id) REFERENCES Caisses (id)');
        $this->addSql('ALTER TABLE DetteCreditDivers ADD CONSTRAINT FK_1C5147F2AE727C59 FOREIGN KEY (id_utilisateur_creation_id) REFERENCES Utilisateurs (id)');
        $this->addSql('ALTER TABLE DetteCreditDivers ADD CONSTRAINT FK_1C5147F2EBCE7B9A FOREIGN KEY (id_utilisateur_remb_id) REFERENCES Utilisateurs (id)');
        $this->addSql('ALTER TABLE DeviseIntercaisses ADD CONSTRAINT FK_D7381A146DAB0C5E FOREIGN KEY (id_journee_caisse_source_id) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE DeviseIntercaisses ADD CONSTRAINT FK_D7381A14A1D63ED8 FOREIGN KEY (id_journee_caisse_partenaire_id) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE DeviseIntercaisses ADD CONSTRAINT FK_D7381A147471EC71 FOREIGN KEY (id_devise_id) REFERENCES Devises (id)');
        $this->addSql('ALTER TABLE DeviseJournees ADD CONSTRAINT FK_282BE5D21CA528D7 FOREIGN KEY (id_journee_caisse_id) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE DeviseJournees ADD CONSTRAINT FK_282BE5D27471EC71 FOREIGN KEY (id_devise_id) REFERENCES Devises (id)');
        $this->addSql('ALTER TABLE DeviseJournees ADD CONSTRAINT FK_282BE5D2FAABDE7 FOREIGN KEY (id_billet_ouv_id) REFERENCES billets (id)');
        $this->addSql('ALTER TABLE DeviseJournees ADD CONSTRAINT FK_282BE5D2A18A167 FOREIGN KEY (id_billet_ferm_id) REFERENCES billets (id)');
        $this->addSql('ALTER TABLE DeviseMouvements ADD CONSTRAINT FK_F832A3811CA528D7 FOREIGN KEY (id_journee_caisse_id) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE DeviseMouvements ADD CONSTRAINT FK_F832A3817471EC71 FOREIGN KEY (id_devise_id) REFERENCES Devises (id)');
        $this->addSql('ALTER TABLE InterCaisses ADD CONSTRAINT FK_F4C41EC6DAB0C5E FOREIGN KEY (id_journee_caisse_source_id) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE InterCaisses ADD CONSTRAINT FK_F4C41ECD63DDC48 FOREIGN KEY (id_journee_caisse_destination_id) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE JourneeCaisses ADD CONSTRAINT FK_EC12D8DFA7814298 FOREIGN KEY (id_caisse_id) REFERENCES Caisses (id)');
        $this->addSql('ALTER TABLE JourneeCaisses ADD CONSTRAINT FK_EC12D8DFC6EE5C49 FOREIGN KEY (id_utilisateur_id) REFERENCES Utilisateurs (id)');
        $this->addSql('ALTER TABLE JourneeCaisses ADD CONSTRAINT FK_EC12D8DFC2D9BD12 FOREIGN KEY (id_journee_suivante_id) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE JourneeCaisses ADD CONSTRAINT FK_EC12D8DFFAABDE7 FOREIGN KEY (id_billet_ouv_id) REFERENCES billetages (id)');
        $this->addSql('ALTER TABLE JourneeCaisses ADD CONSTRAINT FK_EC12D8DF575F61FE FOREIGN KEY (id_system_elect_invent_ouv_id) REFERENCES SystemElectInventaires (id)');
        $this->addSql('ALTER TABLE JourneeCaisses ADD CONSTRAINT FK_EC12D8DFA18A167 FOREIGN KEY (id_billet_ferm_id) REFERENCES billetages (id)');
        $this->addSql('ALTER TABLE JourneeCaisses ADD CONSTRAINT FK_EC12D8DF6E2BFC7B FOREIGN KEY (id_system_elect_invent_ferm_id) REFERENCES SystemElectInventaires (id)');
        $this->addSql('ALTER TABLE ParamComptables ADD CONSTRAINT FK_DED38F081A661CCE FOREIGN KEY (id_cpt_intercaisse) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE ParamComptables ADD CONSTRAINT FK_DED38F08149A7A04 FOREIGN KEY (id_cpt_cvd) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE ParamComptables ADD CONSTRAINT FK_DED38F08B2EACDB5 FOREIGN KEY (id_cpt_compense) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE ParamComptables ADD CONSTRAINT FK_DED38F089119D00F FOREIGN KEY (id_cpt_chrg_salaire) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE ParamComptables ADD CONSTRAINT FK_DED38F08314865F4 FOREIGN KEY (id_cpt_ecart) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE RecetteDepenses ADD CONSTRAINT FK_2FB66904C6EE5C49 FOREIGN KEY (id_utilisateur_id) REFERENCES Utilisateurs (id)');
        $this->addSql('ALTER TABLE RecetteDepenses ADD CONSTRAINT FK_2FB6690463346B17 FOREIGN KEY (id_trans_id) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE Salaires ADD CONSTRAINT FK_88F3461263346B17 FOREIGN KEY (id_trans_id) REFERENCES Transactions (id)');
        $this->addSql('ALTER TABLE SystemElectLigneInventaires ADD CONSTRAINT FK_E1A0E8C89D6788D FOREIGN KEY (id_system_elect_inventaire_id) REFERENCES SystemElectInventaires (id)');
        $this->addSql('ALTER TABLE SystemElectLigneInventaires ADD CONSTRAINT FK_E1A0E8CCA7D9DB0 FOREIGN KEY (id_system_elect_id) REFERENCES SystemElects (id)');
        $this->addSql('ALTER TABLE TransactionComptes ADD CONSTRAINT FK_4BC64C6E559198AC FOREIGN KEY (IdCompte) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE TransactionComptes ADD CONSTRAINT FK_4BC64C6E8114B15C FOREIGN KEY (IdTransaction) REFERENCES TransactionComptes (id)');
        $this->addSql('ALTER TABLE Transactions ADD CONSTRAINT FK_F299C1B45D419CCB FOREIGN KEY (idUtilisateur) REFERENCES Utilisateurs (id)');
        $this->addSql('ALTER TABLE Transactions ADD CONSTRAINT FK_F299C1B443147D00 FOREIGN KEY (idUtilisateurLast) REFERENCES Utilisateurs (id)');
        $this->addSql('ALTER TABLE TransfertInternationaux ADD CONSTRAINT FK_CD12576A1CA528D7 FOREIGN KEY (id_journee_caisse_id) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE TransfertInternationaux ADD CONSTRAINT FK_CD12576A6945FF6B FOREIGN KEY (id_system_transfert_id) REFERENCES SystemTransfert (id)');
        $this->addSql('ALTER TABLE TransfertInternationaux ADD CONSTRAINT FK_CD12576A7879EB34 FOREIGN KEY (id_pays_id) REFERENCES Pays (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE BilletageLignes DROP FOREIGN KEY FK_612BF20E3B06492F');
        $this->addSql('ALTER TABLE JourneeCaisses DROP FOREIGN KEY FK_EC12D8DFFAABDE7');
        $this->addSql('ALTER TABLE JourneeCaisses DROP FOREIGN KEY FK_EC12D8DFA18A167');
        $this->addSql('ALTER TABLE BilletageLignes DROP FOREIGN KEY FK_612BF20EDE5B902F');
        $this->addSql('ALTER TABLE DeviseJournees DROP FOREIGN KEY FK_282BE5D2FAABDE7');
        $this->addSql('ALTER TABLE DeviseJournees DROP FOREIGN KEY FK_282BE5D2A18A167');
        $this->addSql('ALTER TABLE DetteCreditDivers DROP FOREIGN KEY FK_1C5147F2A7814298');
        $this->addSql('ALTER TABLE JourneeCaisses DROP FOREIGN KEY FK_EC12D8DFA7814298');
        $this->addSql('ALTER TABLE Comptes DROP FOREIGN KEY FK_99CE619D5D23CE99');
        $this->addSql('ALTER TABLE Caisses DROP FOREIGN KEY FK_41BE1F466E6C2D2F');
        $this->addSql('ALTER TABLE Caisses DROP FOREIGN KEY FK_41BE1F46117E3302');
        $this->addSql('ALTER TABLE ParamComptables DROP FOREIGN KEY FK_DED38F081A661CCE');
        $this->addSql('ALTER TABLE ParamComptables DROP FOREIGN KEY FK_DED38F08149A7A04');
        $this->addSql('ALTER TABLE ParamComptables DROP FOREIGN KEY FK_DED38F08B2EACDB5');
        $this->addSql('ALTER TABLE ParamComptables DROP FOREIGN KEY FK_DED38F089119D00F');
        $this->addSql('ALTER TABLE ParamComptables DROP FOREIGN KEY FK_DED38F08314865F4');
        $this->addSql('ALTER TABLE RecetteDepenses DROP FOREIGN KEY FK_2FB6690463346B17');
        $this->addSql('ALTER TABLE TransactionComptes DROP FOREIGN KEY FK_4BC64C6E559198AC');
        $this->addSql('ALTER TABLE DetteCreditDivers DROP FOREIGN KEY FK_1C5147F2AE727C59');
        $this->addSql('ALTER TABLE DetteCreditDivers DROP FOREIGN KEY FK_1C5147F2EBCE7B9A');
        $this->addSql('ALTER TABLE JourneeCaisses DROP FOREIGN KEY FK_EC12D8DFC6EE5C49');
        $this->addSql('ALTER TABLE RecetteDepenses DROP FOREIGN KEY FK_2FB66904C6EE5C49');
        $this->addSql('ALTER TABLE Transactions DROP FOREIGN KEY FK_F299C1B45D419CCB');
        $this->addSql('ALTER TABLE Transactions DROP FOREIGN KEY FK_F299C1B443147D00');
        $this->addSql('ALTER TABLE DeviseIntercaisses DROP FOREIGN KEY FK_D7381A147471EC71');
        $this->addSql('ALTER TABLE DeviseJournees DROP FOREIGN KEY FK_282BE5D27471EC71');
        $this->addSql('ALTER TABLE DeviseMouvements DROP FOREIGN KEY FK_F832A3817471EC71');
        $this->addSql('ALTER TABLE DeviseIntercaisses DROP FOREIGN KEY FK_D7381A146DAB0C5E');
        $this->addSql('ALTER TABLE DeviseIntercaisses DROP FOREIGN KEY FK_D7381A14A1D63ED8');
        $this->addSql('ALTER TABLE DeviseJournees DROP FOREIGN KEY FK_282BE5D21CA528D7');
        $this->addSql('ALTER TABLE DeviseMouvements DROP FOREIGN KEY FK_F832A3811CA528D7');
        $this->addSql('ALTER TABLE InterCaisses DROP FOREIGN KEY FK_F4C41EC6DAB0C5E');
        $this->addSql('ALTER TABLE InterCaisses DROP FOREIGN KEY FK_F4C41ECD63DDC48');
        $this->addSql('ALTER TABLE JourneeCaisses DROP FOREIGN KEY FK_EC12D8DFC2D9BD12');
        $this->addSql('ALTER TABLE TransfertInternationaux DROP FOREIGN KEY FK_CD12576A1CA528D7');
        $this->addSql('ALTER TABLE TransfertInternationaux DROP FOREIGN KEY FK_CD12576A7879EB34');
        $this->addSql('ALTER TABLE JourneeCaisses DROP FOREIGN KEY FK_EC12D8DF575F61FE');
        $this->addSql('ALTER TABLE JourneeCaisses DROP FOREIGN KEY FK_EC12D8DF6E2BFC7B');
        $this->addSql('ALTER TABLE SystemElectLigneInventaires DROP FOREIGN KEY FK_E1A0E8C89D6788D');
        $this->addSql('ALTER TABLE SystemElectLigneInventaires DROP FOREIGN KEY FK_E1A0E8CCA7D9DB0');
        $this->addSql('ALTER TABLE TransfertInternationaux DROP FOREIGN KEY FK_CD12576A6945FF6B');
        $this->addSql('ALTER TABLE TransactionComptes DROP FOREIGN KEY FK_4BC64C6E8114B15C');
        $this->addSql('ALTER TABLE Salaires DROP FOREIGN KEY FK_88F3461263346B17');
        $this->addSql('DROP TABLE BilletageLignes');
        $this->addSql('DROP TABLE billetages');
        $this->addSql('DROP TABLE billets');
        $this->addSql('DROP TABLE Caisses');
        $this->addSql('DROP TABLE Clients');
        $this->addSql('DROP TABLE Comptes');
        $this->addSql('DROP TABLE Utilisateurs');
        $this->addSql('DROP TABLE DetteCreditDivers');
        $this->addSql('DROP TABLE DeviseIntercaisses');
        $this->addSql('DROP TABLE DeviseJournees');
        $this->addSql('DROP TABLE DeviseMouvements');
        $this->addSql('DROP TABLE Devises');
        $this->addSql('DROP TABLE InterCaisses');
        $this->addSql('DROP TABLE JourneeCaisses');
        $this->addSql('DROP TABLE ParamComptables');
        $this->addSql('DROP TABLE Pays');
        $this->addSql('DROP TABLE RecetteDepenses');
        $this->addSql('DROP TABLE Salaires');
        $this->addSql('DROP TABLE SystemElectInventaires');
        $this->addSql('DROP TABLE SystemElectLigneInventaires');
        $this->addSql('DROP TABLE SystemElects');
        $this->addSql('DROP TABLE SystemTransfert');
        $this->addSql('DROP TABLE TransactionComptes');
        $this->addSql('DROP TABLE Transactions');
        $this->addSql('DROP TABLE TransfertInternationaux');
    }
}
