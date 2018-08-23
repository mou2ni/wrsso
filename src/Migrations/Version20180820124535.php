<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180820124535 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE journeecaisses DROP FOREIGN KEY FK_EC12D8DF843321D9');
        $this->addSql('DROP INDEX IDX_EC12D8DF843321D9 ON journeecaisses');
        $this->addSql('ALTER TABLE journeecaisses CHANGE journee_suivante_id journee_precedente_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE journeecaisses ADD CONSTRAINT FK_EC12D8DF12DE51E0 FOREIGN KEY (journee_precedente_id) REFERENCES JourneeCaisses (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EC12D8DF12DE51E0 ON journeecaisses (journee_precedente_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE JourneeCaisses DROP FOREIGN KEY FK_EC12D8DF12DE51E0');
        $this->addSql('DROP INDEX UNIQ_EC12D8DF12DE51E0 ON JourneeCaisses');
        $this->addSql('ALTER TABLE JourneeCaisses CHANGE journee_precedente_id journee_suivante_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE JourneeCaisses ADD CONSTRAINT FK_EC12D8DF843321D9 FOREIGN KEY (journee_suivante_id) REFERENCES journeecaisses (id)');
        $this->addSql('CREATE INDEX IDX_EC12D8DF843321D9 ON JourneeCaisses (journee_suivante_id)');
    }
}
