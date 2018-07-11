<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180711122039 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE journeecaisses DROP INDEX IDX_EC12D8DFFAABDE7, ADD UNIQUE INDEX UNIQ_EC12D8DFFAABDE7 (id_billet_ouv_id)');
        $this->addSql('ALTER TABLE journeecaisses DROP INDEX IDX_EC12D8DFA18A167, ADD UNIQUE INDEX UNIQ_EC12D8DFA18A167 (id_billet_ferm_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE JourneeCaisses DROP INDEX UNIQ_EC12D8DFFAABDE7, ADD INDEX IDX_EC12D8DFFAABDE7 (id_billet_ouv_id)');
        $this->addSql('ALTER TABLE JourneeCaisses DROP INDEX UNIQ_EC12D8DFA18A167, ADD INDEX IDX_EC12D8DFA18A167 (id_billet_ferm_id)');
    }
}
