<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180823201954 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE billets ADD devise_id INT NOT NULL');
        $this->addSql('ALTER TABLE billets ADD CONSTRAINT FK_4FCF9B68F4445056 FOREIGN KEY (devise_id) REFERENCES Devises (id)');
        $this->addSql('CREATE INDEX IDX_4FCF9B68F4445056 ON billets (devise_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE billets DROP FOREIGN KEY FK_4FCF9B68F4445056');
        $this->addSql('DROP INDEX IDX_4FCF9B68F4445056 ON billets');
        $this->addSql('ALTER TABLE billets DROP devise_id');
    }
}
