<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180720112626 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE intercaisses DROP FOREIGN KEY FK_F4C41EC521A6FBF');
        $this->addSql('ALTER TABLE intercaisses DROP FOREIGN KEY FK_F4C41EC81D89780');
        $this->addSql('DROP INDEX IDX_F4C41EC81D89780 ON intercaisses');
        $this->addSql('DROP INDEX IDX_F4C41EC521A6FBF ON intercaisses');
        $this->addSql('ALTER TABLE intercaisses ADD journeeCaisseSortant INT NOT NULL, ADD journeeCaisseEntrant INT NOT NULL, DROP journeeCaisseSource, DROP journeeCaisseDestination');
        $this->addSql('ALTER TABLE intercaisses ADD CONSTRAINT FK_F4C41EC1F688F76 FOREIGN KEY (journeeCaisseSortant) REFERENCES JourneeCaisses (id)');
        $this->addSql('ALTER TABLE intercaisses ADD CONSTRAINT FK_F4C41EC7A115F5B FOREIGN KEY (journeeCaisseEntrant) REFERENCES JourneeCaisses (id)');
        $this->addSql('CREATE INDEX IDX_F4C41EC1F688F76 ON intercaisses (journeeCaisseSortant)');
        $this->addSql('CREATE INDEX IDX_F4C41EC7A115F5B ON intercaisses (journeeCaisseEntrant)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE InterCaisses DROP FOREIGN KEY FK_F4C41EC1F688F76');
        $this->addSql('ALTER TABLE InterCaisses DROP FOREIGN KEY FK_F4C41EC7A115F5B');
        $this->addSql('DROP INDEX IDX_F4C41EC1F688F76 ON InterCaisses');
        $this->addSql('DROP INDEX IDX_F4C41EC7A115F5B ON InterCaisses');
        $this->addSql('ALTER TABLE InterCaisses ADD journeeCaisseSource INT NOT NULL, ADD journeeCaisseDestination INT NOT NULL, DROP journeeCaisseSortant, DROP journeeCaisseEntrant');
        $this->addSql('ALTER TABLE InterCaisses ADD CONSTRAINT FK_F4C41EC521A6FBF FOREIGN KEY (journeeCaisseDestination) REFERENCES journeecaisses (id)');
        $this->addSql('ALTER TABLE InterCaisses ADD CONSTRAINT FK_F4C41EC81D89780 FOREIGN KEY (journeeCaisseSource) REFERENCES journeecaisses (id)');
        $this->addSql('CREATE INDEX IDX_F4C41EC81D89780 ON InterCaisses (journeeCaisseSource)');
        $this->addSql('CREATE INDEX IDX_F4C41EC521A6FBF ON InterCaisses (journeeCaisseDestination)');
    }
}
