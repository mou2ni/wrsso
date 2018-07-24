<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180724141320 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE devise_recus (id INT AUTO_INCREMENT NOT NULL, date_recu DATE NOT NULL, nom_prenom VARCHAR(255) DEFAULT NULL, type_piece VARCHAR(50) DEFAULT NULL, numero_piece VARCHAR(50) DEFAULT NULL, expire_le DATE DEFAULT NULL, motif VARCHAR(255) DEFAULT NULL, devise_recus VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE devisemouvements ADD taux DOUBLE PRECISION NOT NULL, CHANGE nombre nombre INT NOT NULL, CHANGE m_cvd m_cvd INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE devise_recus');
        $this->addSql('ALTER TABLE DeviseMouvements DROP taux, CHANGE nombre nombre DOUBLE PRECISION NOT NULL, CHANGE m_cvd m_cvd DOUBLE PRECISION NOT NULL');
    }
}
