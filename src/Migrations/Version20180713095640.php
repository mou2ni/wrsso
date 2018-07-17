<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180713095640 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE devisejournees DROP FOREIGN KEY FK_282BE5D2A18A167');
        $this->addSql('ALTER TABLE devisejournees DROP FOREIGN KEY FK_282BE5D2FAABDE7');
        $this->addSql('ALTER TABLE devisejournees ADD CONSTRAINT FK_282BE5D2A18A167 FOREIGN KEY (id_billet_ferm_id) REFERENCES billetages (id)');
        $this->addSql('ALTER TABLE devisejournees ADD CONSTRAINT FK_282BE5D2FAABDE7 FOREIGN KEY (id_billet_ouv_id) REFERENCES billetages (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE DeviseJournees DROP FOREIGN KEY FK_282BE5D2FAABDE7');
        $this->addSql('ALTER TABLE DeviseJournees DROP FOREIGN KEY FK_282BE5D2A18A167');
        $this->addSql('ALTER TABLE DeviseJournees ADD CONSTRAINT FK_282BE5D2FAABDE7 FOREIGN KEY (id_billet_ouv_id) REFERENCES billetagelignes (id)');
        $this->addSql('ALTER TABLE DeviseJournees ADD CONSTRAINT FK_282BE5D2A18A167 FOREIGN KEY (id_billet_ferm_id) REFERENCES billetagelignes (id)');
    }
}
