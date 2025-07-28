<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250310181333 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie_entity (id UUID NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, icon VARCHAR(255) DEFAULT NULL, created_at DATE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_featured BOOLEAN NOT NULL, is_sub_category BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN categorie_entity.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN categorie_entity.created_at IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN categorie_entity.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE categorie_entity_categorie_entity (categorie_entity_source UUID NOT NULL, categorie_entity_target UUID NOT NULL, PRIMARY KEY(categorie_entity_source, categorie_entity_target))');
        $this->addSql('CREATE INDEX IDX_F419D356988820B4 ON categorie_entity_categorie_entity (categorie_entity_source)');
        $this->addSql('CREATE INDEX IDX_F419D356816D703B ON categorie_entity_categorie_entity (categorie_entity_target)');
        $this->addSql('COMMENT ON COLUMN categorie_entity_categorie_entity.categorie_entity_source IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN categorie_entity_categorie_entity.categorie_entity_target IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE categorie_entity_categorie_entity ADD CONSTRAINT FK_F419D356988820B4 FOREIGN KEY (categorie_entity_source) REFERENCES categorie_entity (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE categorie_entity_categorie_entity ADD CONSTRAINT FK_F419D356816D703B FOREIGN KEY (categorie_entity_target) REFERENCES categorie_entity (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE categorie_entity_categorie_entity DROP CONSTRAINT FK_F419D356988820B4');
        $this->addSql('ALTER TABLE categorie_entity_categorie_entity DROP CONSTRAINT FK_F419D356816D703B');
        $this->addSql('DROP TABLE categorie_entity');
        $this->addSql('DROP TABLE categorie_entity_categorie_entity');
    }
}
