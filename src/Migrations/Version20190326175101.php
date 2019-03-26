<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190326175101 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE compense_lignes (id INT AUTO_INCREMENT NOT NULL, transaction_id INT DEFAULT NULL, compense_id INT NOT NULL, system_transfert_id INT DEFAULT NULL, m_envoi_attendu DOUBLE PRECISION NOT NULL, m_reception_attendu DOUBLE PRECISION NOT NULL, m_envoi_compense DOUBLE PRECISION NOT NULL, m_reception_compense DOUBLE PRECISION NOT NULL, INDEX IDX_8773A6B72FC0CB0F (transaction_id), INDEX IDX_8773A6B743DB652F (compense_id), INDEX IDX_8773A6B72FAF63A0 (system_transfert_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE compenses (id INT AUTO_INCREMENT NOT NULL, caisse_id INT DEFAULT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, total_envoi DOUBLE PRECISION NOT NULL, total_reception DOUBLE PRECISION NOT NULL, INDEX IDX_66752A5C27B4FEBF (caisse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE compense_lignes ADD CONSTRAINT FK_8773A6B72FC0CB0F FOREIGN KEY (transaction_id) REFERENCES Transactions (id)');
        $this->addSql('ALTER TABLE compense_lignes ADD CONSTRAINT FK_8773A6B743DB652F FOREIGN KEY (compense_id) REFERENCES compenses (id)');
        $this->addSql('ALTER TABLE compense_lignes ADD CONSTRAINT FK_8773A6B72FAF63A0 FOREIGN KEY (system_transfert_id) REFERENCES SystemTransfert (id)');
        $this->addSql('ALTER TABLE compenses ADD CONSTRAINT FK_66752A5C27B4FEBF FOREIGN KEY (caisse_id) REFERENCES Caisses (id)');
        $this->addSql('ALTER TABLE SystemTransfert DROP FOREIGN KEY FK_5350E46437E080D9');
        $this->addSql('ALTER TABLE SystemTransfert ADD CONSTRAINT FK_5350E46437E080D9 FOREIGN KEY (banque_id) REFERENCES Caisses (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE compense_lignes DROP FOREIGN KEY FK_8773A6B743DB652F');
        $this->addSql('DROP TABLE compense_lignes');
        $this->addSql('DROP TABLE compenses');
        $this->addSql('ALTER TABLE SystemTransfert DROP FOREIGN KEY FK_5350E46437E080D9');
        $this->addSql('ALTER TABLE SystemTransfert ADD CONSTRAINT FK_5350E46437E080D9 FOREIGN KEY (banque_id) REFERENCES entreprises (id)');
    }
}
