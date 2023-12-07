<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231207170137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE block (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE block_image (block_id INT NOT NULL, image_id INT NOT NULL, INDEX IDX_22CC2B9E9ED820C (block_id), INDEX IDX_22CC2B93DA5256D (image_id), PRIMARY KEY(block_id, image_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE block_text (block_id INT NOT NULL, text_id INT NOT NULL, INDEX IDX_FDE5F5BCE9ED820C (block_id), INDEX IDX_FDE5F5BC698D3548 (text_id), PRIMARY KEY(block_id, text_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE block_post (block_id INT NOT NULL, post_id INT NOT NULL, INDEX IDX_9CE43EF6E9ED820C (block_id), INDEX IDX_9CE43EF64B89032C (post_id), PRIMARY KEY(block_id, post_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE block_image ADD CONSTRAINT FK_22CC2B9E9ED820C FOREIGN KEY (block_id) REFERENCES block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_image ADD CONSTRAINT FK_22CC2B93DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_text ADD CONSTRAINT FK_FDE5F5BCE9ED820C FOREIGN KEY (block_id) REFERENCES block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_text ADD CONSTRAINT FK_FDE5F5BC698D3548 FOREIGN KEY (text_id) REFERENCES text (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_post ADD CONSTRAINT FK_9CE43EF6E9ED820C FOREIGN KEY (block_id) REFERENCES block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_post ADD CONSTRAINT FK_9CE43EF64B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE block_image DROP FOREIGN KEY FK_22CC2B9E9ED820C');
        $this->addSql('ALTER TABLE block_image DROP FOREIGN KEY FK_22CC2B93DA5256D');
        $this->addSql('ALTER TABLE block_text DROP FOREIGN KEY FK_FDE5F5BCE9ED820C');
        $this->addSql('ALTER TABLE block_text DROP FOREIGN KEY FK_FDE5F5BC698D3548');
        $this->addSql('ALTER TABLE block_post DROP FOREIGN KEY FK_9CE43EF6E9ED820C');
        $this->addSql('ALTER TABLE block_post DROP FOREIGN KEY FK_9CE43EF64B89032C');
        $this->addSql('DROP TABLE block');
        $this->addSql('DROP TABLE block_image');
        $this->addSql('DROP TABLE block_text');
        $this->addSql('DROP TABLE block_post');
    }
}
