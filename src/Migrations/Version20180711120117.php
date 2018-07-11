<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180711120117 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE transactioncomptes DROP FOREIGN KEY FK_4BC64C6E8114B15C');
        $this->addSql('ALTER TABLE transactioncomptes ADD CONSTRAINT FK_4BC64C6E8114B15C FOREIGN KEY (IdTransaction) REFERENCES Transactions (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE TransactionComptes DROP FOREIGN KEY FK_4BC64C6E8114B15C');
        $this->addSql('ALTER TABLE TransactionComptes ADD CONSTRAINT FK_4BC64C6E8114B15C FOREIGN KEY (IdTransaction) REFERENCES transactioncomptes (id)');
    }
}
