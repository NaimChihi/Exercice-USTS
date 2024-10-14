<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241014191812 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE project_id_seq CASCADE');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT fk_2fb3d0ee979b1ad6');
        $this->addSql('DROP TABLE project');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4FBF094F26E94372 ON company (siret)');
        $this->addSql('ALTER TABLE user_company DROP role');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE project_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE project (id INT NOT NULL, company_id INT NOT NULL, title VARCHAR(255) NOT NULL, description TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_2fb3d0ee979b1ad6 ON project (company_id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT fk_2fb3d0ee979b1ad6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_company ADD role VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_4FBF094F26E94372');
    }
}
