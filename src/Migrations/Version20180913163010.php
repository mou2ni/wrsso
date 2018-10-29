<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180913163010 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE journeecaisses ADD m_dette_divers_ouv BIGINT NOT NULL, ADD m_credit_divers_ouv BIGINT NOT NULL, ADD m_dette_divers_ferm BIGINT NOT NULL, ADD m_credit_divers_ferm BIGINT NOT NULL, DROP m_credit_divers, DROP m_dette_divers');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE JourneeCaisses ADD m_credit_divers BIGINT NOT NULL, ADD m_dette_divers BIGINT NOT NULL, DROP m_dette_divers_ouv, DROP m_credit_divers_ouv, DROP m_dette_divers_ferm, DROP m_credit_divers_ferm');
    }
}
