<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180808134909 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE devise_tmp_mouvements DROP FOREIGN KEY FK_2C09D408ED9240C0');
        $this->addSql('DROP INDEX IDX_2C09D408ED9240C0 ON devise_tmp_mouvements');
        $this->addSql('ALTER TABLE devise_tmp_mouvements DROP journee_caisse_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE devise_tmp_mouvements ADD journee_caisse_id INT NOT NULL');
        $this->addSql('ALTER TABLE devise_tmp_mouvements ADD CONSTRAINT FK_2C09D408ED9240C0 FOREIGN KEY (journee_caisse_id) REFERENCES journeecaisses (id)');
        $this->addSql('CREATE INDEX IDX_2C09D408ED9240C0 ON devise_tmp_mouvements (journee_caisse_id)');
    }
}
