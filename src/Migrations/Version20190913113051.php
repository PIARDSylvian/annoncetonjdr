<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190913113051 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE commentary_party');
        $this->addSql('ALTER TABLE commentary ADD party_id INT NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE commentary ADD CONSTRAINT FK_1CAC12CA213C1059 FOREIGN KEY (party_id) REFERENCES party (id)');
        $this->addSql('CREATE INDEX IDX_1CAC12CA213C1059 ON commentary (party_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE commentary_party (commentary_id INT NOT NULL, party_id INT NOT NULL, INDEX IDX_BB7FA7E55DED49AA (commentary_id), INDEX IDX_BB7FA7E5213C1059 (party_id), PRIMARY KEY(commentary_id, party_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commentary_party ADD CONSTRAINT FK_BB7FA7E5213C1059 FOREIGN KEY (party_id) REFERENCES party (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentary_party ADD CONSTRAINT FK_BB7FA7E55DED49AA FOREIGN KEY (commentary_id) REFERENCES commentary (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentary DROP FOREIGN KEY FK_1CAC12CA213C1059');
        $this->addSql('DROP INDEX IDX_1CAC12CA213C1059 ON commentary');
        $this->addSql('ALTER TABLE commentary DROP party_id, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
    }
}
