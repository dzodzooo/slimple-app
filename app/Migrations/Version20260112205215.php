<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260112205215 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE verification_codes (code VARCHAR(255) NOT NULL, id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, INDEX IDX_7B56601DA76ED395 (user_id), PRIMARY KEY (id))');
        $this->addSql('ALTER TABLE verification_codes ADD CONSTRAINT FK_7B56601DA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE verification_codes DROP FOREIGN KEY FK_7B56601DA76ED395');
        $this->addSql('DROP TABLE verification_codes');
    }
}
