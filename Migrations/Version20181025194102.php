<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181025194102 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added fields to session';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `session` 
            CHANGE COLUMN `token` token VARCHAR(45) NOT NULL ,
            ADD COLUMN `refresh_token` VARCHAR(45) NOT NULL AFTER token,
            ADD COLUMN `invalidated_at` DATETIME NULL DEFAULT NULL,
            ADD COLUMN `is_valid` TINYINT(4) NOT NULL DEFAULT 1
        ');
    }

    public function down(Schema $schema): void
    {
    }
}
