<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20181025185424 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Changes length of password';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `user` 
            CHANGE COLUMN `password` `password` VARCHAR(96) NOT NULL
        ');
    }

    public function down(Schema $schema): void
    {
    }
}
