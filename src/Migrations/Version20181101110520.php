<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181101110520 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE caisses DROP FOREIGN KEY FK_41BE1F46D5D3F181');
        $this->addSql('DROP INDEX UNIQ_41BE1F46D5D3F181 ON caisses');
        $this->addSql('ALTER TABLE utilisateurs DROP FOREIGN KEY FK_514AEAA6B451A8DB');
        $this->addSql('DROP INDEX IDX_514AEAA6B451A8DB ON utilisateurs');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Caisses ADD CONSTRAINT FK_41BE1F46D5D3F181 FOREIGN KEY (journee_ouverte_id) REFERENCES journeecaisses (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_41BE1F46D5D3F181 ON Caisses (journee_ouverte_id)');
        $this->addSql('ALTER TABLE Utilisateurs ADD CONSTRAINT FK_514AEAA6B451A8DB FOREIGN KEY (journee_caisse_active_id) REFERENCES journeecaisses (id)');
        $this->addSql('CREATE INDEX IDX_514AEAA6B451A8DB ON Utilisateurs (journee_caisse_active_id)');
    }
}
