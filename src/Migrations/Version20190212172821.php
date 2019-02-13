<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190212172821 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE collaborateurs CHANGE est_representant est_representant TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE transfertinternationaux ADD transaction_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE transfertinternationaux ADD CONSTRAINT FK_CD12576A2FC0CB0F FOREIGN KEY (transaction_id) REFERENCES Transactions (id)');
        $this->addSql('CREATE INDEX IDX_CD12576A2FC0CB0F ON transfertinternationaux (transaction_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE collaborateurs CHANGE est_representant est_representant TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE TransfertInternationaux DROP FOREIGN KEY FK_CD12576A2FC0CB0F');
        $this->addSql('DROP INDEX IDX_CD12576A2FC0CB0F ON TransfertInternationaux');
        $this->addSql('ALTER TABLE TransfertInternationaux DROP transaction_id');
    }
}
