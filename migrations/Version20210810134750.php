<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210810134750 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE customer (
                id BIGSERIAL NOT NULL,
                full_name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                country VARCHAR(64) NOT NULL,
                username VARCHAR(255) NOT NULL,
                gender VARCHAR(32) NOT NULL,
                city VARCHAR(64) NOT NULL,
                phone VARCHAR(64) NOT NULL,
                PRIMARY KEY(id))'
        );

        $this->addSql(
            'CREATE INDEX customer_email_uindex on customer (email)'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE customer');
    }
}
