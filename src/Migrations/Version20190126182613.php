<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190126182613 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE BilletageLignes (id INT AUTO_INCREMENT NOT NULL, billetages_id INT NOT NULL, billet_id INT NOT NULL, valeur_billet DOUBLE PRECISION NOT NULL, nb_billet INT NOT NULL, INDEX IDX_612BF20EF2B35750 (billetages_id), INDEX IDX_612BF20E44973C78 (billet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE billetages (id INT AUTO_INCREMENT NOT NULL, date_billettage DATETIME NOT NULL, valeur_total DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE billets (id INT AUTO_INCREMENT NOT NULL, devise_id INT DEFAULT NULL, valeur DOUBLE PRECISION NOT NULL, is_active TINYINT(1) NOT NULL, INDEX IDX_4FCF9B68F4445056 (devise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Caisses (id INT AUTO_INCREMENT NOT NULL, compte_operation_id INT NOT NULL, id_cpt_cv_devise INT DEFAULT NULL, compte_intercaisse_id INT DEFAULT NULL, compte_attente_compense_id INT DEFAULT NULL, last_journee_id INT DEFAULT NULL, last_utilisateur_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, statut VARCHAR(255) NOT NULL, type_caisse VARCHAR(255) NOT NULL, INDEX IDX_41BE1F462886B1E4 (compte_operation_id), INDEX IDX_41BE1F468A8E563 (id_cpt_cv_devise), INDEX IDX_41BE1F46F2823676 (compte_intercaisse_id), INDEX IDX_41BE1F46E8526BE8 (compte_attente_compense_id), UNIQUE INDEX UNIQ_41BE1F4626720BA2 (last_journee_id), INDEX IDX_41BE1F46958FE8CB (last_utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Clients (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Comptes (id INT AUTO_INCREMENT NOT NULL, client INT NOT NULL, num_compte VARCHAR(50) NOT NULL, intitule VARCHAR(255) NOT NULL, solde_courant INT DEFAULT 0, type_compte VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_99CE619D76D705D8 (num_compte), INDEX IDX_99CE619DC7440455 (client), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE DetteCreditDivers (id INT AUTO_INCREMENT NOT NULL, journee_caisse_creation_id INT NOT NULL, journee_caisse_remb_id INT DEFAULT NULL, date_creation DATETIME NOT NULL, date_remboursement DATETIME DEFAULT NULL, libelle VARCHAR(255) NOT NULL, statut VARCHAR(255) NOT NULL, m_credit DOUBLE PRECISION NOT NULL, m_dette DOUBLE PRECISION NOT NULL, journeeCaisseActive INT NOT NULL, utilisateurCreat INT DEFAULT NULL, utilisateurRemb INT DEFAULT NULL, INDEX IDX_1C5147F2933A183 (journee_caisse_creation_id), INDEX IDX_1C5147F2666C2D03 (journee_caisse_remb_id), INDEX IDX_1C5147F2954C14F1 (journeeCaisseActive), INDEX IDX_1C5147F2E97147BA (utilisateurCreat), INDEX IDX_1C5147F2BD94A1E9 (utilisateurRemb), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE DeviseIntercaisses (id INT AUTO_INCREMENT NOT NULL, journee_caisse_source_id INT NOT NULL, journee_caisse_destination_id INT NOT NULL, date_intercaisse DATETIME NOT NULL, statut VARCHAR(255) NOT NULL, observations VARCHAR(255) DEFAULT NULL, INDEX IDX_D7381A146AE367D (journee_caisse_source_id), INDEX IDX_D7381A144F08E1DE (journee_caisse_destination_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE DeviseJournees (id INT AUTO_INCREMENT NOT NULL, devise_id INT NOT NULL, billet_ouv_id INT DEFAULT NULL, billet_ferm_id INT DEFAULT NULL, qte_ouv INT NOT NULL, ecart_ouv DOUBLE PRECISION NOT NULL, qte_achat INT NOT NULL, qte_vente INT NOT NULL, m_cvd_achat DOUBLE PRECISION NOT NULL, m_cvd_vente DOUBLE PRECISION NOT NULL, qte_intercaisse INT NOT NULL, qte_ferm INT NOT NULL, ecart_ferm INT NOT NULL, idJourneeCaisse INT NOT NULL, INDEX IDX_282BE5D27DA9E4F (idJourneeCaisse), INDEX IDX_282BE5D2F4445056 (devise_id), UNIQUE INDEX UNIQ_282BE5D2B1826C82 (billet_ouv_id), UNIQUE INDEX UNIQ_282BE5D2377E1C61 (billet_ferm_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE DeviseMouvements (id INT AUTO_INCREMENT NOT NULL, devise_journee_id INT NOT NULL, devise_recu_id INT DEFAULT NULL, devise_intercaisse_id INT DEFAULT NULL, devise_id INT NOT NULL, journee_caisse_id INT NOT NULL, nombre INT NOT NULL, taux DOUBLE PRECISION NOT NULL, INDEX IDX_F832A381FC328454 (devise_journee_id), INDEX IDX_F832A3818C999F1 (devise_recu_id), INDEX IDX_F832A38183F78888 (devise_intercaisse_id), INDEX IDX_F832A381F4445056 (devise_id), INDEX IDX_F832A381ED9240C0 (journee_caisse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE devise_recus (id INT AUTO_INCREMENT NOT NULL, pays_piece_id INT DEFAULT NULL, date_recu DATETIME NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, type_piece VARCHAR(50) DEFAULT NULL, num_piece VARCHAR(50) DEFAULT NULL, expire_le DATE DEFAULT NULL, motif VARCHAR(255) DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, sens VARCHAR(1) NOT NULL, journeeCaisse INT NOT NULL, INDEX IDX_22234A8392A0D6EA (pays_piece_id), INDEX IDX_22234A83CD3EC261 (journeeCaisse), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Devises (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, libelle VARCHAR(255) NOT NULL, date_modification DATETIME NOT NULL, tx_achat DOUBLE PRECISION NOT NULL, tx_vente DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE devise_tmp_mouvements (id INT AUTO_INCREMENT NOT NULL, devise_intercaisse_id INT DEFAULT NULL, devise_id INT NOT NULL, nombre INT NOT NULL, taux DOUBLE PRECISION NOT NULL, INDEX IDX_2C09D40883F78888 (devise_intercaisse_id), INDEX IDX_2C09D408F4445056 (devise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE InterCaisses (id INT AUTO_INCREMENT NOT NULL, journee_caisse_initiateur_id INT DEFAULT NULL, transaction_id INT DEFAULT NULL, recette_depense_id INT DEFAULT NULL, m_intercaisse DOUBLE PRECISION NOT NULL, statut VARCHAR(255) NOT NULL, observations VARCHAR(255) NOT NULL, journeeCaisseSortant INT NOT NULL, journeeCaisseEntrant INT NOT NULL, INDEX IDX_F4C41EC53B5438B (journee_caisse_initiateur_id), INDEX IDX_F4C41EC1F688F76 (journeeCaisseSortant), INDEX IDX_F4C41EC7A115F5B (journeeCaisseEntrant), INDEX IDX_F4C41EC2FC0CB0F (transaction_id), INDEX IDX_F4C41EC3DB72CBA (recette_depense_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE JourneeCaisses (id INT AUTO_INCREMENT NOT NULL, caisse_id INT DEFAULT NULL, utilisateur_id INT DEFAULT NULL, journee_precedente_id INT DEFAULT NULL, billet_ouv_id INT DEFAULT NULL, system_elect_invent_ouv_id INT DEFAULT NULL, billet_ferm_id INT DEFAULT NULL, system_elect_invent_ferm_id INT DEFAULT NULL, statut VARCHAR(255) NOT NULL, date_ouv DATETIME NOT NULL, date_comptable DATE DEFAULT NULL, m_liquidite_ouv BIGINT NOT NULL, m_solde_elect_ouv BIGINT NOT NULL, m_ecart_ouv BIGINT NOT NULL, date_ferm DATETIME DEFAULT NULL, m_liquidite_ferm BIGINT NOT NULL, m_solde_elect_ferm BIGINT NOT NULL, m_dette_divers_ouv BIGINT NOT NULL, m_credit_divers_ouv BIGINT NOT NULL, m_dette_divers_ferm BIGINT NOT NULL, m_credit_divers_ferm BIGINT NOT NULL, m_intercaisses BIGINT NOT NULL, m_emission_trans BIGINT NOT NULL, m_reception_trans BIGINT NOT NULL, m_cvd BIGINT NOT NULL, m_retrait_client BIGINT NOT NULL, m_depot_client BIGINT NOT NULL, m_ecart_ferm BIGINT NOT NULL, m_recette BIGINT NOT NULL, m_depense BIGINT NOT NULL, INDEX IDX_EC12D8DF27B4FEBF (caisse_id), INDEX IDX_EC12D8DFFB88E14F (utilisateur_id), UNIQUE INDEX UNIQ_EC12D8DF12DE51E0 (journee_precedente_id), UNIQUE INDEX UNIQ_EC12D8DFB1826C82 (billet_ouv_id), INDEX IDX_EC12D8DF2FFAA428 (system_elect_invent_ouv_id), UNIQUE INDEX UNIQ_EC12D8DF377E1C61 (billet_ferm_id), INDEX IDX_EC12D8DF1E32E5F (system_elect_invent_ferm_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ligne_salaires (id INT AUTO_INCREMENT NOT NULL, salaire_id INT DEFAULT NULL, compte_id INT DEFAULT NULL, m_salaire_base DOUBLE PRECISION NOT NULL, m_indem_logement DOUBLE PRECISION NOT NULL, m_indem_transport DOUBLE PRECISION NOT NULL, m_indem_fonction DOUBLE PRECISION NOT NULL, m_indem_autres DOUBLE PRECISION NOT NULL, m_sociale_salarie DOUBLE PRECISION NOT NULL, m_sociale_patronale DOUBLE PRECISION NOT NULL, m_impot_salarie DOUBLE PRECISION NOT NULL, m_taxe_patronale DOUBLE PRECISION NOT NULL, INDEX IDX_2E1F35722678C781 (salaire_id), INDEX IDX_2E1F3572F2C56620 (compte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ParamComptables (id INT AUTO_INCREMENT NOT NULL, id_cpt_intercaisse INT DEFAULT NULL, id_cpt_cvd INT DEFAULT NULL, id_cpt_compense INT DEFAULT NULL, compte_charge_base_salaire_id INT DEFAULT NULL, compte_charge_loge_salaire_id INT DEFAULT NULL, compte_charge_transp_salaire_id INT DEFAULT NULL, compte_charge_fonct_salaire_id INT DEFAULT NULL, compte_charge_indem_salaire_id INT DEFAULT NULL, compte_charge_coti_patronale_id INT DEFAULT NULL, compte_taxe_salaire_id INT DEFAULT NULL, compte_orga_social_id INT DEFAULT NULL, compte_orga_impot_salaire_id INT DEFAULT NULL, compte_orga_taxe_salaire_id INT DEFAULT NULL, compte_orga_taxe_fact_clt_id INT DEFAULT NULL, compte_orga_taxe_fact_fseur_id INT DEFAULT NULL, id_cpt_ecart INT DEFAULT NULL, id_cpt_charges INT DEFAULT NULL, id_cpt_produits INT DEFAULT NULL, code_structure VARCHAR(255) NOT NULL, INDEX IDX_DED38F081A661CCE (id_cpt_intercaisse), INDEX IDX_DED38F08149A7A04 (id_cpt_cvd), INDEX IDX_DED38F08B2EACDB5 (id_cpt_compense), INDEX IDX_DED38F085A6C77A0 (compte_charge_base_salaire_id), INDEX IDX_DED38F0883AF619 (compte_charge_loge_salaire_id), INDEX IDX_DED38F08C34758D1 (compte_charge_transp_salaire_id), INDEX IDX_DED38F08286A8BF9 (compte_charge_fonct_salaire_id), INDEX IDX_DED38F08FC18E500 (compte_charge_indem_salaire_id), INDEX IDX_DED38F0866CC6179 (compte_charge_coti_patronale_id), INDEX IDX_DED38F08FD5FA9C3 (compte_taxe_salaire_id), INDEX IDX_DED38F088D5E0397 (compte_orga_social_id), INDEX IDX_DED38F08A51C551A (compte_orga_impot_salaire_id), INDEX IDX_DED38F085BE3757A (compte_orga_taxe_salaire_id), INDEX IDX_DED38F08F04745FB (compte_orga_taxe_fact_clt_id), INDEX IDX_DED38F087B6CC449 (compte_orga_taxe_fact_fseur_id), INDEX IDX_DED38F08314865F4 (id_cpt_ecart), INDEX IDX_DED38F08CA20F56D (id_cpt_charges), INDEX IDX_DED38F0870BCF4B6 (id_cpt_produits), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Pays (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, zone VARCHAR(255) NOT NULL, dans_region TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recette_depenses (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, transaction_id INT DEFAULT NULL, journee_caisse_id INT NOT NULL, date_operation DATETIME NOT NULL, libelle VARCHAR(255) NOT NULL, m_recette DOUBLE PRECISION NOT NULL, m_depense DOUBLE PRECISION NOT NULL, statut VARCHAR(255) NOT NULL, est_comptant TINYINT(1) NOT NULL, INDEX IDX_5E21EE33FB88E14F (utilisateur_id), INDEX IDX_5E21EE332FC0CB0F (transaction_id), INDEX IDX_5E21EE33ED9240C0 (journee_caisse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Salaires (id INT AUTO_INCREMENT NOT NULL, transaction_id INT NOT NULL, periode_salaire DATE NOT NULL, m_charge_totale DOUBLE PRECISION NOT NULL, m_remuneration_totale DOUBLE PRECISION NOT NULL, INDEX IDX_88F346122FC0CB0F (transaction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE SystemElectInventaires (id INT AUTO_INCREMENT NOT NULL, date_inventaire DATETIME NOT NULL, solde_total DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE SystemElectLigneInventaires (id INT AUTO_INCREMENT NOT NULL, id_system_elect_inventaire_id INT NOT NULL, id_system_elect_id INT NOT NULL, solde DOUBLE PRECISION NOT NULL, INDEX IDX_E1A0E8C89D6788D (id_system_elect_inventaire_id), INDEX IDX_E1A0E8CCA7D9DB0 (id_system_elect_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE SystemElects (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE SystemTransfert (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE TransactionComptes (id INT AUTO_INCREMENT NOT NULL, num_compte VARCHAR(255) NOT NULL, m_debit INT DEFAULT NULL, m_credit INT DEFAULT NULL, IdCompte INT NOT NULL, IdTransaction INT NOT NULL, INDEX IDX_4BC64C6E559198AC (IdCompte), INDEX IDX_4BC64C6E8114B15C (IdTransaction), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Transactions (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, date_transaction DATETIME NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, idUtilisateur INT NOT NULL, idJourneeCaisse INT DEFAULT NULL, idUtilisateurLast INT DEFAULT NULL, INDEX IDX_F299C1B45D419CCB (idUtilisateur), INDEX IDX_F299C1B47DA9E4F (idJourneeCaisse), INDEX IDX_F299C1B443147D00 (idUtilisateurLast), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE TransfertInternationaux (id INT AUTO_INCREMENT NOT NULL, id_pays_id INT NOT NULL, sens VARCHAR(255) NOT NULL, m_transfert DOUBLE PRECISION NOT NULL, m_frais_ht DOUBLE PRECISION NOT NULL, m_tva DOUBLE PRECISION NOT NULL, m_autres_taxes DOUBLE PRECISION NOT NULL, m_transfert_ttc DOUBLE PRECISION NOT NULL, journeeCaisse INT NOT NULL, journeeCaisseEmi INT DEFAULT NULL, journeeCaisseRecu INT DEFAULT NULL, idSystemTransfert INT NOT NULL, INDEX IDX_CD12576ACD3EC261 (journeeCaisse), INDEX IDX_CD12576A1C955392 (journeeCaisseEmi), INDEX IDX_CD12576AD542DB11 (journeeCaisseRecu), INDEX IDX_CD12576AB24F04ED (idSystemTransfert), INDEX IDX_CD12576A7879EB34 (id_pays_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_operation_comptables (id INT AUTO_INCREMENT NOT NULL, compte_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, INDEX IDX_C0FB3AA4F2C56620 (compte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles VARCHAR(200) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Utilisateurs (id INT AUTO_INCREMENT NOT NULL, id_cpt_ecart INT DEFAULT NULL, journee_caisse_active_id INT DEFAULT NULL, last_caisse_id INT DEFAULT NULL, login VARCHAR(255) NOT NULL, mdp VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, est_caissier TINYINT(1) NOT NULL, status VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_514AEAA6314865F4 (id_cpt_ecart), INDEX IDX_514AEAA6B451A8DB (journee_caisse_active_id), INDEX IDX_514AEAA6307B21B9 (last_caisse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE BilletageLignes ADD CONSTRAINT FK_612BF20EF2B35750 FOREIGN KEY (billetages_id) REFERENCES billetages (id)');
        $this->addSql('ALTER TABLE BilletageLignes ADD CONSTRAINT FK_612BF20E44973C78 FOREIGN KEY (billet_id) REFERENCES billets (id)');
        $this->addSql('ALTER TABLE billets ADD CONSTRAINT FK_4FCF9B68F4445056 FOREIGN KEY (devise_id) REFERENCES Devises (id)');
        $this->addSql('ALTER TABLE Caisses ADD CONSTRAINT FK_41BE1F462886B1E4 FOREIGN KEY (compte_operation_id) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE Caisses ADD CONSTRAINT FK_41BE1F468A8E563 FOREIGN KEY (id_cpt_cv_devise) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE Caisses ADD CONSTRAINT FK_41BE1F46F2823676 FOREIGN KEY (compte_intercaisse_id) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE Caisses ADD CONSTRAINT FK_41BE1F46E8526BE8 FOREIGN KEY (compte_attente_compense_id) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE Caisses ADD CONSTRAINT FK_41BE1F4626720BA2 FOREIGN KEY (last_journee_id) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE Caisses ADD CONSTRAINT FK_41BE1F46958FE8CB FOREIGN KEY (last_utilisateur_id) REFERENCES Utilisateurs (id)');
        $this->addSql('ALTER TABLE Comptes ADD CONSTRAINT FK_99CE619DC7440455 FOREIGN KEY (client) REFERENCES Clients (id)');
        $this->addSql('ALTER TABLE DetteCreditDivers ADD CONSTRAINT FK_1C5147F2933A183 FOREIGN KEY (journee_caisse_creation_id) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE DetteCreditDivers ADD CONSTRAINT FK_1C5147F2666C2D03 FOREIGN KEY (journee_caisse_remb_id) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE DetteCreditDivers ADD CONSTRAINT FK_1C5147F2954C14F1 FOREIGN KEY (journeeCaisseActive) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE DetteCreditDivers ADD CONSTRAINT FK_1C5147F2E97147BA FOREIGN KEY (utilisateurCreat) REFERENCES Utilisateurs (id)');
        $this->addSql('ALTER TABLE DetteCreditDivers ADD CONSTRAINT FK_1C5147F2BD94A1E9 FOREIGN KEY (utilisateurRemb) REFERENCES Utilisateurs (id)');
        $this->addSql('ALTER TABLE DeviseIntercaisses ADD CONSTRAINT FK_D7381A146AE367D FOREIGN KEY (journee_caisse_source_id) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE DeviseIntercaisses ADD CONSTRAINT FK_D7381A144F08E1DE FOREIGN KEY (journee_caisse_destination_id) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE DeviseJournees ADD CONSTRAINT FK_282BE5D27DA9E4F FOREIGN KEY (idJourneeCaisse) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE DeviseJournees ADD CONSTRAINT FK_282BE5D2F4445056 FOREIGN KEY (devise_id) REFERENCES Devises (id)');
        $this->addSql('ALTER TABLE DeviseJournees ADD CONSTRAINT FK_282BE5D2B1826C82 FOREIGN KEY (billet_ouv_id) REFERENCES billetages (id)');
        $this->addSql('ALTER TABLE DeviseJournees ADD CONSTRAINT FK_282BE5D2377E1C61 FOREIGN KEY (billet_ferm_id) REFERENCES billetages (id)');
        $this->addSql('ALTER TABLE DeviseMouvements ADD CONSTRAINT FK_F832A381FC328454 FOREIGN KEY (devise_journee_id) REFERENCES DeviseJournees (id)');
        $this->addSql('ALTER TABLE DeviseMouvements ADD CONSTRAINT FK_F832A3818C999F1 FOREIGN KEY (devise_recu_id) REFERENCES devise_recus (id)');
        $this->addSql('ALTER TABLE DeviseMouvements ADD CONSTRAINT FK_F832A38183F78888 FOREIGN KEY (devise_intercaisse_id) REFERENCES DeviseIntercaisses (id)');
        $this->addSql('ALTER TABLE DeviseMouvements ADD CONSTRAINT FK_F832A381F4445056 FOREIGN KEY (devise_id) REFERENCES Devises (id)');
        $this->addSql('ALTER TABLE DeviseMouvements ADD CONSTRAINT FK_F832A381ED9240C0 FOREIGN KEY (journee_caisse_id) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE devise_recus ADD CONSTRAINT FK_22234A8392A0D6EA FOREIGN KEY (pays_piece_id) REFERENCES Pays (id)');
        $this->addSql('ALTER TABLE devise_recus ADD CONSTRAINT FK_22234A83CD3EC261 FOREIGN KEY (journeeCaisse) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE devise_tmp_mouvements ADD CONSTRAINT FK_2C09D40883F78888 FOREIGN KEY (devise_intercaisse_id) REFERENCES DeviseIntercaisses (id)');
        $this->addSql('ALTER TABLE devise_tmp_mouvements ADD CONSTRAINT FK_2C09D408F4445056 FOREIGN KEY (devise_id) REFERENCES Devises (id)');
        $this->addSql('ALTER TABLE InterCaisses ADD CONSTRAINT FK_F4C41EC53B5438B FOREIGN KEY (journee_caisse_initiateur_id) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE InterCaisses ADD CONSTRAINT FK_F4C41EC1F688F76 FOREIGN KEY (journeeCaisseSortant) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE InterCaisses ADD CONSTRAINT FK_F4C41EC7A115F5B FOREIGN KEY (journeeCaisseEntrant) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE InterCaisses ADD CONSTRAINT FK_F4C41EC2FC0CB0F FOREIGN KEY (transaction_id) REFERENCES Transactions (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE InterCaisses ADD CONSTRAINT FK_F4C41EC3DB72CBA FOREIGN KEY (recette_depense_id) REFERENCES recette_depenses (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE JourneeCaisses ADD CONSTRAINT FK_EC12D8DF27B4FEBF FOREIGN KEY (caisse_id) REFERENCES Caisses (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE JourneeCaisses ADD CONSTRAINT FK_EC12D8DFFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES Utilisateurs (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE JourneeCaisses ADD CONSTRAINT FK_EC12D8DF12DE51E0 FOREIGN KEY (journee_precedente_id) REFERENCES JourneeCaisses (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE JourneeCaisses ADD CONSTRAINT FK_EC12D8DFB1826C82 FOREIGN KEY (billet_ouv_id) REFERENCES billetages (id)');
        $this->addSql('ALTER TABLE JourneeCaisses ADD CONSTRAINT FK_EC12D8DF2FFAA428 FOREIGN KEY (system_elect_invent_ouv_id) REFERENCES SystemElectInventaires (id)');
        $this->addSql('ALTER TABLE JourneeCaisses ADD CONSTRAINT FK_EC12D8DF377E1C61 FOREIGN KEY (billet_ferm_id) REFERENCES billetages (id)');
        $this->addSql('ALTER TABLE JourneeCaisses ADD CONSTRAINT FK_EC12D8DF1E32E5F FOREIGN KEY (system_elect_invent_ferm_id) REFERENCES SystemElectInventaires (id)');
        $this->addSql('ALTER TABLE ligne_salaires ADD CONSTRAINT FK_2E1F35722678C781 FOREIGN KEY (salaire_id) REFERENCES Salaires (id)');
        $this->addSql('ALTER TABLE ligne_salaires ADD CONSTRAINT FK_2E1F3572F2C56620 FOREIGN KEY (compte_id) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE ParamComptables ADD CONSTRAINT FK_DED38F081A661CCE FOREIGN KEY (id_cpt_intercaisse) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE ParamComptables ADD CONSTRAINT FK_DED38F08149A7A04 FOREIGN KEY (id_cpt_cvd) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE ParamComptables ADD CONSTRAINT FK_DED38F08B2EACDB5 FOREIGN KEY (id_cpt_compense) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE ParamComptables ADD CONSTRAINT FK_DED38F085A6C77A0 FOREIGN KEY (compte_charge_base_salaire_id) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE ParamComptables ADD CONSTRAINT FK_DED38F0883AF619 FOREIGN KEY (compte_charge_loge_salaire_id) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE ParamComptables ADD CONSTRAINT FK_DED38F08C34758D1 FOREIGN KEY (compte_charge_transp_salaire_id) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE ParamComptables ADD CONSTRAINT FK_DED38F08286A8BF9 FOREIGN KEY (compte_charge_fonct_salaire_id) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE ParamComptables ADD CONSTRAINT FK_DED38F08FC18E500 FOREIGN KEY (compte_charge_indem_salaire_id) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE ParamComptables ADD CONSTRAINT FK_DED38F0866CC6179 FOREIGN KEY (compte_charge_coti_patronale_id) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE ParamComptables ADD CONSTRAINT FK_DED38F08FD5FA9C3 FOREIGN KEY (compte_taxe_salaire_id) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE ParamComptables ADD CONSTRAINT FK_DED38F088D5E0397 FOREIGN KEY (compte_orga_social_id) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE ParamComptables ADD CONSTRAINT FK_DED38F08A51C551A FOREIGN KEY (compte_orga_impot_salaire_id) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE ParamComptables ADD CONSTRAINT FK_DED38F085BE3757A FOREIGN KEY (compte_orga_taxe_salaire_id) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE ParamComptables ADD CONSTRAINT FK_DED38F08F04745FB FOREIGN KEY (compte_orga_taxe_fact_clt_id) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE ParamComptables ADD CONSTRAINT FK_DED38F087B6CC449 FOREIGN KEY (compte_orga_taxe_fact_fseur_id) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE ParamComptables ADD CONSTRAINT FK_DED38F08314865F4 FOREIGN KEY (id_cpt_ecart) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE ParamComptables ADD CONSTRAINT FK_DED38F08CA20F56D FOREIGN KEY (id_cpt_charges) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE ParamComptables ADD CONSTRAINT FK_DED38F0870BCF4B6 FOREIGN KEY (id_cpt_produits) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE recette_depenses ADD CONSTRAINT FK_5E21EE33FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES Utilisateurs (id)');
        $this->addSql('ALTER TABLE recette_depenses ADD CONSTRAINT FK_5E21EE332FC0CB0F FOREIGN KEY (transaction_id) REFERENCES Transactions (id)');
        $this->addSql('ALTER TABLE recette_depenses ADD CONSTRAINT FK_5E21EE33ED9240C0 FOREIGN KEY (journee_caisse_id) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE Salaires ADD CONSTRAINT FK_88F346122FC0CB0F FOREIGN KEY (transaction_id) REFERENCES Transactions (id)');
        $this->addSql('ALTER TABLE SystemElectLigneInventaires ADD CONSTRAINT FK_E1A0E8C89D6788D FOREIGN KEY (id_system_elect_inventaire_id) REFERENCES SystemElectInventaires (id)');
        $this->addSql('ALTER TABLE SystemElectLigneInventaires ADD CONSTRAINT FK_E1A0E8CCA7D9DB0 FOREIGN KEY (id_system_elect_id) REFERENCES SystemElects (id)');
        $this->addSql('ALTER TABLE TransactionComptes ADD CONSTRAINT FK_4BC64C6E559198AC FOREIGN KEY (IdCompte) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE TransactionComptes ADD CONSTRAINT FK_4BC64C6E8114B15C FOREIGN KEY (IdTransaction) REFERENCES Transactions (id)');
        $this->addSql('ALTER TABLE Transactions ADD CONSTRAINT FK_F299C1B45D419CCB FOREIGN KEY (idUtilisateur) REFERENCES Utilisateurs (id)');
        $this->addSql('ALTER TABLE Transactions ADD CONSTRAINT FK_F299C1B47DA9E4F FOREIGN KEY (idJourneeCaisse) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE Transactions ADD CONSTRAINT FK_F299C1B443147D00 FOREIGN KEY (idUtilisateurLast) REFERENCES Utilisateurs (id)');
        $this->addSql('ALTER TABLE TransfertInternationaux ADD CONSTRAINT FK_CD12576ACD3EC261 FOREIGN KEY (journeeCaisse) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE TransfertInternationaux ADD CONSTRAINT FK_CD12576A1C955392 FOREIGN KEY (journeeCaisseEmi) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE TransfertInternationaux ADD CONSTRAINT FK_CD12576AD542DB11 FOREIGN KEY (journeeCaisseRecu) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE TransfertInternationaux ADD CONSTRAINT FK_CD12576AB24F04ED FOREIGN KEY (idSystemTransfert) REFERENCES SystemTransfert (id)');
        $this->addSql('ALTER TABLE TransfertInternationaux ADD CONSTRAINT FK_CD12576A7879EB34 FOREIGN KEY (id_pays_id) REFERENCES Pays (id)');
        $this->addSql('ALTER TABLE type_operation_comptables ADD CONSTRAINT FK_C0FB3AA4F2C56620 FOREIGN KEY (compte_id) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE Utilisateurs ADD CONSTRAINT FK_514AEAA6314865F4 FOREIGN KEY (id_cpt_ecart) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE Utilisateurs ADD CONSTRAINT FK_514AEAA6B451A8DB FOREIGN KEY (journee_caisse_active_id) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE Utilisateurs ADD CONSTRAINT FK_514AEAA6307B21B9 FOREIGN KEY (last_caisse_id) REFERENCES Caisses (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE BilletageLignes DROP FOREIGN KEY FK_612BF20EF2B35750');
        $this->addSql('ALTER TABLE DeviseJournees DROP FOREIGN KEY FK_282BE5D2B1826C82');
        $this->addSql('ALTER TABLE DeviseJournees DROP FOREIGN KEY FK_282BE5D2377E1C61');
        $this->addSql('ALTER TABLE JourneeCaisses DROP FOREIGN KEY FK_EC12D8DFB1826C82');
        $this->addSql('ALTER TABLE JourneeCaisses DROP FOREIGN KEY FK_EC12D8DF377E1C61');
        $this->addSql('ALTER TABLE BilletageLignes DROP FOREIGN KEY FK_612BF20E44973C78');
        $this->addSql('ALTER TABLE JourneeCaisses DROP FOREIGN KEY FK_EC12D8DF27B4FEBF');
        $this->addSql('ALTER TABLE Utilisateurs DROP FOREIGN KEY FK_514AEAA6307B21B9');
        $this->addSql('ALTER TABLE Comptes DROP FOREIGN KEY FK_99CE619DC7440455');
        $this->addSql('ALTER TABLE Caisses DROP FOREIGN KEY FK_41BE1F462886B1E4');
        $this->addSql('ALTER TABLE Caisses DROP FOREIGN KEY FK_41BE1F468A8E563');
        $this->addSql('ALTER TABLE Caisses DROP FOREIGN KEY FK_41BE1F46F2823676');
        $this->addSql('ALTER TABLE Caisses DROP FOREIGN KEY FK_41BE1F46E8526BE8');
        $this->addSql('ALTER TABLE ligne_salaires DROP FOREIGN KEY FK_2E1F3572F2C56620');
        $this->addSql('ALTER TABLE ParamComptables DROP FOREIGN KEY FK_DED38F081A661CCE');
        $this->addSql('ALTER TABLE ParamComptables DROP FOREIGN KEY FK_DED38F08149A7A04');
        $this->addSql('ALTER TABLE ParamComptables DROP FOREIGN KEY FK_DED38F08B2EACDB5');
        $this->addSql('ALTER TABLE ParamComptables DROP FOREIGN KEY FK_DED38F085A6C77A0');
        $this->addSql('ALTER TABLE ParamComptables DROP FOREIGN KEY FK_DED38F0883AF619');
        $this->addSql('ALTER TABLE ParamComptables DROP FOREIGN KEY FK_DED38F08C34758D1');
        $this->addSql('ALTER TABLE ParamComptables DROP FOREIGN KEY FK_DED38F08286A8BF9');
        $this->addSql('ALTER TABLE ParamComptables DROP FOREIGN KEY FK_DED38F08FC18E500');
        $this->addSql('ALTER TABLE ParamComptables DROP FOREIGN KEY FK_DED38F0866CC6179');
        $this->addSql('ALTER TABLE ParamComptables DROP FOREIGN KEY FK_DED38F08FD5FA9C3');
        $this->addSql('ALTER TABLE ParamComptables DROP FOREIGN KEY FK_DED38F088D5E0397');
        $this->addSql('ALTER TABLE ParamComptables DROP FOREIGN KEY FK_DED38F08A51C551A');
        $this->addSql('ALTER TABLE ParamComptables DROP FOREIGN KEY FK_DED38F085BE3757A');
        $this->addSql('ALTER TABLE ParamComptables DROP FOREIGN KEY FK_DED38F08F04745FB');
        $this->addSql('ALTER TABLE ParamComptables DROP FOREIGN KEY FK_DED38F087B6CC449');
        $this->addSql('ALTER TABLE ParamComptables DROP FOREIGN KEY FK_DED38F08314865F4');
        $this->addSql('ALTER TABLE ParamComptables DROP FOREIGN KEY FK_DED38F08CA20F56D');
        $this->addSql('ALTER TABLE ParamComptables DROP FOREIGN KEY FK_DED38F0870BCF4B6');
        $this->addSql('ALTER TABLE TransactionComptes DROP FOREIGN KEY FK_4BC64C6E559198AC');
        $this->addSql('ALTER TABLE type_operation_comptables DROP FOREIGN KEY FK_C0FB3AA4F2C56620');
        $this->addSql('ALTER TABLE Utilisateurs DROP FOREIGN KEY FK_514AEAA6314865F4');
        $this->addSql('ALTER TABLE DeviseMouvements DROP FOREIGN KEY FK_F832A38183F78888');
        $this->addSql('ALTER TABLE devise_tmp_mouvements DROP FOREIGN KEY FK_2C09D40883F78888');
        $this->addSql('ALTER TABLE DeviseMouvements DROP FOREIGN KEY FK_F832A381FC328454');
        $this->addSql('ALTER TABLE DeviseMouvements DROP FOREIGN KEY FK_F832A3818C999F1');
        $this->addSql('ALTER TABLE billets DROP FOREIGN KEY FK_4FCF9B68F4445056');
        $this->addSql('ALTER TABLE DeviseJournees DROP FOREIGN KEY FK_282BE5D2F4445056');
        $this->addSql('ALTER TABLE DeviseMouvements DROP FOREIGN KEY FK_F832A381F4445056');
        $this->addSql('ALTER TABLE devise_tmp_mouvements DROP FOREIGN KEY FK_2C09D408F4445056');
        $this->addSql('ALTER TABLE Caisses DROP FOREIGN KEY FK_41BE1F4626720BA2');
        $this->addSql('ALTER TABLE DetteCreditDivers DROP FOREIGN KEY FK_1C5147F2933A183');
        $this->addSql('ALTER TABLE DetteCreditDivers DROP FOREIGN KEY FK_1C5147F2666C2D03');
        $this->addSql('ALTER TABLE DetteCreditDivers DROP FOREIGN KEY FK_1C5147F2954C14F1');
        $this->addSql('ALTER TABLE DeviseIntercaisses DROP FOREIGN KEY FK_D7381A146AE367D');
        $this->addSql('ALTER TABLE DeviseIntercaisses DROP FOREIGN KEY FK_D7381A144F08E1DE');
        $this->addSql('ALTER TABLE DeviseJournees DROP FOREIGN KEY FK_282BE5D27DA9E4F');
        $this->addSql('ALTER TABLE DeviseMouvements DROP FOREIGN KEY FK_F832A381ED9240C0');
        $this->addSql('ALTER TABLE devise_recus DROP FOREIGN KEY FK_22234A83CD3EC261');
        $this->addSql('ALTER TABLE InterCaisses DROP FOREIGN KEY FK_F4C41EC53B5438B');
        $this->addSql('ALTER TABLE InterCaisses DROP FOREIGN KEY FK_F4C41EC1F688F76');
        $this->addSql('ALTER TABLE InterCaisses DROP FOREIGN KEY FK_F4C41EC7A115F5B');
        $this->addSql('ALTER TABLE JourneeCaisses DROP FOREIGN KEY FK_EC12D8DF12DE51E0');
        $this->addSql('ALTER TABLE recette_depenses DROP FOREIGN KEY FK_5E21EE33ED9240C0');
        $this->addSql('ALTER TABLE Transactions DROP FOREIGN KEY FK_F299C1B47DA9E4F');
        $this->addSql('ALTER TABLE TransfertInternationaux DROP FOREIGN KEY FK_CD12576ACD3EC261');
        $this->addSql('ALTER TABLE TransfertInternationaux DROP FOREIGN KEY FK_CD12576A1C955392');
        $this->addSql('ALTER TABLE TransfertInternationaux DROP FOREIGN KEY FK_CD12576AD542DB11');
        $this->addSql('ALTER TABLE Utilisateurs DROP FOREIGN KEY FK_514AEAA6B451A8DB');
        $this->addSql('ALTER TABLE devise_recus DROP FOREIGN KEY FK_22234A8392A0D6EA');
        $this->addSql('ALTER TABLE TransfertInternationaux DROP FOREIGN KEY FK_CD12576A7879EB34');
        $this->addSql('ALTER TABLE InterCaisses DROP FOREIGN KEY FK_F4C41EC3DB72CBA');
        $this->addSql('ALTER TABLE ligne_salaires DROP FOREIGN KEY FK_2E1F35722678C781');
        $this->addSql('ALTER TABLE JourneeCaisses DROP FOREIGN KEY FK_EC12D8DF2FFAA428');
        $this->addSql('ALTER TABLE JourneeCaisses DROP FOREIGN KEY FK_EC12D8DF1E32E5F');
        $this->addSql('ALTER TABLE SystemElectLigneInventaires DROP FOREIGN KEY FK_E1A0E8C89D6788D');
        $this->addSql('ALTER TABLE SystemElectLigneInventaires DROP FOREIGN KEY FK_E1A0E8CCA7D9DB0');
        $this->addSql('ALTER TABLE TransfertInternationaux DROP FOREIGN KEY FK_CD12576AB24F04ED');
        $this->addSql('ALTER TABLE InterCaisses DROP FOREIGN KEY FK_F4C41EC2FC0CB0F');
        $this->addSql('ALTER TABLE recette_depenses DROP FOREIGN KEY FK_5E21EE332FC0CB0F');
        $this->addSql('ALTER TABLE Salaires DROP FOREIGN KEY FK_88F346122FC0CB0F');
        $this->addSql('ALTER TABLE TransactionComptes DROP FOREIGN KEY FK_4BC64C6E8114B15C');
        $this->addSql('ALTER TABLE Caisses DROP FOREIGN KEY FK_41BE1F46958FE8CB');
        $this->addSql('ALTER TABLE DetteCreditDivers DROP FOREIGN KEY FK_1C5147F2E97147BA');
        $this->addSql('ALTER TABLE DetteCreditDivers DROP FOREIGN KEY FK_1C5147F2BD94A1E9');
        $this->addSql('ALTER TABLE JourneeCaisses DROP FOREIGN KEY FK_EC12D8DFFB88E14F');
        $this->addSql('ALTER TABLE recette_depenses DROP FOREIGN KEY FK_5E21EE33FB88E14F');
        $this->addSql('ALTER TABLE Transactions DROP FOREIGN KEY FK_F299C1B45D419CCB');
        $this->addSql('ALTER TABLE Transactions DROP FOREIGN KEY FK_F299C1B443147D00');
        $this->addSql('DROP TABLE BilletageLignes');
        $this->addSql('DROP TABLE billetages');
        $this->addSql('DROP TABLE billets');
        $this->addSql('DROP TABLE Caisses');
        $this->addSql('DROP TABLE Clients');
        $this->addSql('DROP TABLE Comptes');
        $this->addSql('DROP TABLE DetteCreditDivers');
        $this->addSql('DROP TABLE DeviseIntercaisses');
        $this->addSql('DROP TABLE DeviseJournees');
        $this->addSql('DROP TABLE DeviseMouvements');
        $this->addSql('DROP TABLE devise_recus');
        $this->addSql('DROP TABLE Devises');
        $this->addSql('DROP TABLE devise_tmp_mouvements');
        $this->addSql('DROP TABLE InterCaisses');
        $this->addSql('DROP TABLE JourneeCaisses');
        $this->addSql('DROP TABLE ligne_salaires');
        $this->addSql('DROP TABLE ParamComptables');
        $this->addSql('DROP TABLE Pays');
        $this->addSql('DROP TABLE recette_depenses');
        $this->addSql('DROP TABLE Salaires');
        $this->addSql('DROP TABLE SystemElectInventaires');
        $this->addSql('DROP TABLE SystemElectLigneInventaires');
        $this->addSql('DROP TABLE SystemElects');
        $this->addSql('DROP TABLE SystemTransfert');
        $this->addSql('DROP TABLE TransactionComptes');
        $this->addSql('DROP TABLE Transactions');
        $this->addSql('DROP TABLE TransfertInternationaux');
        $this->addSql('DROP TABLE type_operation_comptables');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE Utilisateurs');
    }
}
