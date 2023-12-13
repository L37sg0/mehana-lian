<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231212170852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE menu_items (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, ingredients LONGTEXT NOT NULL, price DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_item_menu (menu_item_id INT NOT NULL, menu_id INT NOT NULL, INDEX IDX_AC75195C9AB44FE0 (menu_item_id), INDEX IDX_AC75195CCCD7E912 (menu_id), PRIMARY KEY(menu_item_id, menu_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menus (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE menu_item_menu ADD CONSTRAINT FK_AC75195C9AB44FE0 FOREIGN KEY (menu_item_id) REFERENCES menu_items (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_item_menu ADD CONSTRAINT FK_AC75195CCCD7E912 FOREIGN KEY (menu_id) REFERENCES menus (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE menu_item_menu DROP FOREIGN KEY FK_AC75195C9AB44FE0');
        $this->addSql('ALTER TABLE menu_item_menu DROP FOREIGN KEY FK_AC75195CCCD7E912');
        $this->addSql('DROP TABLE menu_items');
        $this->addSql('DROP TABLE menu_item_menu');
        $this->addSql('DROP TABLE menus');
    }
}
