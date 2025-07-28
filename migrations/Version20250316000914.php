<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250316000914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_entity DROP CONSTRAINT fk_6c5405cc3da5256d');
        $this->addSql('DROP INDEX idx_6c5405cc3da5256d');
        $this->addSql('ALTER TABLE product_entity DROP image_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE product_entity ADD image_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN product_entity.image_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE product_entity ADD CONSTRAINT fk_6c5405cc3da5256d FOREIGN KEY (image_id) REFERENCES media_object (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_6c5405cc3da5256d ON product_entity (image_id)');
    }
}
