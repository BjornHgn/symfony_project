<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250221101202 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__article AS SELECT id, nom, categorie, genre, prix, description, image, created_at, author_id FROM article');
        $this->addSql('DROP TABLE article');
        $this->addSql('CREATE TABLE article (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER NOT NULL, nom VARCHAR(255) NOT NULL, categorie VARCHAR(100) NOT NULL, genre VARCHAR(50) NOT NULL, prix DOUBLE PRECISION NOT NULL, description CLOB NOT NULL, image VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, CONSTRAINT FK_23A0E66F675F31B FOREIGN KEY (author_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO article (id, nom, categorie, genre, prix, description, image, created_at, author_id) SELECT id, nom, categorie, genre, prix, description, image, created_at, author_id FROM __temp__article');
        $this->addSql('DROP TABLE __temp__article');
        $this->addSql('CREATE INDEX IDX_23A0E66F675F31B ON article (author_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__cart AS SELECT id, user_id, article_id FROM cart');
        $this->addSql('DROP TABLE cart');
        $this->addSql('CREATE TABLE cart (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, article_id INTEGER NOT NULL, CONSTRAINT FK_BA388B7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_BA388B77294869C FOREIGN KEY (article_id) REFERENCES article (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO cart (id, user_id, article_id) SELECT id, user_id, article_id FROM __temp__cart');
        $this->addSql('DROP TABLE __temp__cart');
        $this->addSql('CREATE INDEX IDX_BA388B7A76ED395 ON cart (user_id)');
        $this->addSql('CREATE INDEX IDX_BA388B77294869C ON cart (article_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__invoice AS SELECT id, user_id, deal_date, amount, facturation_address, facturation_city, facturation_zipcode FROM invoice');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('CREATE TABLE invoice (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, deal_date DATETIME NOT NULL, amount NUMERIC(10, 2) NOT NULL, facturation_address VARCHAR(255) NOT NULL, facturation_city VARCHAR(255) NOT NULL, facturation_zipcode INTEGER NOT NULL, CONSTRAINT FK_90651744A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO invoice (id, user_id, deal_date, amount, facturation_address, facturation_city, facturation_zipcode) SELECT id, user_id, deal_date, amount, facturation_address, facturation_city, facturation_zipcode FROM __temp__invoice');
        $this->addSql('DROP TABLE __temp__invoice');
        $this->addSql('CREATE INDEX IDX_90651744A76ED395 ON invoice (user_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__stock AS SELECT id, article_id, nbr_article FROM stock');
        $this->addSql('DROP TABLE stock');
        $this->addSql('CREATE TABLE stock (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, article_id INTEGER NOT NULL, nbr_article INTEGER NOT NULL, CONSTRAINT FK_4B3656607294869C FOREIGN KEY (article_id) REFERENCES article (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO stock (id, article_id, nbr_article) SELECT id, article_id, nbr_article FROM __temp__stock');
        $this->addSql('DROP TABLE __temp__stock');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4B3656607294869C ON stock (article_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__article AS SELECT id, author_id, nom, categorie, genre, prix, description, image, created_at FROM article');
        $this->addSql('DROP TABLE article');
        $this->addSql('CREATE TABLE article (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER NOT NULL, nom VARCHAR(255) NOT NULL, categorie VARCHAR(100) NOT NULL, genre VARCHAR(50) NOT NULL, prix DOUBLE PRECISION NOT NULL, description CLOB NOT NULL, image VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL)');
        $this->addSql('INSERT INTO article (id, author_id, nom, categorie, genre, prix, description, image, created_at) SELECT id, author_id, nom, categorie, genre, prix, description, image, created_at FROM __temp__article');
        $this->addSql('DROP TABLE __temp__article');
        $this->addSql('CREATE TEMPORARY TABLE __temp__cart AS SELECT id, user_id, article_id FROM cart');
        $this->addSql('DROP TABLE cart');
        $this->addSql('CREATE TABLE cart (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, article_id INTEGER NOT NULL)');
        $this->addSql('INSERT INTO cart (id, user_id, article_id) SELECT id, user_id, article_id FROM __temp__cart');
        $this->addSql('DROP TABLE __temp__cart');
        $this->addSql('CREATE TEMPORARY TABLE __temp__invoice AS SELECT id, user_id, deal_date, amount, facturation_address, facturation_city, facturation_zipcode FROM invoice');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('CREATE TABLE invoice (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, deal_date DATETIME NOT NULL, amount NUMERIC(10, 2) NOT NULL, facturation_address VARCHAR(255) NOT NULL, facturation_city VARCHAR(255) NOT NULL, facturation_zipcode INTEGER NOT NULL)');
        $this->addSql('INSERT INTO invoice (id, user_id, deal_date, amount, facturation_address, facturation_city, facturation_zipcode) SELECT id, user_id, deal_date, amount, facturation_address, facturation_city, facturation_zipcode FROM __temp__invoice');
        $this->addSql('DROP TABLE __temp__invoice');
        $this->addSql('CREATE TEMPORARY TABLE __temp__stock AS SELECT id, article_id, nbr_article FROM stock');
        $this->addSql('DROP TABLE stock');
        $this->addSql('CREATE TABLE stock (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, article_id INTEGER NOT NULL, nbr_article INTEGER NOT NULL)');
        $this->addSql('INSERT INTO stock (id, article_id, nbr_article) SELECT id, article_id, nbr_article FROM __temp__stock');
        $this->addSql('DROP TABLE __temp__stock');
    }
}
