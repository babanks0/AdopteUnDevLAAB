<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250110204706 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE technology_dev DROP FOREIGN KEY FK_AE5E9CA0A421F7B0');
        $this->addSql('DROP INDEX IDX_AE5E9CA0A421F7B0 ON technology_dev');
        $this->addSql('ALTER TABLE technology_dev CHANGE dev_id user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE technology_dev ADD CONSTRAINT FK_AE5E9CA0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_AE5E9CA0A76ED395 ON technology_dev (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE technology_dev DROP FOREIGN KEY FK_AE5E9CA0A76ED395');
        $this->addSql('DROP INDEX IDX_AE5E9CA0A76ED395 ON technology_dev');
        $this->addSql('ALTER TABLE technology_dev CHANGE user_id dev_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE technology_dev ADD CONSTRAINT FK_AE5E9CA0A421F7B0 FOREIGN KEY (dev_id) REFERENCES dev (id)');
        $this->addSql('CREATE INDEX IDX_AE5E9CA0A421F7B0 ON technology_dev (dev_id)');
    }
}
