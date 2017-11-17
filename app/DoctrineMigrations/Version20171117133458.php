<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171117133458 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE channel ADD feed_id INT DEFAULT NULL, ADD language VARCHAR(255) DEFAULT NULL, ADD copyright VARCHAR(255) DEFAULT NULL, ADD managing_editor VARCHAR(255) DEFAULT NULL, ADD web_master VARCHAR(255) DEFAULT NULL, ADD last_build_date DATETIME DEFAULT NULL, ADD generator VARCHAR(255) DEFAULT NULL, ADD docs VARCHAR(255) DEFAULT NULL, ADD cloud VARCHAR(255) DEFAULT NULL, ADD ttl INT DEFAULT NULL, ADD image VARCHAR(255) DEFAULT NULL, ADD rating VARCHAR(255) DEFAULT NULL, ADD text_input LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\', ADD skip_hours LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\', ADD skip_days LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\', DROP author, DROP comments, DROP guid, DROP guid_is_perma_link, DROP enclosure_length, DROP enclosure_type, DROP enclosure_url, DROP source, DROP source_url, DROP duration, CHANGE link link VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE channel ADD CONSTRAINT FK_A2F98E4751A5BC03 FOREIGN KEY (feed_id) REFERENCES feed (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A2F98E4751A5BC03 ON channel (feed_id)');
        $this->addSql('ALTER TABLE item ADD enclosure LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\', ADD subtitle VARCHAR(255) DEFAULT NULL, ADD summary LONGTEXT DEFAULT NULL, ADD episode_type VARCHAR(255) DEFAULT NULL, ADD explicit TINYINT(1) DEFAULT NULL, ADD image VARCHAR(255) DEFAULT NULL, ADD `order` INT DEFAULT NULL, ADD season INT DEFAULT NULL, DROP enclosure_type, DROP enclosure_url, DROP source, CHANGE link link VARCHAR(255) NOT NULL, CHANGE enclosure_length episode INT DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE channel DROP FOREIGN KEY FK_A2F98E4751A5BC03');
        $this->addSql('DROP INDEX UNIQ_A2F98E4751A5BC03 ON channel');
        $this->addSql('ALTER TABLE channel ADD author VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD comments LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, ADD guid VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD guid_is_perma_link TINYINT(1) NOT NULL, ADD enclosure_length INT DEFAULT NULL, ADD enclosure_type VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD enclosure_url VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD source VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD source_url VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD duration INT DEFAULT NULL, DROP feed_id, DROP language, DROP copyright, DROP managing_editor, DROP web_master, DROP last_build_date, DROP generator, DROP docs, DROP cloud, DROP ttl, DROP image, DROP rating, DROP text_input, DROP skip_hours, DROP skip_days, CHANGE link link VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE item ADD enclosure_length INT DEFAULT NULL, ADD enclosure_type VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD enclosure_url VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD source VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, DROP enclosure, DROP subtitle, DROP summary, DROP episode, DROP episode_type, DROP explicit, DROP image, DROP `order`, DROP season, CHANGE link link VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
