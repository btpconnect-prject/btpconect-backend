<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250310181006 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_entity (id UUID NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION DEFAULT NULL, qte INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN product_entity.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE user_entity (id UUID NOT NULL, email VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, profile_picture VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN user_entity.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE user_entity_work_space_entity (user_entity_id UUID NOT NULL, work_space_entity_id UUID NOT NULL, PRIMARY KEY(user_entity_id, work_space_entity_id))');
        $this->addSql('CREATE INDEX IDX_FA1D4B1C81C5F0B9 ON user_entity_work_space_entity (user_entity_id)');
        $this->addSql('CREATE INDEX IDX_FA1D4B1C3A7749AA ON user_entity_work_space_entity (work_space_entity_id)');
        $this->addSql('COMMENT ON COLUMN user_entity_work_space_entity.user_entity_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_entity_work_space_entity.work_space_entity_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE work_space_entity (id UUID NOT NULL, title VARCHAR(255) NOT NULL, bg_image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN work_space_entity.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE user_entity_work_space_entity ADD CONSTRAINT FK_FA1D4B1C81C5F0B9 FOREIGN KEY (user_entity_id) REFERENCES user_entity (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_entity_work_space_entity ADD CONSTRAINT FK_FA1D4B1C3A7749AA FOREIGN KEY (work_space_entity_id) REFERENCES work_space_entity (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_entity_work_space_entity DROP CONSTRAINT FK_FA1D4B1C81C5F0B9');
        $this->addSql('ALTER TABLE user_entity_work_space_entity DROP CONSTRAINT FK_FA1D4B1C3A7749AA');
        $this->addSql('DROP TABLE product_entity');
        $this->addSql('DROP TABLE user_entity');
        $this->addSql('DROP TABLE user_entity_work_space_entity');
        $this->addSql('DROP TABLE work_space_entity');
    }
}
