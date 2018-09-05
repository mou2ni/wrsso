<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180830225624 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE systemelectinventaires DROP solde_total');
        $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_F299C1B45D419CCB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE SystemElectInventaires ADD solde_total DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE Transactions ADD CONSTRAINT FK_F299C1B45D419CCB FOREIGN KEY (idUtilisateur) REFERENCES utilisateurs (id)');
    }
}
