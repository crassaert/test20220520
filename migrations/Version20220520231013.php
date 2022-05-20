<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220520231013 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE manager_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product_availability_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE shop_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE manager (id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE product (id INT NOT NULL, name VARCHAR(255) NOT NULL, picture_url VARCHAR(2048) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE product_availability (id INT NOT NULL, shop_id INT NOT NULL, product_id INT NOT NULL, availability INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B21380D44D16C4DD ON product_availability (shop_id)');
        $this->addSql('CREATE INDEX IDX_B21380D44584665A ON product_availability (product_id)');
        $this->addSql('CREATE TABLE shop (id INT NOT NULL, manager_id INT NOT NULL, name VARCHAR(255) NOT NULL, lat NUMERIC(20, 16) NOT NULL, lng NUMERIC(20, 16) NOT NULL, postal_address TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AC6A4CA2783E3463 ON shop (manager_id)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE product_availability ADD CONSTRAINT FK_B21380D44D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_availability ADD CONSTRAINT FK_B21380D44584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shop ADD CONSTRAINT FK_AC6A4CA2783E3463 FOREIGN KEY (manager_id) REFERENCES manager (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE shop DROP CONSTRAINT FK_AC6A4CA2783E3463');
        $this->addSql('ALTER TABLE product_availability DROP CONSTRAINT FK_B21380D44584665A');
        $this->addSql('ALTER TABLE product_availability DROP CONSTRAINT FK_B21380D44D16C4DD');
        $this->addSql('DROP SEQUENCE manager_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE product_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE product_availability_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE shop_id_seq CASCADE');
        $this->addSql('DROP TABLE manager');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_availability');
        $this->addSql('DROP TABLE shop');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
