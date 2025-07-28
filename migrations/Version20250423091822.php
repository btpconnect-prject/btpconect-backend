<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250423091822 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address DROP CONSTRAINT fk_d4e6f81a76ed395');
        $this->addSql('DROP INDEX uniq_d4e6f81a76ed395');
        $this->addSql('ALTER TABLE address DROP user_id');
        $this->addSql('ALTER TABLE user_entity ADD adresse_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN user_entity.adresse_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE user_entity ADD CONSTRAINT FK_6B7A5F554DE7DC5C FOREIGN KEY (adresse_id) REFERENCES address (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6B7A5F554DE7DC5C ON user_entity (adresse_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE address ADD user_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN address.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT fk_d4e6f81a76ed395 FOREIGN KEY (user_id) REFERENCES user_entity (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_d4e6f81a76ed395 ON address (user_id)');
        $this->addSql('ALTER TABLE user_entity DROP CONSTRAINT FK_6B7A5F554DE7DC5C');
        $this->addSql('DROP INDEX IDX_6B7A5F554DE7DC5C');
        $this->addSql('ALTER TABLE user_entity DROP adresse_id');
    }
}
