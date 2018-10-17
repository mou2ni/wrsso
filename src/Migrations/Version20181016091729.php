<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181016091729 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE transfertinternationaux CHANGE m_transfert m_transfert DOUBLE PRECISION NOT NULL, CHANGE m_frais_ht m_frais_ht DOUBLE PRECISION NOT NULL, CHANGE m_tva m_tva DOUBLE PRECISION NOT NULL, CHANGE m_autres_taxes m_autres_taxes DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE TransfertInternationaux CHANGE m_transfert m_transfert VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE m_frais_ht m_frais_ht VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE m_tva m_tva VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE m_autres_taxes m_autres_taxes VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
