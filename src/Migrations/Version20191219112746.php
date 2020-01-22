<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191219112746 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE commentary (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, party_id INT NOT NULL, commentary LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_1CAC12CA7E3C61F9 (owner_id), INDEX IDX_1CAC12CA213C1059 (party_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, address_id INT NOT NULL, name VARCHAR(255) NOT NULL, date_start DATETIME NOT NULL, date_finish DATETIME NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_3BAE0AA77E3C61F9 (owner_id), INDEX IDX_3BAE0AA7F5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, address VARCHAR(255) NOT NULL, lat DOUBLE PRECISION NOT NULL, lng DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE party (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, game_name_id INT DEFAULT NULL, address_id INT DEFAULT NULL, party_name VARCHAR(50) NOT NULL, already_subscribed INT NOT NULL, max_player INT NOT NULL, date DATETIME NOT NULL, minor TINYINT(1) DEFAULT \'1\' NOT NULL, game_edition TINYINT(1) NOT NULL, name_scenario VARCHAR(50) DEFAULT NULL, scenario_edition TINYINT(1) NOT NULL, opened_campaign TINYINT(1) NOT NULL, game_description LONGTEXT DEFAULT NULL, online TINYINT(1) NOT NULL, INDEX IDX_89954EE07E3C61F9 (owner_id), INDEX IDX_89954EE0E4D5E3F9 (game_name_id), INDEX IDX_89954EE0F5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE party_user (party_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_9230179A213C1059 (party_id), INDEX IDX_9230179AA76ED395 (user_id), PRIMARY KEY(party_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentary ADD CONSTRAINT FK_1CAC12CA7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentary ADD CONSTRAINT FK_1CAC12CA213C1059 FOREIGN KEY (party_id) REFERENCES party (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA77E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7F5B7AF75 FOREIGN KEY (address_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE party ADD CONSTRAINT FK_89954EE07E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE party ADD CONSTRAINT FK_89954EE0E4D5E3F9 FOREIGN KEY (game_name_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE party ADD CONSTRAINT FK_89954EE0F5B7AF75 FOREIGN KEY (address_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE party_user ADD CONSTRAINT FK_9230179A213C1059 FOREIGN KEY (party_id) REFERENCES party (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE party_user ADD CONSTRAINT FK_9230179AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7F5B7AF75');
        $this->addSql('ALTER TABLE party DROP FOREIGN KEY FK_89954EE0F5B7AF75');
        $this->addSql('ALTER TABLE commentary DROP FOREIGN KEY FK_1CAC12CA213C1059');
        $this->addSql('ALTER TABLE party_user DROP FOREIGN KEY FK_9230179A213C1059');
        $this->addSql('DROP TABLE commentary');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE party');
        $this->addSql('DROP TABLE party_user');
    }
}
