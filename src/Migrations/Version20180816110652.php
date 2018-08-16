<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180816110652 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE billetagelignes DROP FOREIGN KEY FK_612BF20E3B06492F');
        $this->addSql('DROP INDEX IDX_612BF20E3B06492F ON billetagelignes');
        $this->addSql('ALTER TABLE billetagelignes DROP valeur_ligne, CHANGE id_billetage_id billetages_id INT NOT NULL');
        $this->addSql('ALTER TABLE billetagelignes ADD CONSTRAINT FK_612BF20EF2B35750 FOREIGN KEY (billetages_id) REFERENCES billetages (id)');
        $this->addSql('CREATE INDEX IDX_612BF20EF2B35750 ON billetagelignes (billetages_id)');
        $this->addSql('ALTER TABLE billetages DROP valeur_total');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE BilletageLignes DROP FOREIGN KEY FK_612BF20EF2B35750');
        $this->addSql('DROP INDEX IDX_612BF20EF2B35750 ON BilletageLignes');
        $this->addSql('ALTER TABLE BilletageLignes ADD valeur_ligne DOUBLE PRECISION NOT NULL, CHANGE billetages_id id_billetage_id INT NOT NULL');
        $this->addSql('ALTER TABLE BilletageLignes ADD CONSTRAINT FK_612BF20E3B06492F FOREIGN KEY (id_billetage_id) REFERENCES billetages (id)');
        $this->addSql('CREATE INDEX IDX_612BF20E3B06492F ON BilletageLignes (id_billetage_id)');
        $this->addSql('ALTER TABLE billetages ADD valeur_total DOUBLE PRECISION NOT NULL');
    }
}
