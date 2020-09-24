<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200924195908 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE association_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE commentary_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE event_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE game_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE location_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE note_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE party_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE report_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_app_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE user_app (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, pseudonym VARCHAR(25) NOT NULL, secret_q VARCHAR(10) NOT NULL, secret_r VARCHAR(255) NOT NULL, reset_token VARCHAR(255) DEFAULT NULL, confirm_token VARCHAR(255) DEFAULT NULL, suspend BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_22781144E7927C74 ON user_app (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_227811443654B190 ON user_app (pseudonym)');
        $this->addSql('CREATE TABLE note (id INT NOT NULL, ambiance INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE note_user (note_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(note_id, user_id))');
        $this->addSql('CREATE INDEX IDX_2DE9C71126ED0855 ON note_user (note_id)');
        $this->addSql('CREATE INDEX IDX_2DE9C711A76ED395 ON note_user (user_id)');
        $this->addSql('CREATE TABLE location (id INT NOT NULL, address VARCHAR(255) NOT NULL, lat DOUBLE PRECISION NOT NULL, lng DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE game (id INT NOT NULL, name VARCHAR(255) NOT NULL, image_url VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_232B318C5E237E06 ON game (name)');
        $this->addSql('CREATE TABLE party (id INT NOT NULL, owner_id INT NOT NULL, game_name_id INT NOT NULL, address_id INT DEFAULT NULL, note_id INT DEFAULT NULL, party_name VARCHAR(50) NOT NULL, already_subscribed INT NOT NULL, max_player INT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, minor BOOLEAN NOT NULL, game_edition BOOLEAN NOT NULL, name_scenario VARCHAR(50) DEFAULT NULL, scenario_edition BOOLEAN NOT NULL, opened_campaign BOOLEAN NOT NULL, game_description TEXT DEFAULT NULL, online BOOLEAN NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_89954EE0989D9B62 ON party (slug)');
        $this->addSql('CREATE INDEX IDX_89954EE07E3C61F9 ON party (owner_id)');
        $this->addSql('CREATE INDEX IDX_89954EE0E4D5E3F9 ON party (game_name_id)');
        $this->addSql('CREATE INDEX IDX_89954EE0F5B7AF75 ON party (address_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_89954EE026ED0855 ON party (note_id)');
        $this->addSql('CREATE TABLE party_user (party_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(party_id, user_id))');
        $this->addSql('CREATE INDEX IDX_9230179A213C1059 ON party_user (party_id)');
        $this->addSql('CREATE INDEX IDX_9230179AA76ED395 ON party_user (user_id)');
        $this->addSql('CREATE TABLE event (id INT NOT NULL, owner_id INT NOT NULL, address_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, date_start TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, date_finish TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, description TEXT DEFAULT NULL, image_url VARCHAR(255) DEFAULT NULL, pendding BOOLEAN NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3BAE0AA7989D9B62 ON event (slug)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA77E3C61F9 ON event (owner_id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7F5B7AF75 ON event (address_id)');
        $this->addSql('CREATE TABLE association (id INT NOT NULL, owner_id INT NOT NULL, address_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, image_url VARCHAR(255) DEFAULT NULL, pendding BOOLEAN NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FD8521CC989D9B62 ON association (slug)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FD8521CC7E3C61F9 ON association (owner_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FD8521CCF5B7AF75 ON association (address_id)');
        $this->addSql('CREATE TABLE commentary (id INT NOT NULL, owner_id INT NOT NULL, party_id INT DEFAULT NULL, event_id INT DEFAULT NULL, association_id INT DEFAULT NULL, commentary TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1CAC12CA7E3C61F9 ON commentary (owner_id)');
        $this->addSql('CREATE INDEX IDX_1CAC12CA213C1059 ON commentary (party_id)');
        $this->addSql('CREATE INDEX IDX_1CAC12CA71F7E88B ON commentary (event_id)');
        $this->addSql('CREATE INDEX IDX_1CAC12CAEFB9C8A5 ON commentary (association_id)');
        $this->addSql('CREATE TABLE report (id INT NOT NULL, owner_id INT NOT NULL, commentary_id INT DEFAULT NULL, association_id INT DEFAULT NULL, event_id INT DEFAULT NULL, party_id INT DEFAULT NULL, user_id INT DEFAULT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, reason TEXT NOT NULL, solved BOOLEAN NOT NULL, action TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C42F77847E3C61F9 ON report (owner_id)');
        $this->addSql('CREATE INDEX IDX_C42F77845DED49AA ON report (commentary_id)');
        $this->addSql('CREATE INDEX IDX_C42F7784EFB9C8A5 ON report (association_id)');
        $this->addSql('CREATE INDEX IDX_C42F778471F7E88B ON report (event_id)');
        $this->addSql('CREATE INDEX IDX_C42F7784213C1059 ON report (party_id)');
        $this->addSql('CREATE INDEX IDX_C42F7784A76ED395 ON report (user_id)');
        $this->addSql('ALTER TABLE note_user ADD CONSTRAINT FK_2DE9C71126ED0855 FOREIGN KEY (note_id) REFERENCES note (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE note_user ADD CONSTRAINT FK_2DE9C711A76ED395 FOREIGN KEY (user_id) REFERENCES user_app (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE party ADD CONSTRAINT FK_89954EE07E3C61F9 FOREIGN KEY (owner_id) REFERENCES user_app (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE party ADD CONSTRAINT FK_89954EE0E4D5E3F9 FOREIGN KEY (game_name_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE party ADD CONSTRAINT FK_89954EE0F5B7AF75 FOREIGN KEY (address_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE party ADD CONSTRAINT FK_89954EE026ED0855 FOREIGN KEY (note_id) REFERENCES note (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE party_user ADD CONSTRAINT FK_9230179A213C1059 FOREIGN KEY (party_id) REFERENCES party (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE party_user ADD CONSTRAINT FK_9230179AA76ED395 FOREIGN KEY (user_id) REFERENCES user_app (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA77E3C61F9 FOREIGN KEY (owner_id) REFERENCES user_app (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7F5B7AF75 FOREIGN KEY (address_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE association ADD CONSTRAINT FK_FD8521CC7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user_app (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE association ADD CONSTRAINT FK_FD8521CCF5B7AF75 FOREIGN KEY (address_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE commentary ADD CONSTRAINT FK_1CAC12CA7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user_app (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE commentary ADD CONSTRAINT FK_1CAC12CA213C1059 FOREIGN KEY (party_id) REFERENCES party (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE commentary ADD CONSTRAINT FK_1CAC12CA71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE commentary ADD CONSTRAINT FK_1CAC12CAEFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F77847E3C61F9 FOREIGN KEY (owner_id) REFERENCES user_app (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F77845DED49AA FOREIGN KEY (commentary_id) REFERENCES commentary (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784EFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F778471F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784213C1059 FOREIGN KEY (party_id) REFERENCES party (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784A76ED395 FOREIGN KEY (user_id) REFERENCES user_app (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE commentary DROP CONSTRAINT FK_1CAC12CAEFB9C8A5');
        $this->addSql('ALTER TABLE report DROP CONSTRAINT FK_C42F7784EFB9C8A5');
        $this->addSql('ALTER TABLE report DROP CONSTRAINT FK_C42F77845DED49AA');
        $this->addSql('ALTER TABLE commentary DROP CONSTRAINT FK_1CAC12CA71F7E88B');
        $this->addSql('ALTER TABLE report DROP CONSTRAINT FK_C42F778471F7E88B');
        $this->addSql('ALTER TABLE party DROP CONSTRAINT FK_89954EE0E4D5E3F9');
        $this->addSql('ALTER TABLE association DROP CONSTRAINT FK_FD8521CCF5B7AF75');
        $this->addSql('ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA7F5B7AF75');
        $this->addSql('ALTER TABLE party DROP CONSTRAINT FK_89954EE0F5B7AF75');
        $this->addSql('ALTER TABLE note_user DROP CONSTRAINT FK_2DE9C71126ED0855');
        $this->addSql('ALTER TABLE party DROP CONSTRAINT FK_89954EE026ED0855');
        $this->addSql('ALTER TABLE commentary DROP CONSTRAINT FK_1CAC12CA213C1059');
        $this->addSql('ALTER TABLE party_user DROP CONSTRAINT FK_9230179A213C1059');
        $this->addSql('ALTER TABLE report DROP CONSTRAINT FK_C42F7784213C1059');
        $this->addSql('ALTER TABLE association DROP CONSTRAINT FK_FD8521CC7E3C61F9');
        $this->addSql('ALTER TABLE commentary DROP CONSTRAINT FK_1CAC12CA7E3C61F9');
        $this->addSql('ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA77E3C61F9');
        $this->addSql('ALTER TABLE note_user DROP CONSTRAINT FK_2DE9C711A76ED395');
        $this->addSql('ALTER TABLE party DROP CONSTRAINT FK_89954EE07E3C61F9');
        $this->addSql('ALTER TABLE party_user DROP CONSTRAINT FK_9230179AA76ED395');
        $this->addSql('ALTER TABLE report DROP CONSTRAINT FK_C42F77847E3C61F9');
        $this->addSql('ALTER TABLE report DROP CONSTRAINT FK_C42F7784A76ED395');
        $this->addSql('DROP SEQUENCE association_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE commentary_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE event_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE game_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE location_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE note_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE party_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE report_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_app_id_seq CASCADE');
        $this->addSql('DROP TABLE association');
        $this->addSql('DROP TABLE commentary');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE note_user');
        $this->addSql('DROP TABLE party');
        $this->addSql('DROP TABLE party_user');
        $this->addSql('DROP TABLE report');
        $this->addSql('DROP TABLE user_app');
    }
}
