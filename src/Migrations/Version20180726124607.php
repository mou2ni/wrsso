<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180726124607 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE devisejournees CHANGE id_billet_ferm_id id_billet_ferm_id INT DEFAULT NULL, CHANGE qte_ouv qte_ouv INT NOT NULL, CHANGE qte_achat qte_achat INT NOT NULL, CHANGE qte_vente qte_vente INT NOT NULL, CHANGE qte_intercaisse qte_intercaisse INT NOT NULL, CHANGE qte_ferm qte_ferm INT NOT NULL, CHANGE ecart_ferm ecart_ferm INT NOT NULL');
        $this->addSql('ALTER TABLE billetagelignes DROP FOREIGN KEY FK_612BF20EDE5B902F');
        $this->addSql('DROP INDEX IDX_612BF20EDE5B902F ON billetagelignes');
        $this->addSql('ALTER TABLE billetagelignes ADD valeur_billet DOUBLE PRECISION NOT NULL, DROP valeur_billet_id, CHANGE nb_billet nb_billet INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE BilletageLignes ADD valeur_billet_id INT NOT NULL, DROP valeur_billet, CHANGE nb_billet nb_billet DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE BilletageLignes ADD CONSTRAINT FK_612BF20EDE5B902F FOREIGN KEY (valeur_billet_id) REFERENCES billets (id)');
        $this->addSql('CREATE INDEX IDX_612BF20EDE5B902F ON BilletageLignes (valeur_billet_id)');
        $this->addSql('ALTER TABLE DeviseJournees CHANGE id_billet_ferm_id id_billet_ferm_id INT NOT NULL, CHANGE qte_ouv qte_ouv DOUBLE PRECISION NOT NULL, CHANGE qte_achat qte_achat DOUBLE PRECISION NOT NULL, CHANGE qte_vente qte_vente DOUBLE PRECISION NOT NULL, CHANGE qte_intercaisse qte_intercaisse DOUBLE PRECISION NOT NULL, CHANGE qte_ferm qte_ferm DOUBLE PRECISION NOT NULL, CHANGE ecart_ferm ecart_ferm DOUBLE PRECISION NOT NULL');
    }
}
