<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171123093721 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE channel ADD subtitle VARCHAR(255) DEFAULT NULL, ADD summary LONGTEXT DEFAULT NULL, ADD author VARCHAR(255) DEFAULT NULL, ADD block TINYINT(1) DEFAULT NULL, ADD complete TINYINT(1) DEFAULT NULL, ADD explicit TINYINT(1) DEFAULT NULL, ADD itunes_image LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\', CHANGE image image LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\'');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE channel DROP subtitle, DROP summary, DROP author, DROP block, DROP complete, DROP explicit, DROP itunes_image, CHANGE image image VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
