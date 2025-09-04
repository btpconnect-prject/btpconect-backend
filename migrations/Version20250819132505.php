<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250819132505 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_entity ADD promo_name_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN product_entity.promo_name_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE product_entity ADD CONSTRAINT FK_6C5405CC796324E4 FOREIGN KEY (promo_name_id) REFERENCES settings (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6C5405CC796324E4 ON product_entity (promo_name_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE product_entity DROP CONSTRAINT FK_6C5405CC796324E4');
        $this->addSql('DROP INDEX IDX_6C5405CC796324E4');
        $this->addSql('ALTER TABLE product_entity DROP promo_name_id');
        $this->addSql('ALTER TABLE product_entity ALTER updated_at DROP NOT NULL');
        $this->addSql('ALTER TABLE product_entity ALTER created_at DROP NOT NULL');
        $this->addSql('ALTER TABLE product_entity ALTER product_caractors SET NOT NULL');
        $this->addSql('ALTER TABLE "order" ALTER cart DROP NOT NULL');
        $this->addSql('ALTER TABLE "order" ALTER status DROP NOT NULL');
    }
}
