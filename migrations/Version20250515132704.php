<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250515132704 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates confirmation_codes table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE confirmation_codes (
                id INT AUTO_INCREMENT NOT NULL,
                code VARCHAR(6) NOT NULL,
                user_id INT NOT NULL,
                created_at DATETIME NOT NULL,
                PRIMARY KEY(id))
                DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);

        $this->addSql('CREATE UNIQUE INDEX confirmation_codes_code_idx USING HASH ON confirmation_codes(code)');
        $this->addSql('CREATE UNIQUE INDEX confirmation_codes_user_idx USING HASH ON confirmation_codes(user_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE confirmation_codes');
    }
}
