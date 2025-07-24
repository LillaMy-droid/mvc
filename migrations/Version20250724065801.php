<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250724065801 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE product
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__library AS SELECT id, titel, isbn, author, image FROM library
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE library
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE library (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, titel VARCHAR(255) NOT NULL, isbn VARCHAR(255) NOT NULL, author VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO library (id, titel, isbn, author, image) SELECT id, titel, isbn, author, image FROM __temp__library
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__library
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE "BINARY", value INTEGER NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__library AS SELECT id, titel, isbn, author, image FROM library
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE library
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE library (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, titel VARCHAR(255) NOT NULL, isbn VARCHAR(255) NOT NULL, author VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO library (id, titel, isbn, author, image) SELECT id, titel, isbn, author, image FROM __temp__library
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__library
        SQL);
    }
}
