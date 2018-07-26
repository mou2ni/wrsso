<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180724155855 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE JourneeCaisses DROP FOREIGN KEY FK_EC12D8DFA7814298');
        $this->addSql('ALTER TABLE JourneeCaisses DROP FOREIGN KEY FK_EC12D8DFC6EE5C49');

        $this->addSql('ALTER TABLE journeecaisses CHANGE id_caisse_id id_caisse_id INT NOT NULL, CHANGE id_utilisateur_id id_utilisateur_id INT NOT NULL, CHANGE date_ferm date_ferm DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE devise_recus DROP devise_recus');

        $this->addSql('ALTER TABLE JourneeCaisses ADD CONSTRAINT FK_EC12D8DFA7814298 FOREIGN KEY (id_caisse_id) REFERENCES Caisses (id)');
        $this->addSql('ALTER TABLE JourneeCaisses ADD CONSTRAINT FK_EC12D8DFC6EE5C49 FOREIGN KEY (id_utilisateur_id) REFERENCES Utilisateurs (id)');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE devise_recus ADD devise_recus VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE JourneeCaisses CHANGE id_caisse_id id_caisse_id INT DEFAULT NULL, CHANGE id_utilisateur_id id_utilisateur_id INT DEFAULT NULL, CHANGE date_ferm date_ferm DATETIME NOT NULL');
    }
}
