<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250930102559 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification DROP CONSTRAINT FK_BF5476CAFDC6F10B');
        $this->addSql('ALTER TABLE notification ALTER user_notification_id SET NOT NULL');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAFDC6F10B FOREIGN KEY (user_notification_id) REFERENCES user_entity (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "order" ALTER cart DROP NOT NULL');
        $this->addSql('ALTER TABLE "order" ALTER status DROP NOT NULL');
        $this->addSql('ALTER TABLE notification DROP CONSTRAINT fk_bf5476cafdc6f10b');
        $this->addSql('ALTER TABLE notification ALTER user_notification_id DROP NOT NULL');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT fk_bf5476cafdc6f10b FOREIGN KEY (user_notification_id) REFERENCES user_entity (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_entity ALTER updated_at DROP NOT NULL');
        $this->addSql('ALTER TABLE product_entity ALTER created_at DROP NOT NULL');
        $this->addSql('ALTER TABLE product_entity ALTER product_caractors SET NOT NULL');
    }
}
