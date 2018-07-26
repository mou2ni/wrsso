<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180726103832 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE devisemouvements ADD devise_id INT NOT NULL');
        $this->addSql('ALTER TABLE devisemouvements ADD CONSTRAINT FK_F832A381F4445056 FOREIGN KEY (devise_id) REFERENCES Devises (id)');
        $this->addSql('CREATE INDEX IDX_F832A381F4445056 ON devisemouvements (devise_id)');
        $this->addSql('ALTER TABLE devise_recus ADD prenom VARCHAR(255) DEFAULT NULL, CHANGE nom_prenom nom VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE devise_recus ADD nom_prenom VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, DROP nom, DROP prenom');
        $this->addSql('ALTER TABLE DeviseMouvements DROP FOREIGN KEY FK_F832A381F4445056');
        $this->addSql('DROP INDEX IDX_F832A381F4445056 ON DeviseMouvements');
        $this->addSql('ALTER TABLE DeviseMouvements DROP devise_id');
    }
}
