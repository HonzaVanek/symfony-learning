<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260916095717 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders ADD COLUMN customer_name VARCHAR(255) NOT NULL DEFAULT "Neznámý zákazník"');
        $this->addSql('ALTER TABLE orders ADD COLUMN created_at DATETIME NOT NULL DEFAULT "2026-09-16 12:15:00"');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__orders AS SELECT id, total FROM orders');
        $this->addSql('DROP TABLE orders');
        $this->addSql('CREATE TABLE orders (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, total INTEGER NOT NULL)');
        $this->addSql('INSERT INTO orders (id, total) SELECT id, total FROM __temp__orders');
        $this->addSql('DROP TABLE __temp__orders');
    }
}
