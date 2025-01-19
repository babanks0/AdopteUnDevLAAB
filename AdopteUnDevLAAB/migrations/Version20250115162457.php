<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250115162457 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE candidacture (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', poste_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted TINYINT(1) DEFAULT 0 NOT NULL, UNIQUE INDEX UNIQ_4033C4D3A0905086 (poste_id), INDEX IDX_4033C4D3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE candidacture ADD CONSTRAINT FK_4033C4D3A0905086 FOREIGN KEY (poste_id) REFERENCES poste (id)');
        $this->addSql('ALTER TABLE candidacture ADD CONSTRAINT FK_4033C4D3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidacture DROP FOREIGN KEY FK_4033C4D3A0905086');
        $this->addSql('ALTER TABLE candidacture DROP FOREIGN KEY FK_4033C4D3A76ED395');
        $this->addSql('DROP TABLE candidacture');
    }
}
