<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231214083029 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE menu_menu_item (menu_id INT NOT NULL, menu_item_id INT NOT NULL, INDEX IDX_CE14B264CCD7E912 (menu_id), INDEX IDX_CE14B2649AB44FE0 (menu_item_id), PRIMARY KEY(menu_id, menu_item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE menu_menu_item ADD CONSTRAINT FK_CE14B264CCD7E912 FOREIGN KEY (menu_id) REFERENCES menus (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_menu_item ADD CONSTRAINT FK_CE14B2649AB44FE0 FOREIGN KEY (menu_item_id) REFERENCES menu_items (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_item_menu DROP FOREIGN KEY FK_AC75195C9AB44FE0');
        $this->addSql('ALTER TABLE menu_item_menu DROP FOREIGN KEY FK_AC75195CCCD7E912');
        $this->addSql('DROP TABLE menu_item_menu');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE menu_item_menu (menu_item_id INT NOT NULL, menu_id INT NOT NULL, INDEX IDX_AC75195C9AB44FE0 (menu_item_id), INDEX IDX_AC75195CCCD7E912 (menu_id), PRIMARY KEY(menu_item_id, menu_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE menu_item_menu ADD CONSTRAINT FK_AC75195C9AB44FE0 FOREIGN KEY (menu_item_id) REFERENCES menu_items (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_item_menu ADD CONSTRAINT FK_AC75195CCCD7E912 FOREIGN KEY (menu_id) REFERENCES menus (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_menu_item DROP FOREIGN KEY FK_CE14B264CCD7E912');
        $this->addSql('ALTER TABLE menu_menu_item DROP FOREIGN KEY FK_CE14B2649AB44FE0');
        $this->addSql('DROP TABLE menu_menu_item');
    }
}
