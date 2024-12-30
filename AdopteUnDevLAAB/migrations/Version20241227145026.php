<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241227145026 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, raison_sociale VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted TINYINT(1) DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dev (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenoms VARCHAR(255) NOT NULL, bibliographie LONGTEXT NOT NULL, visibilite TINYINT(1) NOT NULL, salaire_min VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted TINYINT(1) DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favoris (id INT AUTO_INCREMENT NOT NULL, poste_id INT DEFAULT NULL, dev_id INT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted TINYINT(1) DEFAULT 0 NOT NULL, INDEX IDX_8933C432A0905086 (poste_id), INDEX IDX_8933C432A421F7B0 (dev_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE niveau_experience (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted TINYINT(1) DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE niveau_experience_dev (id INT AUTO_INCREMENT NOT NULL, dev_id INT NOT NULL, niveau_experience_id INT NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted TINYINT(1) DEFAULT 0 NOT NULL, INDEX IDX_4544D6B0A421F7B0 (dev_id), INDEX IDX_4544D6B05649FBA6 (niveau_experience_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE poste (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, titre VARCHAR(255) NOT NULL, localisation VARCHAR(255) NOT NULL, salaire VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, status TINYINT(1) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted TINYINT(1) DEFAULT 0 NOT NULL, INDEX IDX_7C890FAB979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE technology (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted TINYINT(1) DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE technology_dev (id INT AUTO_INCREMENT NOT NULL, technology_id INT NOT NULL, dev_id INT NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted TINYINT(1) DEFAULT 0 NOT NULL, INDEX IDX_AE5E9CA04235D463 (technology_id), INDEX IDX_AE5E9CA0A421F7B0 (dev_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE technology_poste (id INT AUTO_INCREMENT NOT NULL, technology_id INT NOT NULL, poste_id INT NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted TINYINT(1) DEFAULT 0 NOT NULL, INDEX IDX_9CFAE5CF4235D463 (technology_id), INDEX IDX_9CFAE5CFA0905086 (poste_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, dev_id INT DEFAULT NULL, company_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, avatar VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, localisation VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted TINYINT(1) DEFAULT 0 NOT NULL, INDEX IDX_8D93D649A421F7B0 (dev_id), INDEX IDX_8D93D649979B1AD6 (company_id), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C432A0905086 FOREIGN KEY (poste_id) REFERENCES poste (id)');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C432A421F7B0 FOREIGN KEY (dev_id) REFERENCES dev (id)');
        $this->addSql('ALTER TABLE niveau_experience_dev ADD CONSTRAINT FK_4544D6B0A421F7B0 FOREIGN KEY (dev_id) REFERENCES dev (id)');
        $this->addSql('ALTER TABLE niveau_experience_dev ADD CONSTRAINT FK_4544D6B05649FBA6 FOREIGN KEY (niveau_experience_id) REFERENCES niveau_experience (id)');
        $this->addSql('ALTER TABLE poste ADD CONSTRAINT FK_7C890FAB979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE technology_dev ADD CONSTRAINT FK_AE5E9CA04235D463 FOREIGN KEY (technology_id) REFERENCES technology (id)');
        $this->addSql('ALTER TABLE technology_dev ADD CONSTRAINT FK_AE5E9CA0A421F7B0 FOREIGN KEY (dev_id) REFERENCES dev (id)');
        $this->addSql('ALTER TABLE technology_poste ADD CONSTRAINT FK_9CFAE5CF4235D463 FOREIGN KEY (technology_id) REFERENCES technology (id)');
        $this->addSql('ALTER TABLE technology_poste ADD CONSTRAINT FK_9CFAE5CFA0905086 FOREIGN KEY (poste_id) REFERENCES poste (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649A421F7B0 FOREIGN KEY (dev_id) REFERENCES dev (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C432A0905086');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C432A421F7B0');
        $this->addSql('ALTER TABLE niveau_experience_dev DROP FOREIGN KEY FK_4544D6B0A421F7B0');
        $this->addSql('ALTER TABLE niveau_experience_dev DROP FOREIGN KEY FK_4544D6B05649FBA6');
        $this->addSql('ALTER TABLE poste DROP FOREIGN KEY FK_7C890FAB979B1AD6');
        $this->addSql('ALTER TABLE technology_dev DROP FOREIGN KEY FK_AE5E9CA04235D463');
        $this->addSql('ALTER TABLE technology_dev DROP FOREIGN KEY FK_AE5E9CA0A421F7B0');
        $this->addSql('ALTER TABLE technology_poste DROP FOREIGN KEY FK_9CFAE5CF4235D463');
        $this->addSql('ALTER TABLE technology_poste DROP FOREIGN KEY FK_9CFAE5CFA0905086');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649A421F7B0');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649979B1AD6');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE dev');
        $this->addSql('DROP TABLE favoris');
        $this->addSql('DROP TABLE niveau_experience');
        $this->addSql('DROP TABLE niveau_experience_dev');
        $this->addSql('DROP TABLE poste');
        $this->addSql('DROP TABLE technology');
        $this->addSql('DROP TABLE technology_dev');
        $this->addSql('DROP TABLE technology_poste');
        $this->addSql('DROP TABLE `user`');
    }
}
