<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20181024142343 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Creates User and Session tables';
    }

    public function down(Schema $schema) : void { }
    
    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE `user` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `rut` INT(11) NOT NULL,
            `dv` VARCHAR(1) NOT NULL,
            `name` VARCHAR(255) NOT NULL,
            `username` VARCHAR(45) NULL DEFAULT NULL,
            `email` VARCHAR(100) NOT NULL,
            `password` VARCHAR(45) NULL DEFAULT NULL,
            `status` ENUM("Activo", "Suspendido", "Eliminado") NULL DEFAULT NULL,
            `last_password_change` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `modified_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE INDEX `rut_UNIQUE` (`rut` ASC))
          ENGINE = InnoDB
          DEFAULT CHARACTER SET = utf8
          COLLATE = utf8_general_ci');
          
          $this->addSql('CREATE TABLE `session` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `token` VARCHAR(255) NOT NULL,
                `user` INT(11) NOT NULL,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `expires_at` DATETIME NULL DEFAULT NULL,
                `refreshed_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
                `invalid_reason` VARCHAR(255) NULL DEFAULT NULL,
                PRIMARY KEY (`id`, `user`),
                INDEX `fk_session_user_idx` (`user` ASC),
                CONSTRAINT `fk_session_user`
                FOREIGN KEY (`user`)
                REFERENCES `user` (`id`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION)
            ENGINE = InnoDB
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_general_ci
        ');
    }
}
