<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171207211306 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE item_related_material (item_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', related_material_id VARCHAR(255) NOT NULL, INDEX IDX_E25F3827126F525E (item_id), INDEX IDX_E25F382728E3D2EB (related_material_id), PRIMARY KEY(item_id, related_material_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE related_material (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\', deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX id_unique (id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item_related_material ADD CONSTRAINT FK_E25F3827126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_related_material ADD CONSTRAINT FK_E25F382728E3D2EB FOREIGN KEY (related_material_id) REFERENCES related_material (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category ADD data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\'');
        $this->addSql('ALTER TABLE audience ADD data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\'');
        $this->addSql('ALTER TABLE context ADD data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\'');
        $this->addSql('ALTER TABLE recommender ADD data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\'');
        $this->addSql('ALTER TABLE subject ADD data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\'');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE item_related_material DROP FOREIGN KEY FK_E25F382728E3D2EB');
        $this->addSql('DROP TABLE item_related_material');
        $this->addSql('DROP TABLE related_material');
        $this->addSql('ALTER TABLE audience DROP data');
        $this->addSql('ALTER TABLE category DROP data');
        $this->addSql('ALTER TABLE context DROP data');
        $this->addSql('ALTER TABLE recommender DROP data');
        $this->addSql('ALTER TABLE subject DROP data');
    }
}
