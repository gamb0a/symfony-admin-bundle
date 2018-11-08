<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181025195724 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'UPDATE fields to session';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `session` 
            CHANGE COLUMN `token` token VARCHAR(255) NOT NULL ,
            CHANGE COLUMN `refresh_token` refresh_token VARCHAR(255) NOT NULL
        ');
    }

    public function down(Schema $schema): void
    {
    }
}
