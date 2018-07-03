<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180703165546 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comptes DROP FOREIGN KEY FK_99CE619D99DED506');
        $this->addSql('DROP INDEX IDX_99CE619D99DED506 ON comptes');
        $this->addSql('ALTER TABLE comptes CHANGE id_client_id IdClient INT NOT NULL');
        $this->addSql('ALTER TABLE comptes ADD CONSTRAINT FK_99CE619D5D23CE99 FOREIGN KEY (IdClient) REFERENCES Clients (id)');
        $this->addSql('CREATE INDEX IDX_99CE619D5D23CE99 ON comptes (IdClient)');
        $this->addSql('ALTER TABLE paramcomptables DROP FOREIGN KEY FK_DED38F082335A176');
        $this->addSql('ALTER TABLE paramcomptables DROP FOREIGN KEY FK_DED38F08B26A9750');
        $this->addSql('ALTER TABLE paramcomptables DROP FOREIGN KEY FK_DED38F08CF8DE125');
        $this->addSql('ALTER TABLE paramcomptables DROP FOREIGN KEY FK_DED38F08ECE85DD4');
        $this->addSql('DROP INDEX IDX_DED38F082335A176 ON paramcomptables');
        $this->addSql('DROP INDEX IDX_DED38F08B26A9750 ON paramcomptables');
        $this->addSql('DROP INDEX IDX_DED38F08ECE85DD4 ON paramcomptables');
        $this->addSql('DROP INDEX IDX_DED38F08CF8DE125 ON paramcomptables');
        $this->addSql('ALTER TABLE paramcomptables ADD id_cpt_intercaisse INT DEFAULT NULL, ADD id_cpt_cvd INT DEFAULT NULL, ADD id_cpt_compense INT DEFAULT NULL, ADD id_cpt_chrg_salaire INT DEFAULT NULL, ADD id_cpt_ecart INT DEFAULT NULL, DROP id_compte_intercaisse_id, DROP id_compte_contre_valeur_devise_id, DROP id_compte_charge_salaire_net_id, DROP id_compte_compense_id');
        $this->addSql('ALTER TABLE paramcomptables ADD CONSTRAINT FK_DED38F081A661CCE FOREIGN KEY (id_cpt_intercaisse) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE paramcomptables ADD CONSTRAINT FK_DED38F08149A7A04 FOREIGN KEY (id_cpt_cvd) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE paramcomptables ADD CONSTRAINT FK_DED38F08B2EACDB5 FOREIGN KEY (id_cpt_compense) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE paramcomptables ADD CONSTRAINT FK_DED38F089119D00F FOREIGN KEY (id_cpt_chrg_salaire) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE paramcomptables ADD CONSTRAINT FK_DED38F08314865F4 FOREIGN KEY (id_cpt_ecart) REFERENCES Comptes (id)');
        $this->addSql('CREATE INDEX IDX_DED38F081A661CCE ON paramcomptables (id_cpt_intercaisse)');
        $this->addSql('CREATE INDEX IDX_DED38F08149A7A04 ON paramcomptables (id_cpt_cvd)');
        $this->addSql('CREATE INDEX IDX_DED38F08B2EACDB5 ON paramcomptables (id_cpt_compense)');
        $this->addSql('CREATE INDEX IDX_DED38F089119D00F ON paramcomptables (id_cpt_chrg_salaire)');
        $this->addSql('CREATE INDEX IDX_DED38F08314865F4 ON paramcomptables (id_cpt_ecart)');
        $this->addSql('ALTER TABLE transactioncomptes DROP FOREIGN KEY FK_4BC64C6E63346B17');
        $this->addSql('ALTER TABLE transactioncomptes DROP FOREIGN KEY FK_4BC64C6E72F0DA07');
        $this->addSql('DROP INDEX IDX_4BC64C6E63346B17 ON transactioncomptes');
        $this->addSql('DROP INDEX IDX_4BC64C6E72F0DA07 ON transactioncomptes');
        $this->addSql('ALTER TABLE transactioncomptes ADD IdCompte INT NOT NULL, ADD IdTransaction INT NOT NULL, DROP id_trans_id, DROP id_compte_id');
        $this->addSql('ALTER TABLE transactioncomptes ADD CONSTRAINT FK_4BC64C6E559198AC FOREIGN KEY (IdCompte) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE transactioncomptes ADD CONSTRAINT FK_4BC64C6E8114B15C FOREIGN KEY (IdTransaction) REFERENCES TransactionComptes (id)');
        $this->addSql('CREATE INDEX IDX_4BC64C6E559198AC ON transactioncomptes (IdCompte)');
        $this->addSql('CREATE INDEX IDX_4BC64C6E8114B15C ON transactioncomptes (IdTransaction)');
        $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_F299C1B4C6EE5C49');
        $this->addSql('DROP INDEX IDX_F299C1B4C6EE5C49 ON transactions');
        $this->addSql('ALTER TABLE transactions ADD created_at DATETIME DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL, ADD idUtilisateurLast INT NOT NULL, CHANGE id_utilisateur_id idUtilisateur INT NOT NULL');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_F299C1B45D419CCB FOREIGN KEY (idUtilisateur) REFERENCES Utilisateurs (id)');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_F299C1B443147D00 FOREIGN KEY (idUtilisateurLast) REFERENCES Utilisateurs (id)');
        $this->addSql('CREATE INDEX IDX_F299C1B45D419CCB ON transactions (idUtilisateur)');
        $this->addSql('CREATE INDEX IDX_F299C1B443147D00 ON transactions (idUtilisateurLast)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Comptes DROP FOREIGN KEY FK_99CE619D5D23CE99');
        $this->addSql('DROP INDEX IDX_99CE619D5D23CE99 ON Comptes');
        $this->addSql('ALTER TABLE Comptes CHANGE idclient id_client_id INT NOT NULL');
        $this->addSql('ALTER TABLE Comptes ADD CONSTRAINT FK_99CE619D99DED506 FOREIGN KEY (id_client_id) REFERENCES clients (id)');
        $this->addSql('CREATE INDEX IDX_99CE619D99DED506 ON Comptes (id_client_id)');
        $this->addSql('ALTER TABLE ParamComptables DROP FOREIGN KEY FK_DED38F081A661CCE');
        $this->addSql('ALTER TABLE ParamComptables DROP FOREIGN KEY FK_DED38F08149A7A04');
        $this->addSql('ALTER TABLE ParamComptables DROP FOREIGN KEY FK_DED38F08B2EACDB5');
        $this->addSql('ALTER TABLE ParamComptables DROP FOREIGN KEY FK_DED38F089119D00F');
        $this->addSql('ALTER TABLE ParamComptables DROP FOREIGN KEY FK_DED38F08314865F4');
        $this->addSql('DROP INDEX IDX_DED38F081A661CCE ON ParamComptables');
        $this->addSql('DROP INDEX IDX_DED38F08149A7A04 ON ParamComptables');
        $this->addSql('DROP INDEX IDX_DED38F08B2EACDB5 ON ParamComptables');
        $this->addSql('DROP INDEX IDX_DED38F089119D00F ON ParamComptables');
        $this->addSql('DROP INDEX IDX_DED38F08314865F4 ON ParamComptables');
        $this->addSql('ALTER TABLE ParamComptables ADD id_compte_intercaisse_id INT NOT NULL, ADD id_compte_contre_valeur_devise_id INT NOT NULL, ADD id_compte_charge_salaire_net_id INT NOT NULL, ADD id_compte_compense_id INT NOT NULL, DROP id_cpt_intercaisse, DROP id_cpt_cvd, DROP id_cpt_compense, DROP id_cpt_chrg_salaire, DROP id_cpt_ecart');
        $this->addSql('ALTER TABLE ParamComptables ADD CONSTRAINT FK_DED38F082335A176 FOREIGN KEY (id_compte_intercaisse_id) REFERENCES comptes (id)');
        $this->addSql('ALTER TABLE ParamComptables ADD CONSTRAINT FK_DED38F08B26A9750 FOREIGN KEY (id_compte_contre_valeur_devise_id) REFERENCES comptes (id)');
        $this->addSql('ALTER TABLE ParamComptables ADD CONSTRAINT FK_DED38F08CF8DE125 FOREIGN KEY (id_compte_charge_salaire_net_id) REFERENCES comptes (id)');
        $this->addSql('ALTER TABLE ParamComptables ADD CONSTRAINT FK_DED38F08ECE85DD4 FOREIGN KEY (id_compte_compense_id) REFERENCES comptes (id)');
        $this->addSql('CREATE INDEX IDX_DED38F082335A176 ON ParamComptables (id_compte_intercaisse_id)');
        $this->addSql('CREATE INDEX IDX_DED38F08B26A9750 ON ParamComptables (id_compte_contre_valeur_devise_id)');
        $this->addSql('CREATE INDEX IDX_DED38F08ECE85DD4 ON ParamComptables (id_compte_compense_id)');
        $this->addSql('CREATE INDEX IDX_DED38F08CF8DE125 ON ParamComptables (id_compte_charge_salaire_net_id)');
        $this->addSql('ALTER TABLE TransactionComptes DROP FOREIGN KEY FK_4BC64C6E559198AC');
        $this->addSql('ALTER TABLE TransactionComptes DROP FOREIGN KEY FK_4BC64C6E8114B15C');
        $this->addSql('DROP INDEX IDX_4BC64C6E559198AC ON TransactionComptes');
        $this->addSql('DROP INDEX IDX_4BC64C6E8114B15C ON TransactionComptes');
        $this->addSql('ALTER TABLE TransactionComptes ADD id_trans_id INT NOT NULL, ADD id_compte_id INT NOT NULL, DROP IdCompte, DROP IdTransaction');
        $this->addSql('ALTER TABLE TransactionComptes ADD CONSTRAINT FK_4BC64C6E63346B17 FOREIGN KEY (id_trans_id) REFERENCES transactions (id)');
        $this->addSql('ALTER TABLE TransactionComptes ADD CONSTRAINT FK_4BC64C6E72F0DA07 FOREIGN KEY (id_compte_id) REFERENCES comptes (id)');
        $this->addSql('CREATE INDEX IDX_4BC64C6E63346B17 ON TransactionComptes (id_trans_id)');
        $this->addSql('CREATE INDEX IDX_4BC64C6E72F0DA07 ON TransactionComptes (id_compte_id)');
        $this->addSql('ALTER TABLE Transactions DROP FOREIGN KEY FK_F299C1B45D419CCB');
        $this->addSql('ALTER TABLE Transactions DROP FOREIGN KEY FK_F299C1B443147D00');
        $this->addSql('DROP INDEX IDX_F299C1B45D419CCB ON Transactions');
        $this->addSql('DROP INDEX IDX_F299C1B443147D00 ON Transactions');
        $this->addSql('ALTER TABLE Transactions ADD id_utilisateur_id INT NOT NULL, DROP created_at, DROP updated_at, DROP idUtilisateur, DROP idUtilisateurLast');
        $this->addSql('ALTER TABLE Transactions ADD CONSTRAINT FK_F299C1B4C6EE5C49 FOREIGN KEY (id_utilisateur_id) REFERENCES utilisateurs (id)');
        $this->addSql('CREATE INDEX IDX_F299C1B4C6EE5C49 ON Transactions (id_utilisateur_id)');
    }
}
