<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231210142524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message ADD created_at DATETIME COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('UPDATE message set created_at = NOW(), updated_at = NOW()');
        $this->addSql('ALTER TABLE message MODIFY created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE message MODIFY updated_at DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP created_at, DROP updated_at');
    }
}
