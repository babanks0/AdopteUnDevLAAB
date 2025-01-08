<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250108162424 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE dev CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE favoris CHANGE user_id user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE notification CHANGE user_id user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE poste CHANGE company_id company_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE technology_dev CHANGE dev_id dev_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE user CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE dev_id dev_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE company_id company_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE dev CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE favoris CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE notification CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE poste CHANGE company_id company_id INT NOT NULL');
        $this->addSql('ALTER TABLE technology_dev CHANGE dev_id dev_id INT NOT NULL');
        $this->addSql('ALTER TABLE `user` CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE dev_id dev_id INT DEFAULT NULL, CHANGE company_id company_id INT DEFAULT NULL');
    }
}
