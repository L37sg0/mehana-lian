<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240120170318 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE api_user ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE api_user ADD CONSTRAINT FK_AC64A0BAA76ED395 FOREIGN KEY (user_id) REFERENCES `admins` (id)');
        $this->addSql('CREATE INDEX IDX_AC64A0BAA76ED395 ON api_user (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE api_user DROP FOREIGN KEY FK_AC64A0BAA76ED395');
        $this->addSql('DROP INDEX IDX_AC64A0BAA76ED395 ON api_user');
        $this->addSql('ALTER TABLE api_user DROP user_id');
    }
}
