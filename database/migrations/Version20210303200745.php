<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20210303200745 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE rooms_id_seq CASCADE');
        $this->addSql('ALTER TABLE meetings DROP CONSTRAINT FK_44FE52E254177093');
        $this->addSql('ALTER TABLE rooms ALTER COLUMN id DROP DEFAULT, 
                    ALTER COLUMN id TYPE uuid USING (uuid_generate_v4()), 
                    ALTER COLUMN id SET DEFAULT uuid_generate_v4()');
        $this->addSql('ALTER TABLE public.meetings 
                    ALTER COLUMN room_id DROP DEFAULT, 
                    ALTER COLUMN room_id TYPE uuid USING (uuid_generate_v4())');
        $this->addSql('ALTER TABLE meetings ADD CONSTRAINT FK_44FE52E254177093 FOREIGN KEY (room_id) REFERENCES rooms (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE rooms_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE meetings DROP CONSTRAINT FK_44FE52E254177093');
        $this->addSql('ALTER TABLE meetings ALTER room_id DROP DEFAULT, ALTER COLUMN room_id TYPE integer');
        $this->addSql('ALTER TABLE rooms ALTER id DROP DEFAULT, ALTER COLUMN id TYPE integer');
        $this->addSql('CREATE SEQUENCE rooms_id_seq');
        $this->addSql('SELECT setval(\'rooms_id_seq\', (SELECT MAX(id) FROM rooms))');
        $this->addSql('ALTER TABLE rooms ALTER id SET DEFAULT nextval(\'rooms_id_seq\')');
        $this->addSql('ALTER TABLE meetings ADD CONSTRAINT FK_44FE52E254177093 FOREIGN KEY (room_id) REFERENCES rooms (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
