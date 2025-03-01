<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250301155137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Создание таблицы tasks';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
        CREATE TABLE tasks (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description TEXT,
            status BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL,
            created_by INTEGER NULL,
            updated_by INTEGER NULL,
            deleted_by INTEGER NULL
        )
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE tasks');
    }
}