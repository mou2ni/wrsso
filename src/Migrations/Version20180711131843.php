<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180711131843 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE caisses DROP FOREIGN KEY FK_41BE1F46117E3302');
        $this->addSql('DROP INDEX IDX_41BE1F46117E3302 ON caisses');
        $this->addSql('ALTER TABLE caisses DROP id_compte_ecart_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Caisses ADD id_compte_ecart_id INT NOT NULL');
        $this->addSql('ALTER TABLE Caisses ADD CONSTRAINT FK_41BE1F46117E3302 FOREIGN KEY (id_compte_ecart_id) REFERENCES comptes (id)');
        $this->addSql('CREATE INDEX IDX_41BE1F46117E3302 ON Caisses (id_compte_ecart_id)');
    }
}
