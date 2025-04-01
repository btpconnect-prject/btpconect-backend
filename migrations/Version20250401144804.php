<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250401144804 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_entity ADD profile_picture_main_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN user_entity.profile_picture_main_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE user_entity ADD CONSTRAINT FK_6B7A5F55121EFD72 FOREIGN KEY (profile_picture_main_id) REFERENCES media_object (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6B7A5F55121EFD72 ON user_entity (profile_picture_main_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_entity DROP CONSTRAINT FK_6B7A5F55121EFD72');
        $this->addSql('DROP INDEX UNIQ_6B7A5F55121EFD72');
        $this->addSql('ALTER TABLE user_entity DROP profile_picture_main_id');
    }
}
