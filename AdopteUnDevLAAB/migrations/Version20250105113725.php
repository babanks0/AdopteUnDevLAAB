<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250105113725 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE niveau_etude (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted TINYINT(1) DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE niveau_etude_poste (id INT AUTO_INCREMENT NOT NULL, poste_id INT NOT NULL, niveau_etude_id INT NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted TINYINT(1) DEFAULT 0 NOT NULL, INDEX IDX_204F95F2A0905086 (poste_id), INDEX IDX_204F95F2FEAD13D1 (niveau_etude_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE niveau_etude_poste ADD CONSTRAINT FK_204F95F2A0905086 FOREIGN KEY (poste_id) REFERENCES poste (id)');
        $this->addSql('ALTER TABLE niveau_etude_poste ADD CONSTRAINT FK_204F95F2FEAD13D1 FOREIGN KEY (niveau_etude_id) REFERENCES niveau_etude (id)');
        $this->addSql('ALTER TABLE niveau_experience_dev DROP FOREIGN KEY FK_4544D6B0A421F7B0');
        $this->addSql('ALTER TABLE niveau_experience_dev DROP FOREIGN KEY FK_4544D6B05649FBA6');
        $this->addSql('DROP TABLE niveau_experience');
        $this->addSql('DROP TABLE niveau_experience_dev');
        $this->addSql('ALTER TABLE dev ADD experience_level SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C432A421F7B0');
        $this->addSql('DROP INDEX IDX_8933C432A421F7B0 ON favoris');
        $this->addSql('ALTER TABLE favoris ADD user_id INT NOT NULL, DROP dev_id');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C432A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_8933C432A76ED395 ON favoris (user_id)');
        $this->addSql('ALTER TABLE poste ADD niveau_experience INT NOT NULL, CHANGE status favoris TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE niveau_experience (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted TINYINT(1) DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE niveau_experience_dev (id INT AUTO_INCREMENT NOT NULL, dev_id INT NOT NULL, niveau_experience_id INT NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted TINYINT(1) DEFAULT 0 NOT NULL, INDEX IDX_4544D6B0A421F7B0 (dev_id), INDEX IDX_4544D6B05649FBA6 (niveau_experience_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE niveau_experience_dev ADD CONSTRAINT FK_4544D6B0A421F7B0 FOREIGN KEY (dev_id) REFERENCES dev (id)');
        $this->addSql('ALTER TABLE niveau_experience_dev ADD CONSTRAINT FK_4544D6B05649FBA6 FOREIGN KEY (niveau_experience_id) REFERENCES niveau_experience (id)');
        $this->addSql('ALTER TABLE niveau_etude_poste DROP FOREIGN KEY FK_204F95F2A0905086');
        $this->addSql('ALTER TABLE niveau_etude_poste DROP FOREIGN KEY FK_204F95F2FEAD13D1');
        $this->addSql('DROP TABLE niveau_etude');
        $this->addSql('DROP TABLE niveau_etude_poste');
        $this->addSql('ALTER TABLE dev DROP experience_level');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C432A76ED395');
        $this->addSql('DROP INDEX IDX_8933C432A76ED395 ON favoris');
        $this->addSql('ALTER TABLE favoris ADD dev_id INT DEFAULT NULL, DROP user_id');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C432A421F7B0 FOREIGN KEY (dev_id) REFERENCES dev (id)');
        $this->addSql('CREATE INDEX IDX_8933C432A421F7B0 ON favoris (dev_id)');
        $this->addSql('ALTER TABLE poste DROP niveau_experience, CHANGE favoris status TINYINT(1) NOT NULL');
    }
}
