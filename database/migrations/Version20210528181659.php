<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20210528181659 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE meetings_moderators (meeting_id UUID NOT NULL, user_id UUID NOT NULL, PRIMARY KEY(meeting_id, user_id))');
        $this->addSql('CREATE INDEX IDX_A1D7EA767433D9C ON meetings_moderators (meeting_id)');
        $this->addSql('CREATE INDEX IDX_A1D7EA7A76ED395 ON meetings_moderators (user_id)');
        $this->addSql('CREATE TABLE rooms_moderators (room_id UUID NOT NULL, user_id UUID NOT NULL, PRIMARY KEY(room_id, user_id))');
        $this->addSql('CREATE INDEX IDX_3248DEED54177093 ON rooms_moderators (room_id)');
        $this->addSql('CREATE INDEX IDX_3248DEEDA76ED395 ON rooms_moderators (user_id)');
        $this->addSql('ALTER TABLE meetings_moderators ADD CONSTRAINT FK_A1D7EA767433D9C FOREIGN KEY (meeting_id) REFERENCES meetings (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE meetings_moderators ADD CONSTRAINT FK_A1D7EA7A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rooms_moderators ADD CONSTRAINT FK_3248DEED54177093 FOREIGN KEY (room_id) REFERENCES rooms (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rooms_moderators ADD CONSTRAINT FK_3248DEEDA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE meetings DROP CONSTRAINT FK_44FE52E254177093');
        $this->addSql('ALTER TABLE meetings ALTER activate_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE meetings ALTER activate_at DROP DEFAULT');
        $this->addSql('ALTER TABLE meetings ALTER deactivate_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE meetings ALTER deactivate_at DROP DEFAULT');
        $this->addSql('ALTER TABLE meetings ADD CONSTRAINT FK_44FE52E254177093 FOREIGN KEY (room_id) REFERENCES rooms (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rooms DROP CONSTRAINT FK_7CA11A9661220EA6');
        $this->addSql('ALTER TABLE rooms ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE rooms ADD CONSTRAINT FK_7CA11A9661220EA6 FOREIGN KEY (creator_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE meetings_moderators');
        $this->addSql('DROP TABLE rooms_moderators');
        $this->addSql('ALTER TABLE rooms DROP CONSTRAINT fk_7ca11a9661220ea6');
        $this->addSql('ALTER TABLE rooms ALTER id SET DEFAULT \'uuid_generate_v4()\'');
        $this->addSql('ALTER TABLE rooms ADD CONSTRAINT fk_7ca11a9661220ea6 FOREIGN KEY (creator_id) REFERENCES users (id) ON UPDATE CASCADE ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE meetings DROP CONSTRAINT fk_44fe52e254177093');
        $this->addSql('ALTER TABLE meetings ALTER activate_at TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE meetings ALTER activate_at DROP DEFAULT');
        $this->addSql('ALTER TABLE meetings ALTER deactivate_at TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE meetings ALTER deactivate_at DROP DEFAULT');
        $this->addSql('ALTER TABLE meetings ADD CONSTRAINT fk_44fe52e254177093 FOREIGN KEY (room_id) REFERENCES rooms (id) ON UPDATE CASCADE ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
