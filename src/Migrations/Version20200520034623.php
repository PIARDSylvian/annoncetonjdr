<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200520034623 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE party_user DROP FOREIGN KEY FK_9230179A213C1059');
        $this->addSql('ALTER TABLE party_user DROP FOREIGN KEY FK_9230179AA76ED395');
        $this->addSql('ALTER TABLE party_user DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE party_user ADD CONSTRAINT FK_9230179A213C1059 FOREIGN KEY (party_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE party_user ADD CONSTRAINT FK_9230179AA76ED395 FOREIGN KEY (user_id) REFERENCES party (id)');
        $this->addSql('ALTER TABLE party_user ADD PRIMARY KEY (user_id, party_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE party_user DROP FOREIGN KEY FK_9230179AA76ED395');
        $this->addSql('ALTER TABLE party_user DROP FOREIGN KEY FK_9230179A213C1059');
        $this->addSql('ALTER TABLE party_user DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE party_user ADD CONSTRAINT FK_9230179AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE party_user ADD CONSTRAINT FK_9230179A213C1059 FOREIGN KEY (party_id) REFERENCES party (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE party_user ADD PRIMARY KEY (party_id, user_id)');
    }
}
