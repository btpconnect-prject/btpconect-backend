<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250803185745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE promotion (id SERIAL NOT NULL, product_id UUID NOT NULL, discount_rate DOUBLE PRECISION NOT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C11D7DD14584665A ON promotion (product_id)');
        $this->addSql('COMMENT ON COLUMN promotion.product_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD14584665A FOREIGN KEY (product_id) REFERENCES product_entity (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_entity ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE NULL DEFAULT NULL');
        $this->addSql('ALTER TABLE product_entity ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NULL DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN product_entity.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN product_entity.created_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE promotion DROP CONSTRAINT FK_C11D7DD14584665A');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('ALTER TABLE "order" ALTER cart DROP NOT NULL');
        $this->addSql('ALTER TABLE "order" ALTER status DROP NOT NULL');
        $this->addSql('ALTER TABLE product_entity DROP updated_at');
        $this->addSql('ALTER TABLE product_entity DROP created_at');
        $this->addSql('ALTER TABLE product_entity ALTER product_caractors SET NOT NULL');
    }
}
