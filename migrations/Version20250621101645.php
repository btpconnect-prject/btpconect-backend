<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250621101645 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
// 1. Ajouter le champ slug nullable temporairement
    $this->addSql('ALTER TABLE product_entity ADD slug VARCHAR(255) DEFAULT NULL');

    // 2. Générer un slug basique pour les produits existants (à ajuster selon ton schéma)
    $this->addSql("
        UPDATE product_entity 
        SET slug = LOWER(REPLACE(product_name, ' ', '-')) 
        WHERE slug IS NULL
    ");

    // 3. Rendre le champ NOT NULL
    $this->addSql('ALTER TABLE product_entity ALTER COLUMN slug SET NOT NULL');
    

    // 4. Ajouter l’index unique
    $this->addSql('CREATE UNIQUE INDEX UNIQ_6C5405CC989D9B62 ON product_entity (slug)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_6C5405CC989D9B62');
        $this->addSql('ALTER TABLE product_entity DROP slug');
    }
}
