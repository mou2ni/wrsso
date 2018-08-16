<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180815122414 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE caisses DROP FOREIGN KEY FK_41BE1F466E6C2D2F');
        $this->addSql('DROP INDEX IDX_41BE1F466E6C2D2F ON caisses');
        $this->addSql('ALTER TABLE caisses ADD journee_ouverte_id INT DEFAULT NULL, CHANGE id_compte_operation_id compte_operation_id INT NOT NULL');
        $this->addSql('ALTER TABLE caisses ADD CONSTRAINT FK_41BE1F462886B1E4 FOREIGN KEY (compte_operation_id) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE caisses ADD CONSTRAINT FK_41BE1F46D5D3F181 FOREIGN KEY (journee_ouverte_id) REFERENCES JourneeCaisses (id)');
        $this->addSql('CREATE INDEX IDX_41BE1F462886B1E4 ON caisses (compte_operation_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_41BE1F46D5D3F181 ON caisses (journee_ouverte_id)');
        $this->addSql('ALTER TABLE dettecreditdivers DROP FOREIGN KEY FK_1C5147F2A7814298');
        $this->addSql('DROP INDEX IDX_1C5147F2A7814298 ON dettecreditdivers');
        $this->addSql('ALTER TABLE dettecreditdivers CHANGE id_caisse_id caisse_id INT NOT NULL');
        $this->addSql('ALTER TABLE dettecreditdivers ADD CONSTRAINT FK_1C5147F227B4FEBF FOREIGN KEY (caisse_id) REFERENCES Caisses (id)');
        $this->addSql('CREATE INDEX IDX_1C5147F227B4FEBF ON dettecreditdivers (caisse_id)');
        $this->addSql('ALTER TABLE devisejournees DROP FOREIGN KEY FK_282BE5D27471EC71');
        $this->addSql('ALTER TABLE devisejournees DROP FOREIGN KEY FK_282BE5D2A18A167');
        $this->addSql('ALTER TABLE devisejournees DROP FOREIGN KEY FK_282BE5D2FAABDE7');
        $this->addSql('DROP INDEX IDX_282BE5D27471EC71 ON devisejournees');
        $this->addSql('DROP INDEX IDX_282BE5D2FAABDE7 ON devisejournees');
        $this->addSql('DROP INDEX IDX_282BE5D2A18A167 ON devisejournees');
        $this->addSql('ALTER TABLE devisejournees ADD billet_ouv_id INT DEFAULT NULL, ADD billet_ferm_id INT DEFAULT NULL, DROP id_billet_ouv_id, DROP id_billet_ferm_id, CHANGE id_devise_id devise_id INT NOT NULL');
        $this->addSql('ALTER TABLE devisejournees ADD CONSTRAINT FK_282BE5D2F4445056 FOREIGN KEY (devise_id) REFERENCES Devises (id)');
        $this->addSql('ALTER TABLE devisejournees ADD CONSTRAINT FK_282BE5D2B1826C82 FOREIGN KEY (billet_ouv_id) REFERENCES billetages (id)');
        $this->addSql('ALTER TABLE devisejournees ADD CONSTRAINT FK_282BE5D2377E1C61 FOREIGN KEY (billet_ferm_id) REFERENCES billetages (id)');
        $this->addSql('CREATE INDEX IDX_282BE5D2F4445056 ON devisejournees (devise_id)');
        $this->addSql('CREATE INDEX IDX_282BE5D2B1826C82 ON devisejournees (billet_ouv_id)');
        $this->addSql('CREATE INDEX IDX_282BE5D2377E1C61 ON devisejournees (billet_ferm_id)');
        $this->addSql('ALTER TABLE journeecaisses DROP FOREIGN KEY FK_EC12D8DF575F61FE');
        $this->addSql('ALTER TABLE journeecaisses DROP FOREIGN KEY FK_EC12D8DF6E2BFC7B');
        $this->addSql('ALTER TABLE journeecaisses DROP FOREIGN KEY FK_EC12D8DFA18A167');
        $this->addSql('ALTER TABLE journeecaisses DROP FOREIGN KEY FK_EC12D8DFA7814298');
        $this->addSql('ALTER TABLE journeecaisses DROP FOREIGN KEY FK_EC12D8DFC2D9BD12');
        $this->addSql('ALTER TABLE journeecaisses DROP FOREIGN KEY FK_EC12D8DFFAABDE7');
        $this->addSql('DROP INDEX UNIQ_EC12D8DFFAABDE7 ON journeecaisses');
        $this->addSql('DROP INDEX UNIQ_EC12D8DFA18A167 ON journeecaisses');
        $this->addSql('DROP INDEX IDX_EC12D8DFA7814298 ON journeecaisses');
        $this->addSql('DROP INDEX IDX_EC12D8DFC2D9BD12 ON journeecaisses');
        $this->addSql('DROP INDEX IDX_EC12D8DF575F61FE ON journeecaisses');
        $this->addSql('DROP INDEX IDX_EC12D8DF6E2BFC7B ON journeecaisses');
        $this->addSql('ALTER TABLE journeecaisses ADD journee_suivante_id INT DEFAULT NULL, ADD billet_ouv_id INT DEFAULT NULL, ADD system_elect_invent_ouv_id INT DEFAULT NULL, ADD billet_ferm_id INT DEFAULT NULL, ADD system_elect_invent_ferm_id INT DEFAULT NULL, DROP id_journee_suivante_id, DROP id_billet_ouv_id, DROP id_system_elect_invent_ouv_id, DROP id_billet_ferm_id, DROP id_system_elect_invent_ferm_id, CHANGE id_caisse_id caisse_id INT NOT NULL');
        $this->addSql('ALTER TABLE journeecaisses ADD CONSTRAINT FK_EC12D8DF27B4FEBF FOREIGN KEY (caisse_id) REFERENCES Caisses (id)');
        $this->addSql('ALTER TABLE journeecaisses ADD CONSTRAINT FK_EC12D8DF843321D9 FOREIGN KEY (journee_suivante_id) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE journeecaisses ADD CONSTRAINT FK_EC12D8DFB1826C82 FOREIGN KEY (billet_ouv_id) REFERENCES billetages (id)');
        $this->addSql('ALTER TABLE journeecaisses ADD CONSTRAINT FK_EC12D8DF2FFAA428 FOREIGN KEY (system_elect_invent_ouv_id) REFERENCES SystemElectInventaires (id)');
        $this->addSql('ALTER TABLE journeecaisses ADD CONSTRAINT FK_EC12D8DF377E1C61 FOREIGN KEY (billet_ferm_id) REFERENCES billetages (id)');
        $this->addSql('ALTER TABLE journeecaisses ADD CONSTRAINT FK_EC12D8DF1E32E5F FOREIGN KEY (system_elect_invent_ferm_id) REFERENCES SystemElectInventaires (id)');
        $this->addSql('CREATE INDEX IDX_EC12D8DF27B4FEBF ON journeecaisses (caisse_id)');
        $this->addSql('CREATE INDEX IDX_EC12D8DF843321D9 ON journeecaisses (journee_suivante_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EC12D8DFB1826C82 ON journeecaisses (billet_ouv_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EC12D8DF2FFAA428 ON journeecaisses (system_elect_invent_ouv_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EC12D8DF377E1C61 ON journeecaisses (billet_ferm_id)');
        $this->addSql('CREATE INDEX IDX_EC12D8DF1E32E5F ON journeecaisses (system_elect_invent_ferm_id)');
        $this->addSql('ALTER TABLE utilisateurs ADD journee_caisse_active_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateurs ADD CONSTRAINT FK_514AEAA6B451A8DB FOREIGN KEY (journee_caisse_active_id) REFERENCES JourneeCaisses (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_514AEAA6B451A8DB ON utilisateurs (journee_caisse_active_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Caisses DROP FOREIGN KEY FK_41BE1F462886B1E4');
        $this->addSql('ALTER TABLE Caisses DROP FOREIGN KEY FK_41BE1F46D5D3F181');
        $this->addSql('DROP INDEX IDX_41BE1F462886B1E4 ON Caisses');
        $this->addSql('DROP INDEX UNIQ_41BE1F46D5D3F181 ON Caisses');
        $this->addSql('ALTER TABLE Caisses DROP journee_ouverte_id, CHANGE compte_operation_id id_compte_operation_id INT NOT NULL');
        $this->addSql('ALTER TABLE Caisses ADD CONSTRAINT FK_41BE1F466E6C2D2F FOREIGN KEY (id_compte_operation_id) REFERENCES comptes (id)');
        $this->addSql('CREATE INDEX IDX_41BE1F466E6C2D2F ON Caisses (id_compte_operation_id)');
        $this->addSql('ALTER TABLE DetteCreditDivers DROP FOREIGN KEY FK_1C5147F227B4FEBF');
        $this->addSql('DROP INDEX IDX_1C5147F227B4FEBF ON DetteCreditDivers');
        $this->addSql('ALTER TABLE DetteCreditDivers CHANGE caisse_id id_caisse_id INT NOT NULL');
        $this->addSql('ALTER TABLE DetteCreditDivers ADD CONSTRAINT FK_1C5147F2A7814298 FOREIGN KEY (id_caisse_id) REFERENCES caisses (id)');
        $this->addSql('CREATE INDEX IDX_1C5147F2A7814298 ON DetteCreditDivers (id_caisse_id)');
        $this->addSql('ALTER TABLE DeviseJournees DROP FOREIGN KEY FK_282BE5D2F4445056');
        $this->addSql('ALTER TABLE DeviseJournees DROP FOREIGN KEY FK_282BE5D2B1826C82');
        $this->addSql('ALTER TABLE DeviseJournees DROP FOREIGN KEY FK_282BE5D2377E1C61');
        $this->addSql('DROP INDEX IDX_282BE5D2F4445056 ON DeviseJournees');
        $this->addSql('DROP INDEX IDX_282BE5D2B1826C82 ON DeviseJournees');
        $this->addSql('DROP INDEX IDX_282BE5D2377E1C61 ON DeviseJournees');
        $this->addSql('ALTER TABLE DeviseJournees ADD id_billet_ouv_id INT DEFAULT NULL, ADD id_billet_ferm_id INT DEFAULT NULL, DROP billet_ouv_id, DROP billet_ferm_id, CHANGE devise_id id_devise_id INT NOT NULL');
        $this->addSql('ALTER TABLE DeviseJournees ADD CONSTRAINT FK_282BE5D27471EC71 FOREIGN KEY (id_devise_id) REFERENCES devises (id)');
        $this->addSql('ALTER TABLE DeviseJournees ADD CONSTRAINT FK_282BE5D2A18A167 FOREIGN KEY (id_billet_ferm_id) REFERENCES billetages (id)');
        $this->addSql('ALTER TABLE DeviseJournees ADD CONSTRAINT FK_282BE5D2FAABDE7 FOREIGN KEY (id_billet_ouv_id) REFERENCES billetages (id)');
        $this->addSql('CREATE INDEX IDX_282BE5D27471EC71 ON DeviseJournees (id_devise_id)');
        $this->addSql('CREATE INDEX IDX_282BE5D2FAABDE7 ON DeviseJournees (id_billet_ouv_id)');
        $this->addSql('CREATE INDEX IDX_282BE5D2A18A167 ON DeviseJournees (id_billet_ferm_id)');
        $this->addSql('ALTER TABLE JourneeCaisses DROP FOREIGN KEY FK_EC12D8DF27B4FEBF');
        $this->addSql('ALTER TABLE JourneeCaisses DROP FOREIGN KEY FK_EC12D8DF843321D9');
        $this->addSql('ALTER TABLE JourneeCaisses DROP FOREIGN KEY FK_EC12D8DFB1826C82');
        $this->addSql('ALTER TABLE JourneeCaisses DROP FOREIGN KEY FK_EC12D8DF2FFAA428');
        $this->addSql('ALTER TABLE JourneeCaisses DROP FOREIGN KEY FK_EC12D8DF377E1C61');
        $this->addSql('ALTER TABLE JourneeCaisses DROP FOREIGN KEY FK_EC12D8DF1E32E5F');
        $this->addSql('DROP INDEX IDX_EC12D8DF27B4FEBF ON JourneeCaisses');
        $this->addSql('DROP INDEX IDX_EC12D8DF843321D9 ON JourneeCaisses');
        $this->addSql('DROP INDEX UNIQ_EC12D8DFB1826C82 ON JourneeCaisses');
        $this->addSql('DROP INDEX UNIQ_EC12D8DF2FFAA428 ON JourneeCaisses');
        $this->addSql('DROP INDEX UNIQ_EC12D8DF377E1C61 ON JourneeCaisses');
        $this->addSql('DROP INDEX IDX_EC12D8DF1E32E5F ON JourneeCaisses');
        $this->addSql('ALTER TABLE JourneeCaisses ADD id_journee_suivante_id INT DEFAULT NULL, ADD id_billet_ouv_id INT DEFAULT NULL, ADD id_system_elect_invent_ouv_id INT DEFAULT NULL, ADD id_billet_ferm_id INT DEFAULT NULL, ADD id_system_elect_invent_ferm_id INT DEFAULT NULL, DROP journee_suivante_id, DROP billet_ouv_id, DROP system_elect_invent_ouv_id, DROP billet_ferm_id, DROP system_elect_invent_ferm_id, CHANGE caisse_id id_caisse_id INT NOT NULL');
        $this->addSql('ALTER TABLE JourneeCaisses ADD CONSTRAINT FK_EC12D8DF575F61FE FOREIGN KEY (id_system_elect_invent_ouv_id) REFERENCES systemelectinventaires (id)');
        $this->addSql('ALTER TABLE JourneeCaisses ADD CONSTRAINT FK_EC12D8DF6E2BFC7B FOREIGN KEY (id_system_elect_invent_ferm_id) REFERENCES systemelectinventaires (id)');
        $this->addSql('ALTER TABLE JourneeCaisses ADD CONSTRAINT FK_EC12D8DFA18A167 FOREIGN KEY (id_billet_ferm_id) REFERENCES billetages (id)');
        $this->addSql('ALTER TABLE JourneeCaisses ADD CONSTRAINT FK_EC12D8DFA7814298 FOREIGN KEY (id_caisse_id) REFERENCES caisses (id)');
        $this->addSql('ALTER TABLE JourneeCaisses ADD CONSTRAINT FK_EC12D8DFC2D9BD12 FOREIGN KEY (id_journee_suivante_id) REFERENCES journeecaisses (id)');
        $this->addSql('ALTER TABLE JourneeCaisses ADD CONSTRAINT FK_EC12D8DFFAABDE7 FOREIGN KEY (id_billet_ouv_id) REFERENCES billetages (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EC12D8DFFAABDE7 ON JourneeCaisses (id_billet_ouv_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EC12D8DFA18A167 ON JourneeCaisses (id_billet_ferm_id)');
        $this->addSql('CREATE INDEX IDX_EC12D8DFA7814298 ON JourneeCaisses (id_caisse_id)');
        $this->addSql('CREATE INDEX IDX_EC12D8DFC2D9BD12 ON JourneeCaisses (id_journee_suivante_id)');
        $this->addSql('CREATE INDEX IDX_EC12D8DF575F61FE ON JourneeCaisses (id_system_elect_invent_ouv_id)');
        $this->addSql('CREATE INDEX IDX_EC12D8DF6E2BFC7B ON JourneeCaisses (id_system_elect_invent_ferm_id)');
        $this->addSql('ALTER TABLE Utilisateurs DROP FOREIGN KEY FK_514AEAA6B451A8DB');
        $this->addSql('DROP INDEX UNIQ_514AEAA6B451A8DB ON Utilisateurs');
        $this->addSql('ALTER TABLE Utilisateurs DROP journee_caisse_active_id');
    }
}
