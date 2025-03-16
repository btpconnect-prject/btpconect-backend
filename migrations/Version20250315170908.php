<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250315170908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_object DROP image_size');
        $this->addSql('ALTER TABLE media_object DROP updated_at');
        $this->addSql('ALTER TABLE media_object RENAME COLUMN image_name TO file_path');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE media_object ADD image_size INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media_object ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE media_object RENAME COLUMN file_path TO image_name');
        $this->addSql('COMMENT ON COLUMN media_object.updated_at IS \'(DC2Type:datetime_immutable)\'');
    }
}
