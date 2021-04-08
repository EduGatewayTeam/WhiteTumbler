<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20210405074812 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE moderators (room_id UUID NOT NULL, user_id UUID NOT NULL, registered_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(room_id, user_id))');
        $this->addSql('CREATE INDEX IDX_580D16D354177093 ON moderators (room_id)');
        $this->addSql('CREATE INDEX IDX_580D16D3A76ED395 ON moderators (user_id)');
        $this->addSql('CREATE TABLE settings (id UUID NOT NULL, mute_on_startup BOOLEAN NOT NULL, expect_moderator BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE moderators ADD CONSTRAINT FK_580D16D354177093 FOREIGN KEY (room_id) REFERENCES rooms (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE moderators ADD CONSTRAINT FK_580D16D3A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE meetings ADD setting_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE meetings ADD CONSTRAINT FK_44FE52E2EE35BD72 FOREIGN KEY (setting_id) REFERENCES settings (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_44FE52E2EE35BD72 ON meetings (setting_id)');
        $this->addSql('ALTER TABLE rooms ADD default_meeting_setting_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE rooms ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE rooms ADD CONSTRAINT FK_7CA11A9637E04D20 FOREIGN KEY (default_meeting_setting_id) REFERENCES settings (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_7CA11A9637E04D20 ON rooms (default_meeting_setting_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE meetings DROP CONSTRAINT FK_44FE52E2EE35BD72');
        $this->addSql('ALTER TABLE rooms DROP CONSTRAINT FK_7CA11A9637E04D20');
        $this->addSql('DROP TABLE moderators');
        $this->addSql('DROP TABLE settings');
        $this->addSql('DROP INDEX IDX_44FE52E2EE35BD72');
        $this->addSql('ALTER TABLE meetings DROP setting_id');
        $this->addSql('DROP INDEX IDX_7CA11A9637E04D20');
        $this->addSql('ALTER TABLE rooms DROP default_meeting_setting_id');
        $this->addSql('ALTER TABLE rooms ALTER id SET DEFAULT \'uuid_generate_v4()\'');
    }
}
