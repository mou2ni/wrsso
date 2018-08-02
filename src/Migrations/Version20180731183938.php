<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180731183938 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE devise_recus ADD pays_piece_id INT DEFAULT NULL, DROP pays_piece');
        $this->addSql('ALTER TABLE devise_recus ADD CONSTRAINT FK_22234A8392A0D6EA FOREIGN KEY (pays_piece_id) REFERENCES Pays (id)');
        $this->addSql('CREATE INDEX IDX_22234A8392A0D6EA ON devise_recus (pays_piece_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE devise_recus DROP FOREIGN KEY FK_22234A8392A0D6EA');
        $this->addSql('DROP INDEX IDX_22234A8392A0D6EA ON devise_recus');
        $this->addSql('ALTER TABLE devise_recus ADD pays_piece VARCHAR(50) DEFAULT NULL COLLATE utf8mb4_unicode_ci, DROP pays_piece_id');
    }
}
