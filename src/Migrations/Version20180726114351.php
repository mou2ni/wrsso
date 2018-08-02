<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180726114351 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE journeecaisses DROP FOREIGN KEY FK_EC12D8DF1D1C63B3');
        $this->addSql('DROP INDEX IDX_EC12D8DF1D1C63B3 ON journeecaisses');
        $this->addSql('ALTER TABLE journeecaisses CHANGE utilisateur utilisateur_id INT NOT NULL');
        $this->addSql('ALTER TABLE journeecaisses ADD CONSTRAINT FK_EC12D8DFFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES Utilisateurs (id)');
        $this->addSql('CREATE INDEX IDX_EC12D8DFFB88E14F ON journeecaisses (utilisateur_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE JourneeCaisses DROP FOREIGN KEY FK_EC12D8DFFB88E14F');
        $this->addSql('DROP INDEX IDX_EC12D8DFFB88E14F ON JourneeCaisses');
        $this->addSql('ALTER TABLE JourneeCaisses CHANGE utilisateur_id utilisateur INT NOT NULL');
        $this->addSql('ALTER TABLE JourneeCaisses ADD CONSTRAINT FK_EC12D8DF1D1C63B3 FOREIGN KEY (utilisateur) REFERENCES utilisateurs (id)');
        $this->addSql('CREATE INDEX IDX_EC12D8DF1D1C63B3 ON JourneeCaisses (utilisateur)');
    }
}
