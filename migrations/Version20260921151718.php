<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260921151718 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE customer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__orders AS SELECT id, total, customer_name, created_at FROM orders');
        $this->addSql('DROP TABLE orders');
        $this->addSql('CREATE TABLE orders (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, total INTEGER NOT NULL, customer_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, customer_id INTEGER DEFAULT NULL, CONSTRAINT FK_E52FFDEE9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO orders (id, total, customer_name, created_at) SELECT id, total, customer_name, created_at FROM __temp__orders');
        $this->addSql('DROP TABLE __temp__orders');
        $this->addSql('CREATE INDEX IDX_E52FFDEE9395C3F3 ON orders (customer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE customer');
        $this->addSql('CREATE TEMPORARY TABLE __temp__orders AS SELECT id, customer_name, total, created_at FROM orders');
        $this->addSql('DROP TABLE orders');
        $this->addSql('CREATE TABLE orders (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, customer_name VARCHAR(255) NOT NULL, total INTEGER NOT NULL, created_at DATETIME NOT NULL)');
        $this->addSql('INSERT INTO orders (id, customer_name, total, created_at) SELECT id, customer_name, total, created_at FROM __temp__orders');
        $this->addSql('DROP TABLE __temp__orders');
    }
}
