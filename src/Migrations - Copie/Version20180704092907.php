<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180704092907 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE journeecaisses CHANGE id_system_elect_invent_ferm_id id_system_elect_invent_ferm_id INT DEFAULT NULL, CHANGE id_billet_ferm_id id_billet_ferm_id INT DEFAULT NULL, CHANGE id_journee_suivante_id id_journee_suivante_id INT NOT NULL, CHANGE statut statut VARCHAR(255) NOT NULL, CHANGE valeur_billet valeur_billet BIGINT NOT NULL, CHANGE solde_elect_ouv solde_elect_ouv BIGINT NOT NULL, CHANGE ecart_ouv ecart_ouv BIGINT NOT NULL, CHANGE m_cvd m_cvd BIGINT NOT NULL, CHANGE m_emission_trans m_emission_trans BIGINT NOT NULL, CHANGE m_reception_trans m_reception_trans BIGINT NOT NULL, CHANGE m_intercaisse m_intercaisse BIGINT NOT NULL, CHANGE m_retrait_client m_retrait_client BIGINT NOT NULL, CHANGE m_depot_client m_depot_client BIGINT NOT NULL, CHANGE m_credit_divers m_credit_divers BIGINT NOT NULL, CHANGE m_dette_divers m_dette_divers BIGINT NOT NULL, CHANGE valeur_billet_ferm valeur_billet_ferm BIGINT NOT NULL, CHANGE solde_elect_ferm solde_elect_ferm BIGINT NOT NULL, CHANGE m_ecart_ferm m_ecart_ferm BIGINT NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE JourneeCaisses CHANGE id_journee_suivante_id id_journee_suivante_id INT DEFAULT NULL, CHANGE id_billet_ferm_id id_billet_ferm_id INT NOT NULL, CHANGE id_system_elect_invent_ferm_id id_system_elect_invent_ferm_id INT NOT NULL, CHANGE statut statut DOUBLE PRECISION NOT NULL, CHANGE valeur_billet valeur_billet DOUBLE PRECISION NOT NULL, CHANGE solde_elect_ouv solde_elect_ouv DOUBLE PRECISION NOT NULL, CHANGE ecart_ouv ecart_ouv DOUBLE PRECISION NOT NULL, CHANGE m_cvd m_cvd DOUBLE PRECISION NOT NULL, CHANGE m_emission_trans m_emission_trans DOUBLE PRECISION NOT NULL, CHANGE m_reception_trans m_reception_trans DOUBLE PRECISION NOT NULL, CHANGE m_intercaisse m_intercaisse DOUBLE PRECISION NOT NULL, CHANGE m_retrait_client m_retrait_client DOUBLE PRECISION NOT NULL, CHANGE m_depot_client m_depot_client DOUBLE PRECISION NOT NULL, CHANGE m_credit_divers m_credit_divers DOUBLE PRECISION NOT NULL, CHANGE m_dette_divers m_dette_divers DOUBLE PRECISION NOT NULL, CHANGE valeur_billet_ferm valeur_billet_ferm INT NOT NULL, CHANGE solde_elect_ferm solde_elect_ferm INT NOT NULL, CHANGE m_ecart_ferm m_ecart_ferm INT NOT NULL');
    }
}
