<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260101212825 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories (created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, name VARCHAR(255) NOT NULL, id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE receipts (created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, filename VARCHAR(255) NOT NULL, id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE transactions (created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, amount DOUBLE PRECISION NOT NULL, description VARCHAR(255) NOT NULL, date DATETIME NOT NULL, id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY (id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE receipts');
        $this->addSql('DROP TABLE transactions');
    }
}
