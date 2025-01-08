<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250108164648 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', raison_sociale VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted TINYINT(1) DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dev (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', nom VARCHAR(255) NOT NULL, prenoms VARCHAR(255) NOT NULL, bibliographie LONGTEXT DEFAULT NULL, visibilite TINYINT(1) NOT NULL, salaire_min VARCHAR(255) DEFAULT NULL, experience_level SMALLINT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted TINYINT(1) DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favoris (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', poste_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted TINYINT(1) DEFAULT 0 NOT NULL, INDEX IDX_8933C432A0905086 (poste_id), INDEX IDX_8933C432A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE niveau_etude (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', libelle VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted TINYINT(1) DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE niveau_etude_poste (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', poste_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', niveau_etude_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted TINYINT(1) DEFAULT 0 NOT NULL, INDEX IDX_204F95F2A0905086 (poste_id), INDEX IDX_204F95F2FEAD13D1 (niveau_etude_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', post_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', view TINYINT(1) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted TINYINT(1) DEFAULT 0 NOT NULL, INDEX IDX_BF5476CAA76ED395 (user_id), INDEX IDX_BF5476CA4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE poste (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', company_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', titre VARCHAR(255) NOT NULL, localisation VARCHAR(255) NOT NULL, salaire VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, niveau_experience INT NOT NULL, favoris TINYINT(1) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted TINYINT(1) DEFAULT 0 NOT NULL, INDEX IDX_7C890FAB979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE technology (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', titre VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted TINYINT(1) DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE technology_dev (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', technology_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', dev_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted TINYINT(1) DEFAULT 0 NOT NULL, INDEX IDX_AE5E9CA04235D463 (technology_id), INDEX IDX_AE5E9CA0A421F7B0 (dev_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE technology_poste (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', technology_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', poste_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted TINYINT(1) DEFAULT 0 NOT NULL, INDEX IDX_9CFAE5CF4235D463 (technology_id), INDEX IDX_9CFAE5CFA0905086 (poste_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', dev_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', company_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, telephone VARCHAR(255) DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, last_login DATETIME DEFAULT NULL, localisation VARCHAR(255) DEFAULT NULL, is_verified TINYINT(1) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted TINYINT(1) DEFAULT 0 NOT NULL, INDEX IDX_8D93D649A421F7B0 (dev_id), INDEX IDX_8D93D649979B1AD6 (company_id), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C432A0905086 FOREIGN KEY (poste_id) REFERENCES poste (id)');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C432A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE niveau_etude_poste ADD CONSTRAINT FK_204F95F2A0905086 FOREIGN KEY (poste_id) REFERENCES poste (id)');
        $this->addSql('ALTER TABLE niveau_etude_poste ADD CONSTRAINT FK_204F95F2FEAD13D1 FOREIGN KEY (niveau_etude_id) REFERENCES niveau_etude (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA4B89032C FOREIGN KEY (post_id) REFERENCES poste (id)');
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
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C432A76ED395');
        $this->addSql('ALTER TABLE niveau_etude_poste DROP FOREIGN KEY FK_204F95F2A0905086');
        $this->addSql('ALTER TABLE niveau_etude_poste DROP FOREIGN KEY FK_204F95F2FEAD13D1');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAA76ED395');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA4B89032C');
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
        $this->addSql('DROP TABLE niveau_etude');
        $this->addSql('DROP TABLE niveau_etude_poste');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE poste');
        $this->addSql('DROP TABLE technology');
        $this->addSql('DROP TABLE technology_dev');
        $this->addSql('DROP TABLE technology_poste');
        $this->addSql('DROP TABLE `user`');
    }
}
