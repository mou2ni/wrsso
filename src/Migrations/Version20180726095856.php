<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180726095856 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE journeecaisses DROP FOREIGN KEY FK_EC12D8DFC6EE5C49');
        $this->addSql('DROP INDEX IDX_EC12D8DFC6EE5C49 ON journeecaisses');
        $this->addSql('ALTER TABLE journeecaisses ADD utilisateur INT NOT NULL, DROP id_utilisateur_id');
        $this->addSql('ALTER TABLE journeecaisses ADD CONSTRAINT FK_EC12D8DF1D1C63B3 FOREIGN KEY (utilisateur) REFERENCES Utilisateurs (id)');
        $this->addSql('CREATE INDEX IDX_EC12D8DF1D1C63B3 ON journeecaisses (utilisateur)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE JourneeCaisses DROP FOREIGN KEY FK_EC12D8DF1D1C63B3');
        $this->addSql('DROP INDEX IDX_EC12D8DF1D1C63B3 ON JourneeCaisses');
        $this->addSql('ALTER TABLE JourneeCaisses ADD id_utilisateur_id INT DEFAULT NULL, DROP utilisateur');
        $this->addSql('ALTER TABLE JourneeCaisses ADD CONSTRAINT FK_EC12D8DFC6EE5C49 FOREIGN KEY (id_utilisateur_id) REFERENCES utilisateurs (id)');
        $this->addSql('CREATE INDEX IDX_EC12D8DFC6EE5C49 ON JourneeCaisses (id_utilisateur_id)');
    }
}
