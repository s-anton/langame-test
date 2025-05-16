<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250516091931 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates chats table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE chats (
                id VARCHAR(11) NOT NULL,
                created_at DATETIME NOT NULL,
                PRIMARY KEY(id))
                DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE chats');
    }
}
