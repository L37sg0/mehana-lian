<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231207174655 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE page (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE page_block (page_id INT NOT NULL, block_id INT NOT NULL, INDEX IDX_E59A68F4C4663E4 (page_id), INDEX IDX_E59A68F4E9ED820C (block_id), PRIMARY KEY(page_id, block_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE page_block ADD CONSTRAINT FK_E59A68F4C4663E4 FOREIGN KEY (page_id) REFERENCES page (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE page_block ADD CONSTRAINT FK_E59A68F4E9ED820C FOREIGN KEY (block_id) REFERENCES block (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE page_block DROP FOREIGN KEY FK_E59A68F4C4663E4');
        $this->addSql('ALTER TABLE page_block DROP FOREIGN KEY FK_E59A68F4E9ED820C');
        $this->addSql('DROP TABLE page');
        $this->addSql('DROP TABLE page_block');
    }
}
