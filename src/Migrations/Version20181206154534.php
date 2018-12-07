<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181206154534 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE transfertinternationaux DROP FOREIGN KEY FK_CD12576A6945FF6B');
        $this->addSql('DROP INDEX IDX_CD12576A6945FF6B ON transfertinternationaux');
        $this->addSql('ALTER TABLE transfertinternationaux CHANGE id_system_transfert_id idSystemTransfert INT NOT NULL');
        $this->addSql('ALTER TABLE transfertinternationaux ADD CONSTRAINT FK_CD12576AB24F04ED FOREIGN KEY (idSystemTransfert) REFERENCES SystemTransfert (id)');
        $this->addSql('CREATE INDEX IDX_CD12576AB24F04ED ON transfertinternationaux (idSystemTransfert)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE TransfertInternationaux DROP FOREIGN KEY FK_CD12576AB24F04ED');
        $this->addSql('DROP INDEX IDX_CD12576AB24F04ED ON TransfertInternationaux');
        $this->addSql('ALTER TABLE TransfertInternationaux CHANGE idsystemtransfert id_system_transfert_id INT NOT NULL');
        $this->addSql('ALTER TABLE TransfertInternationaux ADD CONSTRAINT FK_CD12576A6945FF6B FOREIGN KEY (id_system_transfert_id) REFERENCES systemtransfert (id)');
        $this->addSql('CREATE INDEX IDX_CD12576A6945FF6B ON TransfertInternationaux (id_system_transfert_id)');
    }
}
