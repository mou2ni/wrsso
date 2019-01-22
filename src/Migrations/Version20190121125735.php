<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190121125735 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ligne_salaires (id INT AUTO_INCREMENT NOT NULL, salaire_id INT DEFAULT NULL, compte_id INT DEFAULT NULL, m_salaire_net DOUBLE PRECISION NOT NULL, INDEX IDX_2E1F35722678C781 (salaire_id), INDEX IDX_2E1F3572F2C56620 (compte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recette_depenses (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, type_operation_comptable_id INT NOT NULL, transaction_id INT DEFAULT NULL, journee_caisse_id INT NOT NULL, date_operation DATETIME NOT NULL, libelle VARCHAR(255) NOT NULL, m_recette DOUBLE PRECISION NOT NULL, m_depense DOUBLE PRECISION NOT NULL, statut VARCHAR(255) NOT NULL, INDEX IDX_5E21EE33FB88E14F (utilisateur_id), INDEX IDX_5E21EE338209B870 (type_operation_comptable_id), INDEX IDX_5E21EE332FC0CB0F (transaction_id), INDEX IDX_5E21EE33ED9240C0 (journee_caisse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Salaires (id INT AUTO_INCREMENT NOT NULL, transaction_id INT NOT NULL, periode_salaire DATE NOT NULL, m_salaire_net_total DOUBLE PRECISION NOT NULL, INDEX IDX_88F346122FC0CB0F (transaction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_operation_comptables (id INT AUTO_INCREMENT NOT NULL, compte_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, INDEX IDX_C0FB3AA4F2C56620 (compte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ligne_salaires ADD CONSTRAINT FK_2E1F35722678C781 FOREIGN KEY (salaire_id) REFERENCES Salaires (id)');
        $this->addSql('ALTER TABLE ligne_salaires ADD CONSTRAINT FK_2E1F3572F2C56620 FOREIGN KEY (compte_id) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE recette_depenses ADD CONSTRAINT FK_5E21EE33FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES Utilisateurs (id)');
        $this->addSql('ALTER TABLE recette_depenses ADD CONSTRAINT FK_5E21EE338209B870 FOREIGN KEY (type_operation_comptable_id) REFERENCES type_operation_comptables (id)');
        $this->addSql('ALTER TABLE recette_depenses ADD CONSTRAINT FK_5E21EE332FC0CB0F FOREIGN KEY (transaction_id) REFERENCES Transactions (id)');
        $this->addSql('ALTER TABLE recette_depenses ADD CONSTRAINT FK_5E21EE33ED9240C0 FOREIGN KEY (journee_caisse_id) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE Salaires ADD CONSTRAINT FK_88F346122FC0CB0F FOREIGN KEY (transaction_id) REFERENCES Transactions (id)');
        $this->addSql('ALTER TABLE type_operation_comptables ADD CONSTRAINT FK_C0FB3AA4F2C56620 FOREIGN KEY (compte_id) REFERENCES Comptes (id)');
        $this->addSql('DROP TABLE recettedepenses');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ligne_salaires DROP FOREIGN KEY FK_2E1F35722678C781');
        $this->addSql('ALTER TABLE recette_depenses DROP FOREIGN KEY FK_5E21EE338209B870');
        $this->addSql('CREATE TABLE recettedepenses (id INT AUTO_INCREMENT NOT NULL, id_utilisateur_id INT NOT NULL, id_trans_id INT NOT NULL, date_operation DATETIME NOT NULL, m_recette DOUBLE PRECISION NOT NULL, libelle VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, m_depense DOUBLE PRECISION NOT NULL, statut VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, INDEX IDX_2FB66904C6EE5C49 (id_utilisateur_id), INDEX IDX_2FB6690463346B17 (id_trans_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE recettedepenses ADD CONSTRAINT FK_2FB6690463346B17 FOREIGN KEY (id_trans_id) REFERENCES comptes (id)');
        $this->addSql('ALTER TABLE recettedepenses ADD CONSTRAINT FK_2FB66904C6EE5C49 FOREIGN KEY (id_utilisateur_id) REFERENCES utilisateurs (id)');
        $this->addSql('DROP TABLE ligne_salaires');
        $this->addSql('DROP TABLE recette_depenses');
        $this->addSql('DROP TABLE Salaires');
        $this->addSql('DROP TABLE type_operation_comptables');
    }
}
