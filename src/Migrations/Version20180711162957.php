<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180711162957 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE caisses ADD id_cpt_cv_devise INT NOT NULL');
        $this->addSql('ALTER TABLE caisses ADD CONSTRAINT FK_41BE1F468A8E563 FOREIGN KEY (id_cpt_cv_devise) REFERENCES Comptes (id)');
        $this->addSql('CREATE INDEX IDX_41BE1F468A8E563 ON caisses (id_cpt_cv_devise)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Caisses DROP FOREIGN KEY FK_41BE1F468A8E563');
        $this->addSql('DROP INDEX IDX_41BE1F468A8E563 ON Caisses');
        $this->addSql('ALTER TABLE Caisses DROP id_cpt_cv_devise');
    }
}
