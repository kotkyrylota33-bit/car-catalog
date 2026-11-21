<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261120233627 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__car AS SELECT id, model, year, engine, price, description FROM car');
        $this->addSql('DROP TABLE car');
        $this->addSql('CREATE TABLE car (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, model VARCHAR(255) NOT NULL, year INTEGER NOT NULL, engine VARCHAR(255) NOT NULL, price INTEGER NOT NULL, description CLOB DEFAULT NULL, brand_id INTEGER NOT NULL, CONSTRAINT FK_773DE69D44F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO car (id, model, year, engine, price, description) SELECT id, model, year, engine, price, description FROM __temp__car');
        $this->addSql('DROP TABLE __temp__car');
        $this->addSql('CREATE INDEX IDX_773DE69D44F5D008 ON car (brand_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__car AS SELECT id, model, year, engine, price, description FROM car');
        $this->addSql('DROP TABLE car');
        $this->addSql('CREATE TABLE car (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, model VARCHAR(255) NOT NULL, year VARCHAR(255) NOT NULL, engine VARCHAR(255) NOT NULL, price VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, brand VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO car (id, model, year, engine, price, description) SELECT id, model, year, engine, price, description FROM __temp__car');
        $this->addSql('DROP TABLE __temp__car');
    }
}
