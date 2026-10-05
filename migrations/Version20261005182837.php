<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261005182837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

   public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
    INSERT INTO statuses (name, title) VALUES
    ('new', 'Новая'),
    ('in_progress', 'В работе'),
    ('done', 'Готово')
    SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(
            "DELETE FROM statuses WHERE name IN ('new', 'in_progress', 'done')"
        );
    }
}
