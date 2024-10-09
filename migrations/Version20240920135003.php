<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240920135003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create app_user table';
    }

    public function up(Schema $schema): void
    {
        // Create the app_user table with correct syntax for PostgreSQL
        $this->addSql('CREATE TABLE app_user (
            id SERIAL PRIMARY KEY,
            email VARCHAR(180) NOT NULL,
            roles JSON NOT NULL,
            password VARCHAR(255) NOT NULL
        )');
        
        // Create a unique index on the email field
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON app_user (email)');
    }

    public function down(Schema $schema): void
    {
        // Drop the app_user table
        $this->addSql('DROP TABLE app_user');
    }
}
