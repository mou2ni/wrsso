<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181218163924 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE devise_recus ADD comment VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE journeecaisses DROP INDEX IDX_EC12D8DF2FFAA428, ADD UNIQUE INDEX UNIQ_EC12D8DF2FFAA428 (system_elect_invent_ouv_id)');
        $this->addSql('ALTER TABLE journeecaisses ADD m_intercaisse_sortants BIGINT NOT NULL, ADD m_intercaisse_entrants BIGINT NOT NULL');
        $this->addSql('ALTER TABLE transfertinternationaux DROP FOREIGN KEY FK_CD12576A6945FF6B');
        $this->addSql('DROP INDEX IDX_CD12576A6945FF6B ON transfertinternationaux');
        $this->addSql('ALTER TABLE transfertinternationaux CHANGE id_system_transfert_id idSystemTransfert INT NOT NULL');
        $this->addSql('ALTER TABLE transfertinternationaux ADD CONSTRAINT FK_CD12576AB24F04ED FOREIGN KEY (idSystemTransfert) REFERENCES SystemTransfert (id)');
        $this->addSql('CREATE INDEX IDX_CD12576AB24F04ED ON transfertinternationaux (idSystemTransfert)');
        $this->addSql('ALTER TABLE utilisateurs ADD CONSTRAINT FK_514AEAA6B451A8DB FOREIGN KEY (journee_caisse_active_id) REFERENCES JourneeCaisses (id)');
        $this->addSql('CREATE INDEX IDX_514AEAA6B451A8DB ON utilisateurs (journee_caisse_active_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE devise_recus DROP comment');
        $this->addSql('ALTER TABLE JourneeCaisses DROP INDEX UNIQ_EC12D8DF2FFAA428, ADD INDEX IDX_EC12D8DF2FFAA428 (system_elect_invent_ouv_id)');
        $this->addSql('ALTER TABLE JourneeCaisses DROP m_intercaisse_sortants, DROP m_intercaisse_entrants');
        $this->addSql('ALTER TABLE TransfertInternationaux DROP FOREIGN KEY FK_CD12576AB24F04ED');
        $this->addSql('DROP INDEX IDX_CD12576AB24F04ED ON TransfertInternationaux');
        $this->addSql('ALTER TABLE TransfertInternationaux CHANGE idsystemtransfert id_system_transfert_id INT NOT NULL');
        $this->addSql('ALTER TABLE TransfertInternationaux ADD CONSTRAINT FK_CD12576A6945FF6B FOREIGN KEY (id_system_transfert_id) REFERENCES systemtransfert (id)');
        $this->addSql('CREATE INDEX IDX_CD12576A6945FF6B ON TransfertInternationaux (id_system_transfert_id)');
        $this->addSql('ALTER TABLE Utilisateurs DROP FOREIGN KEY FK_514AEAA6B451A8DB');
        $this->addSql('DROP INDEX IDX_514AEAA6B451A8DB ON Utilisateurs');
    }
}
