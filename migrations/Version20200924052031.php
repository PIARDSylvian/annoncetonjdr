<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200924052031 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, pseudonym VARCHAR(25) NOT NULL, secret_q VARCHAR(10) NOT NULL, secret_r VARCHAR(255) NOT NULL, reset_token VARCHAR(255) DEFAULT NULL, confirm_token VARCHAR(255) DEFAULT NULL, suspend TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D6493654B190 (pseudonym), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note (id INT AUTO_INCREMENT NOT NULL, ambiance INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note_user (note_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_2DE9C71126ED0855 (note_id), INDEX IDX_2DE9C711A76ED395 (user_id), PRIMARY KEY(note_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, address VARCHAR(255) NOT NULL, lat DOUBLE PRECISION NOT NULL, lng DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image_url VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_232B318C5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE party (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, game_name_id INT NOT NULL, address_id INT DEFAULT NULL, note_id INT DEFAULT NULL, party_name VARCHAR(50) NOT NULL, already_subscribed INT NOT NULL, max_player INT NOT NULL, date DATETIME NOT NULL, minor TINYINT(1) NOT NULL, game_edition TINYINT(1) NOT NULL, name_scenario VARCHAR(50) DEFAULT NULL, scenario_edition TINYINT(1) NOT NULL, opened_campaign TINYINT(1) NOT NULL, game_description LONGTEXT DEFAULT NULL, online TINYINT(1) NOT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_89954EE0989D9B62 (slug), INDEX IDX_89954EE07E3C61F9 (owner_id), INDEX IDX_89954EE0E4D5E3F9 (game_name_id), INDEX IDX_89954EE0F5B7AF75 (address_id), UNIQUE INDEX UNIQ_89954EE026ED0855 (note_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE party_user (party_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_9230179A213C1059 (party_id), INDEX IDX_9230179AA76ED395 (user_id), PRIMARY KEY(party_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, address_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, date_start DATETIME NOT NULL, date_finish DATETIME NOT NULL, description LONGTEXT DEFAULT NULL, image_url VARCHAR(255) DEFAULT NULL, pendding TINYINT(1) NOT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_3BAE0AA7989D9B62 (slug), INDEX IDX_3BAE0AA77E3C61F9 (owner_id), INDEX IDX_3BAE0AA7F5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE association (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, address_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, image_url VARCHAR(255) DEFAULT NULL, pendding TINYINT(1) NOT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_FD8521CC989D9B62 (slug), UNIQUE INDEX UNIQ_FD8521CC7E3C61F9 (owner_id), UNIQUE INDEX UNIQ_FD8521CCF5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentary (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, party_id INT DEFAULT NULL, event_id INT DEFAULT NULL, association_id INT DEFAULT NULL, commentary LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_1CAC12CA7E3C61F9 (owner_id), INDEX IDX_1CAC12CA213C1059 (party_id), INDEX IDX_1CAC12CA71F7E88B (event_id), INDEX IDX_1CAC12CAEFB9C8A5 (association_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, commentary_id INT DEFAULT NULL, association_id INT DEFAULT NULL, event_id INT DEFAULT NULL, party_id INT DEFAULT NULL, user_id INT DEFAULT NULL, date DATETIME NOT NULL, reason LONGTEXT NOT NULL, solved TINYINT(1) NOT NULL, action LONGTEXT DEFAULT NULL, INDEX IDX_C42F77847E3C61F9 (owner_id), INDEX IDX_C42F77845DED49AA (commentary_id), INDEX IDX_C42F7784EFB9C8A5 (association_id), INDEX IDX_C42F778471F7E88B (event_id), INDEX IDX_C42F7784213C1059 (party_id), INDEX IDX_C42F7784A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE note_user ADD CONSTRAINT FK_2DE9C71126ED0855 FOREIGN KEY (note_id) REFERENCES note (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE note_user ADD CONSTRAINT FK_2DE9C711A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE party ADD CONSTRAINT FK_89954EE07E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE party ADD CONSTRAINT FK_89954EE0E4D5E3F9 FOREIGN KEY (game_name_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE party ADD CONSTRAINT FK_89954EE0F5B7AF75 FOREIGN KEY (address_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE party ADD CONSTRAINT FK_89954EE026ED0855 FOREIGN KEY (note_id) REFERENCES note (id)');
        $this->addSql('ALTER TABLE party_user ADD CONSTRAINT FK_9230179A213C1059 FOREIGN KEY (party_id) REFERENCES party (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE party_user ADD CONSTRAINT FK_9230179AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA77E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7F5B7AF75 FOREIGN KEY (address_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE association ADD CONSTRAINT FK_FD8521CC7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE association ADD CONSTRAINT FK_FD8521CCF5B7AF75 FOREIGN KEY (address_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE commentary ADD CONSTRAINT FK_1CAC12CA7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentary ADD CONSTRAINT FK_1CAC12CA213C1059 FOREIGN KEY (party_id) REFERENCES party (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentary ADD CONSTRAINT FK_1CAC12CA71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentary ADD CONSTRAINT FK_1CAC12CAEFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F77847E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F77845DED49AA FOREIGN KEY (commentary_id) REFERENCES commentary (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784EFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F778471F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784213C1059 FOREIGN KEY (party_id) REFERENCES party (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentary DROP FOREIGN KEY FK_1CAC12CAEFB9C8A5');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784EFB9C8A5');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F77845DED49AA');
        $this->addSql('ALTER TABLE commentary DROP FOREIGN KEY FK_1CAC12CA71F7E88B');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F778471F7E88B');
        $this->addSql('ALTER TABLE party DROP FOREIGN KEY FK_89954EE0E4D5E3F9');
        $this->addSql('ALTER TABLE association DROP FOREIGN KEY FK_FD8521CCF5B7AF75');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7F5B7AF75');
        $this->addSql('ALTER TABLE party DROP FOREIGN KEY FK_89954EE0F5B7AF75');
        $this->addSql('ALTER TABLE note_user DROP FOREIGN KEY FK_2DE9C71126ED0855');
        $this->addSql('ALTER TABLE party DROP FOREIGN KEY FK_89954EE026ED0855');
        $this->addSql('ALTER TABLE commentary DROP FOREIGN KEY FK_1CAC12CA213C1059');
        $this->addSql('ALTER TABLE party_user DROP FOREIGN KEY FK_9230179A213C1059');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784213C1059');
        $this->addSql('ALTER TABLE association DROP FOREIGN KEY FK_FD8521CC7E3C61F9');
        $this->addSql('ALTER TABLE commentary DROP FOREIGN KEY FK_1CAC12CA7E3C61F9');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA77E3C61F9');
        $this->addSql('ALTER TABLE note_user DROP FOREIGN KEY FK_2DE9C711A76ED395');
        $this->addSql('ALTER TABLE party DROP FOREIGN KEY FK_89954EE07E3C61F9');
        $this->addSql('ALTER TABLE party_user DROP FOREIGN KEY FK_9230179AA76ED395');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F77847E3C61F9');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784A76ED395');
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
        $this->addSql('DROP TABLE user');
    }
}
