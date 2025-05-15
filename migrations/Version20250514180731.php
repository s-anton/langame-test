<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250514180731 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates news table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE news (
                id INT AUTO_INCREMENT NOT NULL,
                content VARCHAR(255) NOT NULL,
                url VARCHAR(255) NOT NULL,
                published_at DATETIME DEFAULT NULL,
                created_at DATETIME NOT NULL,
                PRIMARY KEY(id))
                DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);

        $this->addSql('CREATE INDEX news_published_at ON news (published_at)');
        $this->addSql('CREATE UNIQUE INDEX news_url USING HASH ON news (url)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE news');
    }
}
