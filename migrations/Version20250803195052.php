<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250803195052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_entity ADD is_verified BOOLEAN DEFAULT NULL');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "order" ALTER cart DROP NOT NULL');
        $this->addSql('ALTER TABLE "order" ALTER status DROP NOT NULL');
        $this->addSql('ALTER TABLE product_entity DROP is_verified');
        $this->addSql('ALTER TABLE product_entity ALTER updated_at DROP NOT NULL');
        $this->addSql('ALTER TABLE product_entity ALTER created_at DROP NOT NULL');
        $this->addSql('ALTER TABLE product_entity ALTER product_caractors SET NOT NULL');
    }
}
