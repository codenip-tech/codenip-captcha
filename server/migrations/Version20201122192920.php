<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201122192920 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Creates `user` table';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE `user` (
                id CHAR(36) NOT NULL PRIMARY KEY,
                name VARCHAR(50) NOT NULL,
                email VARCHAR(100) NOT NULL,
                password VARCHAR(100) NOT NULL,
                created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                UNIQUE U_email (email)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE `user`');
    }
}
