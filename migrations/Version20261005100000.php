<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261005100000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create tasks and statuses tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE statuses (id SERIAL NOT NULL, name VARCHAR(100) NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_STATUS_NAME ON statuses (name)');
        $this->addSql('CREATE TABLE tasks (id SERIAL NOT NULL, status_id INT NOT NULL, title VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_TASK_STATUS ON tasks (status_id)');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_TASK_STATUS FOREIGN KEY (status_id) REFERENCES statuses (id) ON DELETE RESTRICT NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tasks DROP CONSTRAINT FK_TASK_STATUS');
        $this->addSql('DROP TABLE tasks');
        $this->addSql('DROP TABLE statuses');
    }
}
