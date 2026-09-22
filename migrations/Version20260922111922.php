<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260922111922 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__orders AS SELECT id, total, created_at, customer_id FROM orders');
        $this->addSql('DROP TABLE orders');
        $this->addSql('CREATE TABLE orders (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, total INTEGER NOT NULL, created_at DATETIME NOT NULL, customer_id INTEGER NOT NULL, CONSTRAINT FK_E52FFDEE9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO orders (id, total, created_at, customer_id) SELECT id, total, created_at, customer_id FROM __temp__orders');
        $this->addSql('DROP TABLE __temp__orders');
        $this->addSql('CREATE INDEX IDX_E52FFDEE9395C3F3 ON orders (customer_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql(
            'CREATE TEMPORARY TABLE __temp__orders AS
            SELECT
                o.id,
                o.total,
                o.created_at,
                o.customer_id,
                c.name AS customer_name
            FROM orders o
            LEFT JOIN customer c ON c.id = o.customer_id'
        );

        $this->addSql('DROP TABLE orders');

        $this->addSql(
            'CREATE TABLE orders (
                id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                total INTEGER NOT NULL,
                created_at DATETIME NOT NULL,
                customer_id INTEGER DEFAULT NULL,
                customer_name VARCHAR(255) NOT NULL,
                CONSTRAINT FK_E52FFDEE9395C3F3
                    FOREIGN KEY (customer_id)
                    REFERENCES customer (id)
                    NOT DEFERRABLE INITIALLY IMMEDIATE
            )'
        );

        $this->addSql(
            'INSERT INTO orders (
                id,
                total,
                created_at,
                customer_id,
                customer_name
            )
            SELECT
                id,
                total,
                created_at,
                customer_id,
                customer_name
            FROM __temp__orders'
        );

        $this->addSql('DROP TABLE __temp__orders');
        $this->addSql(
            'CREATE INDEX IDX_E52FFDEE9395C3F3 ON orders (customer_id)'
        );
    }
}
