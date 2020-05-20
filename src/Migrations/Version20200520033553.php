<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200520033553 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE party_user (party_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_9230179A213C1059 (party_id), INDEX IDX_9230179AA76ED395 (user_id), PRIMARY KEY(party_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE party_user ADD CONSTRAINT FK_9230179A213C1059 FOREIGN KEY (party_id) REFERENCES party (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE party_user ADD CONSTRAINT FK_9230179AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE user_party');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_party (user_id INT NOT NULL, party_id INT NOT NULL, INDEX IDX_6B57B5B8A76ED395 (user_id), INDEX IDX_6B57B5B8213C1059 (party_id), PRIMARY KEY(user_id, party_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_party ADD CONSTRAINT FK_6B57B5B8213C1059 FOREIGN KEY (party_id) REFERENCES party (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_party ADD CONSTRAINT FK_6B57B5B8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE party_user');
    }
}
