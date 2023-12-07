<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231207171811 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE block_review (block_id INT NOT NULL, review_id INT NOT NULL, INDEX IDX_30EDD74DE9ED820C (block_id), INDEX IDX_30EDD74D3E2E969B (review_id), PRIMARY KEY(block_id, review_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE block_profile (block_id INT NOT NULL, profile_id INT NOT NULL, INDEX IDX_FB745EF1E9ED820C (block_id), INDEX IDX_FB745EF1CCFA12B8 (profile_id), PRIMARY KEY(block_id, profile_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profile (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, position VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE block_review ADD CONSTRAINT FK_30EDD74DE9ED820C FOREIGN KEY (block_id) REFERENCES block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_review ADD CONSTRAINT FK_30EDD74D3E2E969B FOREIGN KEY (review_id) REFERENCES review (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_profile ADD CONSTRAINT FK_FB745EF1E9ED820C FOREIGN KEY (block_id) REFERENCES block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_profile ADD CONSTRAINT FK_FB745EF1CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE block_review DROP FOREIGN KEY FK_30EDD74DE9ED820C');
        $this->addSql('ALTER TABLE block_review DROP FOREIGN KEY FK_30EDD74D3E2E969B');
        $this->addSql('ALTER TABLE block_profile DROP FOREIGN KEY FK_FB745EF1E9ED820C');
        $this->addSql('ALTER TABLE block_profile DROP FOREIGN KEY FK_FB745EF1CCFA12B8');
        $this->addSql('DROP TABLE block_review');
        $this->addSql('DROP TABLE block_profile');
        $this->addSql('DROP TABLE profile');
    }
}
