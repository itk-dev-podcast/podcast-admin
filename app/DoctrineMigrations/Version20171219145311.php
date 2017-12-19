<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171219145311 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE item_related_event (item_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', related_event_id VARCHAR(255) NOT NULL, INDEX IDX_A7834457126F525E (item_id), INDEX IDX_A7834457D774A626 (related_event_id), PRIMARY KEY(item_id, related_event_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE related_event (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\', deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX id_unique (id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item_related_event ADD CONSTRAINT FK_A7834457126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_related_event ADD CONSTRAINT FK_A7834457D774A626 FOREIGN KEY (related_event_id) REFERENCES related_event (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE item_related_event DROP FOREIGN KEY FK_A7834457D774A626');
        $this->addSql('DROP TABLE item_related_event');
        $this->addSql('DROP TABLE related_event');
    }
}
