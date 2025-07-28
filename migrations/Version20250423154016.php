<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250423154016 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_product_entity (order_id UUID NOT NULL, product_entity_id UUID NOT NULL, PRIMARY KEY(order_id, product_entity_id))');
        $this->addSql('CREATE INDEX IDX_424D57C8D9F6D38 ON order_product_entity (order_id)');
        $this->addSql('CREATE INDEX IDX_424D57CEF85CBD0 ON order_product_entity (product_entity_id)');
        $this->addSql('COMMENT ON COLUMN order_product_entity.order_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN order_product_entity.product_entity_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE order_product_entity ADD CONSTRAINT FK_424D57C8D9F6D38 FOREIGN KEY (order_id) REFERENCES "order" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_product_entity ADD CONSTRAINT FK_424D57CEF85CBD0 FOREIGN KEY (product_entity_id) REFERENCES product_entity (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_entity_order DROP CONSTRAINT fk_d479840aef85cbd0');
        $this->addSql('ALTER TABLE product_entity_order DROP CONSTRAINT fk_d479840a8d9f6d38');
        $this->addSql('DROP TABLE product_entity_order');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE product_entity_order (product_entity_id UUID NOT NULL, order_id UUID NOT NULL, PRIMARY KEY(product_entity_id, order_id))');
        $this->addSql('CREATE INDEX idx_d479840a8d9f6d38 ON product_entity_order (order_id)');
        $this->addSql('CREATE INDEX idx_d479840aef85cbd0 ON product_entity_order (product_entity_id)');
        $this->addSql('COMMENT ON COLUMN product_entity_order.product_entity_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN product_entity_order.order_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE product_entity_order ADD CONSTRAINT fk_d479840aef85cbd0 FOREIGN KEY (product_entity_id) REFERENCES product_entity (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_entity_order ADD CONSTRAINT fk_d479840a8d9f6d38 FOREIGN KEY (order_id) REFERENCES "order" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_product_entity DROP CONSTRAINT FK_424D57C8D9F6D38');
        $this->addSql('ALTER TABLE order_product_entity DROP CONSTRAINT FK_424D57CEF85CBD0');
        $this->addSql('DROP TABLE order_product_entity');
    }
}
