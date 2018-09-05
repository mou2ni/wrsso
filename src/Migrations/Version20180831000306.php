<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180831000306 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE transactions ADD idJourneeCaisse INT DEFAULT NULL');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_F299C1B45D419CCB FOREIGN KEY (idUtilisateur) REFERENCES Utilisateurs (id)');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_F299C1B47DA9E4F FOREIGN KEY (idJourneeCaisse) REFERENCES JourneeCaisses (id)');
        $this->addSql('CREATE INDEX IDX_F299C1B47DA9E4F ON transactions (idJourneeCaisse)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Transactions DROP FOREIGN KEY FK_F299C1B45D419CCB');
        $this->addSql('ALTER TABLE Transactions DROP FOREIGN KEY FK_F299C1B47DA9E4F');
        $this->addSql('DROP INDEX IDX_F299C1B47DA9E4F ON Transactions');
        $this->addSql('ALTER TABLE Transactions DROP idJourneeCaisse');
    }
}
