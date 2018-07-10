<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180709184958 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE test');
        $this->addSql('ALTER TABLE billets DROP champ_test');
        $this->addSql('ALTER TABLE comptes CHANGE solde_courant solde_courant INT DEFAULT 0');
        $this->addSql('ALTER TABLE paramcomptables ADD code_structure VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE test (id INT AUTO_INCREMENT NOT NULL, valeur DOUBLE PRECISION NOT NULL, champ_test DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE billets ADD champ_test DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE Comptes CHANGE solde_courant solde_courant DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE ParamComptables DROP code_structure');
    }
}
