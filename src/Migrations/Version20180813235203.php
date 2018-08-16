<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180813235203 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE billetagelignes ADD billet_id INT NOT NULL');
        $this->addSql('ALTER TABLE billetagelignes ADD CONSTRAINT FK_612BF20E44973C78 FOREIGN KEY (billet_id) REFERENCES billets (id)');
        $this->addSql('CREATE INDEX IDX_612BF20E44973C78 ON billetagelignes (billet_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE BilletageLignes DROP FOREIGN KEY FK_612BF20E44973C78');
        $this->addSql('DROP INDEX IDX_612BF20E44973C78 ON BilletageLignes');
        $this->addSql('ALTER TABLE BilletageLignes DROP billet_id');
    }
}
