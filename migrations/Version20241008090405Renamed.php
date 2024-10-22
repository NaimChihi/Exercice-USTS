<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241014184045Renamed extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        // Commenter ou supprimer l'ajout de la colonne identifier
        // $this->addSql('ALTER TABLE company ADD identifier VARCHAR(14) NOT NULL');
        
        $this->addSql('DROP SEQUENCE project_id_seq CASCADE');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT fk_2fb3d0ee979b1ad6');
        $this->addSql('DROP TABLE project');
        $this->addSql('ALTER TABLE user_company DROP role');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        // Si vous avez besoin de rajouter identifier ici aussi, décommentez la ligne
        // $this->addSql('ALTER TABLE company ADD identifier VARCHAR(14) NOT NULL');
        $this->addSql('ALTER TABLE user_company ADD role VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_4FBF094F772E836A');
        $this->addSql('DROP INDEX UNIQ_4FBF094F26E94372');
        $this->addSql('ALTER TABLE company DROP identifier'); // Si vous souhaitez retirer identifier lors d'un rollback
    }
}
