<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180703142856 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE test (id INT AUTO_INCREMENT NOT NULL, valeur DOUBLE PRECISION NOT NULL, champ_test DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE journeecaisses ADD id_billet_ferm_id INT NOT NULL, DROP id_billet_ferm, CHANGE id_journee_suivante_id id_journee_suivante_id INT DEFAULT NULL, CHANGE valeur_billet_ferm valeur_billet_ferm INT NOT NULL, CHANGE solde_elect_ferm solde_elect_ferm INT NOT NULL, CHANGE m_ecart_ferm m_ecart_ferm INT NOT NULL');
        $this->addSql('ALTER TABLE journeecaisses ADD CONSTRAINT FK_EC12D8DFA18A167 FOREIGN KEY (id_billet_ferm_id) REFERENCES billetages (id)');
        $this->addSql('CREATE INDEX IDX_EC12D8DFA18A167 ON journeecaisses (id_billet_ferm_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE test');
        $this->addSql('ALTER TABLE JourneeCaisses DROP FOREIGN KEY FK_EC12D8DFA18A167');
        $this->addSql('DROP INDEX IDX_EC12D8DFA18A167 ON JourneeCaisses');
        $this->addSql('ALTER TABLE JourneeCaisses ADD id_billet_ferm DATETIME NOT NULL, DROP id_billet_ferm_id, CHANGE id_journee_suivante_id id_journee_suivante_id INT NOT NULL, CHANGE valeur_billet_ferm valeur_billet_ferm DATETIME NOT NULL, CHANGE solde_elect_ferm solde_elect_ferm DATETIME NOT NULL, CHANGE m_ecart_ferm m_ecart_ferm DATETIME NOT NULL');
    }
}
