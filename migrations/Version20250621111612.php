<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250621111612 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
                // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
    WITH duplicates AS (
        SELECT id, slug,
               ROW_NUMBER() OVER (PARTITION BY slug ORDER BY id) AS row_num
        FROM product_entity
    )
    UPDATE product_entity
    SET slug = slug || '-' || (row_num - 1)
    FROM duplicates
    WHERE product_entity.id = duplicates.id AND duplicates.row_num > 1
");
        $this->addSql('DROP INDEX uniq_6c5405cc989d9b62');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE UNIQUE INDEX uniq_6c5405cc989d9b62 ON product_entity (slug)');
    }
}
