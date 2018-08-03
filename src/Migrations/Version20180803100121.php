<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180803100121 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE deviseintercaisses DROP FOREIGN KEY FK_D7381A147471EC71');
        $this->addSql('DROP INDEX IDX_D7381A147471EC71 ON deviseintercaisses');
        $this->addSql('ALTER TABLE deviseintercaisses ADD date_intercaisse DATE NOT NULL, DROP id_devise_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE DeviseIntercaisses ADD id_devise_id INT NOT NULL, DROP date_intercaisse');
        $this->addSql('ALTER TABLE DeviseIntercaisses ADD CONSTRAINT FK_D7381A147471EC71 FOREIGN KEY (id_devise_id) REFERENCES devises (id)');
        $this->addSql('CREATE INDEX IDX_D7381A147471EC71 ON DeviseIntercaisses (id_devise_id)');
    }
}
