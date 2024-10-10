<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241010131659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE company_id_seq1 CASCADE');
        $this->addSql('DROP SEQUENCE project_id_seq1 CASCADE');
        $this->addSql('CREATE SEQUENCE user_company_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE user_company (id INT NOT NULL, user_account_id INT NOT NULL, company_id INT NOT NULL, role VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_17B217453C0C9956 ON user_company (user_account_id)');
        $this->addSql('CREATE INDEX IDX_17B21745979B1AD6 ON user_company (company_id)');
        $this->addSql('ALTER TABLE user_company ADD CONSTRAINT FK_17B217453C0C9956 FOREIGN KEY (user_account_id) REFERENCES app_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_company ADD CONSTRAINT FK_17B21745979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_user ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE company ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE project ALTER id DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE user_company_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE company_id_seq1 INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE project_id_seq1 INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE user_company DROP CONSTRAINT FK_17B217453C0C9956');
        $this->addSql('ALTER TABLE user_company DROP CONSTRAINT FK_17B21745979B1AD6');
        $this->addSql('DROP TABLE user_company');
        $this->addSql('CREATE SEQUENCE company_id_seq');
        $this->addSql('SELECT setval(\'company_id_seq\', (SELECT MAX(id) FROM company))');
        $this->addSql('ALTER TABLE company ALTER id SET DEFAULT nextval(\'company_id_seq\')');
        $this->addSql('CREATE SEQUENCE app_user_id_seq');
        $this->addSql('SELECT setval(\'app_user_id_seq\', (SELECT MAX(id) FROM app_user))');
        $this->addSql('ALTER TABLE app_user ALTER id SET DEFAULT nextval(\'app_user_id_seq\')');
        $this->addSql('CREATE SEQUENCE project_id_seq');
        $this->addSql('SELECT setval(\'project_id_seq\', (SELECT MAX(id) FROM project))');
        $this->addSql('ALTER TABLE project ALTER id SET DEFAULT nextval(\'project_id_seq\')');
    }
}
