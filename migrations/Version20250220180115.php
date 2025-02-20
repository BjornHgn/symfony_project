<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250220180115 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)');
        $this->addSql('ALTER TABLE article ADD COLUMN sous_categorie VARCHAR(100) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TEMPORARY TABLE __temp__article AS SELECT id, nom, categorie, genre, prix, description, image, created_at, author_id FROM article');
        $this->addSql('DROP TABLE article');
        $this->addSql('CREATE TABLE article (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, categorie VARCHAR(100) NOT NULL, genre VARCHAR(50) NOT NULL, prix DOUBLE PRECISION NOT NULL, description CLOB NOT NULL, image VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, author_id INTEGER NOT NULL)');
        $this->addSql('INSERT INTO article (id, nom, categorie, genre, prix, description, image, created_at, author_id) SELECT id, nom, categorie, genre, prix, description, image, created_at, author_id FROM __temp__article');
        $this->addSql('DROP TABLE __temp__article');
    }
}
