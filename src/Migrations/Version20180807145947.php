<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180807145947 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE devise_tmp_mouvements (id INT AUTO_INCREMENT NOT NULL, devise_intercaisse_id INT DEFAULT NULL, devise_id INT NOT NULL, nombre INT NOT NULL, taux DOUBLE PRECISION NOT NULL, INDEX IDX_2C09D40883F78888 (devise_intercaisse_id), INDEX IDX_2C09D408F4445056 (devise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE devise_tmp_mouvements ADD CONSTRAINT FK_2C09D40883F78888 FOREIGN KEY (devise_intercaisse_id) REFERENCES DeviseIntercaisses (id)');
        $this->addSql('ALTER TABLE devise_tmp_mouvements ADD CONSTRAINT FK_2C09D408F4445056 FOREIGN KEY (devise_id) REFERENCES Devises (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE devise_tmp_mouvements');
    }
}
