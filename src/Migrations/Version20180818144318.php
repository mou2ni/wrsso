<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180818144318 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE journeecaisses ADD m_liquidite_ouv BIGINT NOT NULL, ADD m_solde_elect_ouv BIGINT NOT NULL, ADD m_liquidite_ferm BIGINT NOT NULL, ADD m_solde_elect_ferm BIGINT NOT NULL, ADD m_intercaisses BIGINT NOT NULL, DROP valeur_billet, DROP solde_elect_ouv, DROP ecart_ouv, DROP m_intercaisse, DROP valeur_billet_ferm, DROP solde_elect_ferm, DROP m_ecart_ferm, CHANGE m_cvd m_cvd BIGINT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE JourneeCaisses ADD valeur_billet BIGINT NOT NULL, ADD solde_elect_ouv BIGINT NOT NULL, ADD ecart_ouv BIGINT NOT NULL, ADD m_intercaisse BIGINT NOT NULL, ADD valeur_billet_ferm BIGINT NOT NULL, ADD solde_elect_ferm BIGINT NOT NULL, ADD m_ecart_ferm BIGINT NOT NULL, DROP m_liquidite_ouv, DROP m_solde_elect_ouv, DROP m_liquidite_ferm, DROP m_solde_elect_ferm, DROP m_intercaisses, CHANGE m_cvd m_cvd DOUBLE PRECISION NOT NULL');
    }
}
