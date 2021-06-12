<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20210612063448 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP INDEX idx_44fe52e254177093');
        $this->addSql('ALTER TABLE meetings ADD moderator_password VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE meetings ADD duration VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE meetings DROP deactivate_at');
        $this->addSql('ALTER TABLE meetings RENAME COLUMN name TO attendee_password');
        $this->addSql('ALTER TABLE meetings RENAME COLUMN activate_at TO start_time');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_44FE52E254177093 ON meetings (room_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_44FE52E254177093');
        $this->addSql('ALTER TABLE meetings ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE meetings ADD deactivate_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE meetings DROP attendee_password');
        $this->addSql('ALTER TABLE meetings DROP moderator_password');
        $this->addSql('ALTER TABLE meetings DROP duration');
        $this->addSql('ALTER TABLE meetings RENAME COLUMN start_time TO activate_at');
        $this->addSql('CREATE INDEX idx_44fe52e254177093 ON meetings (room_id)');
    }
}
