<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180911141307 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE billetages DROP FOREIGN KEY FK_46E532E0ED9240C0');
        $this->addSql('DROP INDEX UNIQ_46E532E0ED9240C0 ON billetages');
        $this->addSql('ALTER TABLE billetages DROP journee_caisse_id, DROP update_at');
        $this->addSql('ALTER TABLE devisejournees DROP INDEX IDX_282BE5D2B1826C82, ADD UNIQUE INDEX UNIQ_282BE5D2B1826C82 (billet_ouv_id)');
        $this->addSql('ALTER TABLE devisejournees DROP INDEX IDX_282BE5D2377E1C61, ADD UNIQUE INDEX UNIQ_282BE5D2377E1C61 (billet_ferm_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE billetages ADD journee_caisse_id INT DEFAULT NULL, ADD update_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE billetages ADD CONSTRAINT FK_46E532E0ED9240C0 FOREIGN KEY (journee_caisse_id) REFERENCES journeecaisses (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_46E532E0ED9240C0 ON billetages (journee_caisse_id)');
        $this->addSql('ALTER TABLE DeviseJournees DROP INDEX UNIQ_282BE5D2B1826C82, ADD INDEX IDX_282BE5D2B1826C82 (billet_ouv_id)');
        $this->addSql('ALTER TABLE DeviseJournees DROP INDEX UNIQ_282BE5D2377E1C61, ADD INDEX IDX_282BE5D2377E1C61 (billet_ferm_id)');
    }
}
