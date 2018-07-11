<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180711133840 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE paramcomptables ADD id_cpt_charges INT DEFAULT NULL, ADD id_cpt_produits INT DEFAULT NULL');
        $this->addSql('ALTER TABLE paramcomptables ADD CONSTRAINT FK_DED38F08CA20F56D FOREIGN KEY (id_cpt_charges) REFERENCES Comptes (id)');
        $this->addSql('ALTER TABLE paramcomptables ADD CONSTRAINT FK_DED38F0870BCF4B6 FOREIGN KEY (id_cpt_produits) REFERENCES Comptes (id)');
        $this->addSql('CREATE INDEX IDX_DED38F08CA20F56D ON paramcomptables (id_cpt_charges)');
        $this->addSql('CREATE INDEX IDX_DED38F0870BCF4B6 ON paramcomptables (id_cpt_produits)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ParamComptables DROP FOREIGN KEY FK_DED38F08CA20F56D');
        $this->addSql('ALTER TABLE ParamComptables DROP FOREIGN KEY FK_DED38F0870BCF4B6');
        $this->addSql('DROP INDEX IDX_DED38F08CA20F56D ON ParamComptables');
        $this->addSql('DROP INDEX IDX_DED38F0870BCF4B6 ON ParamComptables');
        $this->addSql('ALTER TABLE ParamComptables DROP id_cpt_charges, DROP id_cpt_produits');
    }
}
