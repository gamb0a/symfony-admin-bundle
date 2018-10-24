<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20181024214959 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Update User';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE `user` 
            CHANGE COLUMN `password` `password` VARCHAR(45) NOT NULL ,
            CHANGE COLUMN `status` `status` ENUM("Activo", "Suspendido", "Eliminado") NOT NULL DEFAULT "Activo" ,
            CHANGE COLUMN `last_password_change` `last_password_change` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
            CHANGE COLUMN `created_at` `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
            CHANGE COLUMN `modified_at` `modified_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
            ADD COLUMN `is_admin` TINYINT(4) NOT NULL DEFAULT 0 AFTER `modified_at`
        ');
    }
    public function down(Schema $schema) : void { }
}
