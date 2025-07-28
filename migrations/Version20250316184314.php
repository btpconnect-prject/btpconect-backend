<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250316184314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_entity ADD image_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN product_entity.image_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE product_entity ADD CONSTRAINT FK_6C5405CC3DA5256D FOREIGN KEY (image_id) REFERENCES media_object (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6C5405CC3DA5256D ON product_entity (image_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE product_entity DROP CONSTRAINT FK_6C5405CC3DA5256D');
        $this->addSql('DROP INDEX IDX_6C5405CC3DA5256D');
        $this->addSql('ALTER TABLE product_entity DROP image_id');
    }
}
