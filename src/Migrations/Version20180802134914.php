<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180802134914 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE devisemouvements ADD devise_intercaisse_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE devisemouvements ADD CONSTRAINT FK_F832A38183F78888 FOREIGN KEY (devise_intercaisse_id) REFERENCES DeviseIntercaisses (id)');
        $this->addSql('CREATE INDEX IDX_F832A38183F78888 ON devisemouvements (devise_intercaisse_id)');
        $this->addSql('ALTER TABLE deviseintercaisses CHANGE m_intercaisse qte_intercaisse DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE journeecaisses CHANGE m_cvd m_cvd DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE DeviseIntercaisses CHANGE qte_intercaisse m_intercaisse DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE DeviseMouvements DROP FOREIGN KEY FK_F832A38183F78888');
        $this->addSql('DROP INDEX IDX_F832A38183F78888 ON DeviseMouvements');
        $this->addSql('ALTER TABLE DeviseMouvements DROP devise_intercaisse_id');
        $this->addSql('ALTER TABLE JourneeCaisses CHANGE m_cvd m_cvd BIGINT NOT NULL');
    }
}
