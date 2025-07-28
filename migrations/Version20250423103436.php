<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250423103436 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_entity DROP CONSTRAINT fk_6b7a5f554de7dc5c');
        $this->addSql('DROP INDEX idx_6b7a5f554de7dc5c');
        $this->addSql('ALTER TABLE user_entity DROP adresse_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_entity ADD adresse_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN user_entity.adresse_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE user_entity ADD CONSTRAINT fk_6b7a5f554de7dc5c FOREIGN KEY (adresse_id) REFERENCES address (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_6b7a5f554de7dc5c ON user_entity (adresse_id)');
    }
}
