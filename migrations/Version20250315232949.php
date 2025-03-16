<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250315232949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_object ADD product_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN media_object.product_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE media_object ADD CONSTRAINT FK_14D431324584665A FOREIGN KEY (product_id) REFERENCES product_entity (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_14D431324584665A ON media_object (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE media_object DROP CONSTRAINT FK_14D431324584665A');
        $this->addSql('DROP INDEX IDX_14D431324584665A');
        $this->addSql('ALTER TABLE media_object DROP product_id');
    }
}
