<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171115103028 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE channel (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', title VARCHAR(255) NOT NULL, link VARCHAR(255) DEFAULT NULL, description LONGTEXT NOT NULL, author VARCHAR(255) DEFAULT NULL, comments LONGTEXT DEFAULT NULL, guid VARCHAR(255) DEFAULT NULL, guid_is_perma_link TINYINT(1) NOT NULL, pub_date DATETIME DEFAULT NULL, enclosure_length INT DEFAULT NULL, enclosure_type VARCHAR(255) DEFAULT NULL, enclosure_url VARCHAR(255) DEFAULT NULL, source VARCHAR(255) DEFAULT NULL, source_url VARCHAR(255) DEFAULT NULL, duration INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE channel');
    }
}
