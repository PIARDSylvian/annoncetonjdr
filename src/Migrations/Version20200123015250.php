<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200123015250 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE association CHANGE address_id address_id INT DEFAULT NULL, CHANGE pendding pendding TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE commentary ADD association_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentary ADD CONSTRAINT FK_1CAC12CAEFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id)');
        $this->addSql('CREATE INDEX IDX_1CAC12CAEFB9C8A5 ON commentary (association_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE association CHANGE address_id address_id INT NOT NULL, CHANGE pendding pendding TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE commentary DROP FOREIGN KEY FK_1CAC12CAEFB9C8A5');
        $this->addSql('DROP INDEX IDX_1CAC12CAEFB9C8A5 ON commentary');
        $this->addSql('ALTER TABLE commentary DROP association_id');
    }
}
