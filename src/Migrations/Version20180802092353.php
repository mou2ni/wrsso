<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180802092353 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE devisemouvements DROP m_cvd_achat, DROP m_cvd_vente');
        $this->addSql('ALTER TABLE devisejournees ADD qte_mvt INT NOT NULL, ADD m_cvd_mvt DOUBLE PRECISION NOT NULL, DROP qte_achat, DROP qte_vente, DROP m_cvd_achat, DROP m_cvd_vente');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE DeviseJournees ADD qte_vente INT NOT NULL, ADD m_cvd_vente DOUBLE PRECISION NOT NULL, CHANGE qte_mvt qte_achat INT NOT NULL, CHANGE m_cvd_mvt m_cvd_achat DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE DeviseMouvements ADD m_cvd_achat DOUBLE PRECISION NOT NULL, ADD m_cvd_vente DOUBLE PRECISION NOT NULL');
    }
}
