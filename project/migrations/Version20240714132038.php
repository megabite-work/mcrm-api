<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240714132038 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create user, user_credentials, user_security, user_phone, user_store';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_credential_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_security_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_store_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, qr_code VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME ON "user" (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)');
        $this->addSql('CREATE TABLE user_credential (id INT NOT NULL, user_id INT NOT NULL, type VARCHAR(255) NOT NULL, value TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_security (id INT NOT NULL, user_id INT NOT NULL, ip VARCHAR(255) NOT NULL, security INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_store (id INT NOT NULL, user_id INT NOT NULL, store_id INT NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE user_credential_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_security_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_store_id_seq CASCADE');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_credential');
        $this->addSql('DROP TABLE user_security');
        $this->addSql('DROP TABLE user_store');
    }
}
