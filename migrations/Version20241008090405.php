<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241008090405 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create company and project tables and the relationships';
    }

    public function up(Schema $schema): void
    {
        // Create sequences for id generation
        $this->addSql('CREATE SEQUENCE company_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE project_id_seq INCREMENT BY 1 MINVALUE 1 START 1');

        // Create the company table
        $this->addSql('CREATE TABLE company (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            siret VARCHAR(14) NOT NULL,
            address VARCHAR(255) NOT NULL
        )');

        // Create the company_user join table
        $this->addSql('CREATE TABLE company_user (
            company_id INT NOT NULL,
            user_id INT NOT NULL,
            PRIMARY KEY(company_id, user_id)
        )');
        $this->addSql('CREATE INDEX IDX_CEFECCA7979B1AD6 ON company_user (company_id)');
        $this->addSql('CREATE INDEX IDX_CEFECCA7A76ED395 ON company_user (user_id)');

        // Create the project table
        $this->addSql('CREATE TABLE project (
            id SERIAL PRIMARY KEY,
            company_id INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
        )');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE979B1AD6 ON project (company_id)');

        // Foreign key constraints
        $this->addSql('ALTER TABLE company_user ADD CONSTRAINT FK_CEFECCA7979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE company_user ADD CONSTRAINT FK_CEFECCA7A76ED395 FOREIGN KEY (user_id) REFERENCES app_user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // Drop the tables and constraints
        $this->addSql('ALTER TABLE company_user DROP CONSTRAINT FK_CEFECCA7979B1AD6');
        $this->addSql('ALTER TABLE company_user DROP CONSTRAINT FK_CEFECCA7A76ED395');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT FK_2FB3D0EE979B1AD6');
        
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE company_user');
        $this->addSql('DROP TABLE project');

        // Drop sequences
        $this->addSql('DROP SEQUENCE company_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE project_id_seq CASCADE');
    }
}
