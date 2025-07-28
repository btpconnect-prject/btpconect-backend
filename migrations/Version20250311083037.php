<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250311083037 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie_entity ADD related_product_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN categorie_entity.related_product_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE categorie_entity ADD CONSTRAINT FK_880EEE51CF496EEA FOREIGN KEY (related_product_id) REFERENCES product_entity (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_880EEE51CF496EEA ON categorie_entity (related_product_id)');
        $this->addSql('ALTER TABLE product_entity ADD cover_image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE product_entity ADD previous_price DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE product_entity ADD rating INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product_entity ADD just_in BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE product_entity RENAME COLUMN name TO product_name');
        $this->addSql('ALTER TABLE product_entity RENAME COLUMN price TO current_price');
        $this->addSql('ALTER TABLE product_entity RENAME COLUMN qte TO pieces_sold');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE product_entity ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE product_entity ADD price DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE product_entity DROP product_name');
        $this->addSql('ALTER TABLE product_entity DROP current_price');
        $this->addSql('ALTER TABLE product_entity DROP cover_image');
        $this->addSql('ALTER TABLE product_entity DROP previous_price');
        $this->addSql('ALTER TABLE product_entity DROP rating');
        $this->addSql('ALTER TABLE product_entity DROP just_in');
        $this->addSql('ALTER TABLE product_entity RENAME COLUMN pieces_sold TO qte');
        $this->addSql('ALTER TABLE categorie_entity DROP CONSTRAINT FK_880EEE51CF496EEA');
        $this->addSql('DROP INDEX IDX_880EEE51CF496EEA');
        $this->addSql('ALTER TABLE categorie_entity DROP related_product_id');
    }
}
