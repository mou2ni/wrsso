<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180725121359 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE devisemouvements DROP FOREIGN KEY FK_F832A3811CA528D7');
        $this->addSql('ALTER TABLE devisemouvements DROP FOREIGN KEY FK_F832A3817471EC71');
        $this->addSql('DROP INDEX IDX_F832A3811CA528D7 ON devisemouvements');
        $this->addSql('DROP INDEX IDX_F832A3817471EC71 ON devisemouvements');
        $this->addSql('ALTER TABLE devisemouvements ADD devise_journee_id INT NOT NULL, ADD devise_recu_id INT DEFAULT NULL, ADD m_cvd_achat DOUBLE PRECISION NOT NULL, ADD m_cvd_vente DOUBLE PRECISION NOT NULL, DROP id_journee_caisse_id, DROP id_devise_id, DROP sens, DROP m_cvd');
        $this->addSql('ALTER TABLE devisemouvements ADD CONSTRAINT FK_F832A381FC328454 FOREIGN KEY (devise_journee_id) REFERENCES DeviseJournees (id)');
        $this->addSql('ALTER TABLE devisemouvements ADD CONSTRAINT FK_F832A3818C999F1 FOREIGN KEY (devise_recu_id) REFERENCES devise_recus (id)');
        $this->addSql('CREATE INDEX IDX_F832A381FC328454 ON devisemouvements (devise_journee_id)');
        $this->addSql('CREATE INDEX IDX_F832A3818C999F1 ON devisemouvements (devise_recu_id)');
        $this->addSql('ALTER TABLE devise_recus ADD pays_piece VARCHAR(50) DEFAULT NULL, CHANGE numero_piece num_piece VARCHAR(50) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE devise_recus ADD numero_piece VARCHAR(50) DEFAULT NULL COLLATE utf8mb4_unicode_ci, DROP num_piece, DROP pays_piece');
        $this->addSql('ALTER TABLE DeviseMouvements DROP FOREIGN KEY FK_F832A381FC328454');
        $this->addSql('ALTER TABLE DeviseMouvements DROP FOREIGN KEY FK_F832A3818C999F1');
        $this->addSql('DROP INDEX IDX_F832A381FC328454 ON DeviseMouvements');
        $this->addSql('DROP INDEX IDX_F832A3818C999F1 ON DeviseMouvements');
        $this->addSql('ALTER TABLE DeviseMouvements ADD id_devise_id INT NOT NULL, ADD sens VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD m_cvd INT NOT NULL, DROP devise_recu_id, DROP m_cvd_achat, DROP m_cvd_vente, CHANGE devise_journee_id id_journee_caisse_id INT NOT NULL');
        $this->addSql('ALTER TABLE DeviseMouvements ADD CONSTRAINT FK_F832A3811CA528D7 FOREIGN KEY (id_journee_caisse_id) REFERENCES journeecaisses (id)');
        $this->addSql('ALTER TABLE DeviseMouvements ADD CONSTRAINT FK_F832A3817471EC71 FOREIGN KEY (id_devise_id) REFERENCES devises (id)');
        $this->addSql('CREATE INDEX IDX_F832A3811CA528D7 ON DeviseMouvements (id_journee_caisse_id)');
        $this->addSql('CREATE INDEX IDX_F832A3817471EC71 ON DeviseMouvements (id_devise_id)');
    }
}
