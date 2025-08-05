<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250805163253 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE settings (id UUID NOT NULL, config_name VARCHAR(255) NOT NULL, string_value VARCHAR(255) DEFAULT NULL, int_value INT DEFAULT NULL, bool_value BOOLEAN DEFAULT NULL, json_value JSON DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN settings.id IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE settings');
        $this->addSql('ALTER TABLE product_entity ALTER updated_at DROP NOT NULL');
        $this->addSql('ALTER TABLE product_entity ALTER created_at DROP NOT NULL');
        $this->addSql('ALTER TABLE product_entity ALTER product_caractors SET NOT NULL');
        $this->addSql('ALTER TABLE "order" ALTER cart DROP NOT NULL');
        $this->addSql('ALTER TABLE "order" ALTER status DROP NOT NULL');
    }
}
