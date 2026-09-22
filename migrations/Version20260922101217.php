<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260922101217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
            INSERT INTO customer (name, email)
            SELECT DISTINCT customer_name, NULL
            FROM orders
            WHERE customer_id IS NULL
        ");

        $this->addSql("
            UPDATE orders
            SET customer_id = (
                SELECT customer.id
                FROM customer
                WHERE customer.name = orders.customer_name
                LIMIT 1
            )
            WHERE customer_id IS NULL
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('UPDATE orders SET customer_id = NULL');
        $this->addSql('DELETE FROM customer');
    }
}
