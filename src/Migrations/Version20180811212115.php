<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180811212115 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE caisses DROP FOREIGN KEY FK_41BE1F468A8E563');
        $this->addSql('DROP INDEX IDX_41BE1F468A8E563 ON caisses');
        $this->addSql('ALTER TABLE caisses CHANGE id_cpt_cv_devise compte_cv_devise_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE caisses ADD CONSTRAINT FK_41BE1F464F6FD7D FOREIGN KEY (compte_cv_devise_id) REFERENCES Comptes (id)');
        $this->addSql('CREATE INDEX IDX_41BE1F464F6FD7D ON caisses (compte_cv_devise_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Caisses DROP FOREIGN KEY FK_41BE1F464F6FD7D');
        $this->addSql('DROP INDEX IDX_41BE1F464F6FD7D ON Caisses');
        $this->addSql('ALTER TABLE Caisses CHANGE compte_cv_devise_id id_cpt_cv_devise INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Caisses ADD CONSTRAINT FK_41BE1F468A8E563 FOREIGN KEY (id_cpt_cv_devise) REFERENCES comptes (id)');
        $this->addSql('CREATE INDEX IDX_41BE1F468A8E563 ON Caisses (id_cpt_cv_devise)');
    }
}
