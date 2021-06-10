<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20210609075752 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE shedules (week_day VARCHAR(255) NOT NULL, week_type VARCHAR(255) NOT NULL, room_id UUID NOT NULL, start_time TIME(0) WITHOUT TIME ZONE NOT NULL, end_time TIME(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(room_id, week_day, week_type))');
        $this->addSql('CREATE INDEX room_join_index ON shedules (room_id)');
        $this->addSql('ALTER TABLE shedules ADD CONSTRAINT FK_785D97254177093 FOREIGN KEY (room_id) REFERENCES rooms (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE shedules');
    }
}
