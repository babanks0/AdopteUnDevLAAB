<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241230133258 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dev CHANGE bibliographie bibliographie LONGTEXT DEFAULT NULL, CHANGE salaire_min salaire_min VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE telephone telephone VARCHAR(255) DEFAULT NULL, CHANGE avatar avatar VARCHAR(255) DEFAULT NULL, CHANGE localisation localisation VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dev CHANGE bibliographie bibliographie LONGTEXT NOT NULL, CHANGE salaire_min salaire_min VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE `user` CHANGE telephone telephone VARCHAR(255) NOT NULL, CHANGE avatar avatar VARCHAR(255) NOT NULL, CHANGE localisation localisation VARCHAR(255) NOT NULL');
    }
}
