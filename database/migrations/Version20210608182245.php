<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20210608182245 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE meetings_moderators');
        $this->addSql('ALTER TABLE users ADD patronymic VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE users ALTER tsvector DROP NOT NULL');
        $this->addSql('ALTER TABLE users RENAME COLUMN family_name TO surname');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE meetings_moderators (meeting_id UUID NOT NULL, user_id UUID NOT NULL, PRIMARY KEY(meeting_id, user_id))');
        $this->addSql('CREATE INDEX idx_a1d7ea767433d9c ON meetings_moderators (meeting_id)');
        $this->addSql('CREATE INDEX idx_a1d7ea7a76ed395 ON meetings_moderators (user_id)');
        $this->addSql('ALTER TABLE meetings_moderators ADD CONSTRAINT fk_a1d7ea767433d9c FOREIGN KEY (meeting_id) REFERENCES meetings (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE meetings_moderators ADD CONSTRAINT fk_a1d7ea7a76ed395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users ADD family_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE users DROP surname');
        $this->addSql('ALTER TABLE users DROP patronymic');
        $this->addSql('ALTER TABLE users ALTER tsvector SET NOT NULL');
    }
}
