<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180731143322 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE devise_recus ADD journeeCaisse INT NOT NULL');
        $this->addSql('ALTER TABLE devise_recus ADD CONSTRAINT FK_22234A83CD3EC261 FOREIGN KEY (journeeCaisse) REFERENCES JourneeCaisses (id)');
        $this->addSql('CREATE INDEX IDX_22234A83CD3EC261 ON devise_recus (journeeCaisse)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE devise_recus DROP FOREIGN KEY FK_22234A83CD3EC261');
        $this->addSql('DROP INDEX IDX_22234A83CD3EC261 ON devise_recus');
        $this->addSql('ALTER TABLE devise_recus DROP journeeCaisse');
    }
}
