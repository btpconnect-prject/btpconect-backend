<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250311095258 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie_entity DROP CONSTRAINT fk_880eee51cf496eea');
        $this->addSql('DROP INDEX idx_880eee51cf496eea');
        $this->addSql('ALTER TABLE categorie_entity DROP related_product_id');
        $this->addSql('ALTER TABLE product_entity ADD category_id UUID NOT NULL');
        $this->addSql('COMMENT ON COLUMN product_entity.category_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE product_entity ADD CONSTRAINT FK_6C5405CC12469DE2 FOREIGN KEY (category_id) REFERENCES categorie_entity (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6C5405CC12469DE2 ON product_entity (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE product_entity DROP CONSTRAINT FK_6C5405CC12469DE2');
        $this->addSql('DROP INDEX IDX_6C5405CC12469DE2');
        $this->addSql('ALTER TABLE product_entity DROP category_id');
        $this->addSql('ALTER TABLE categorie_entity ADD related_product_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN categorie_entity.related_product_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE categorie_entity ADD CONSTRAINT fk_880eee51cf496eea FOREIGN KEY (related_product_id) REFERENCES product_entity (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_880eee51cf496eea ON categorie_entity (related_product_id)');
    }
}
