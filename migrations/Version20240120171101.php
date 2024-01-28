<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240120171101 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE api_integration (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, client_id VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_979EE71519EB6921 (client_id), INDEX IDX_979EE715A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE api_integration ADD CONSTRAINT FK_979EE715A76ED395 FOREIGN KEY (user_id) REFERENCES `admins` (id)');
        $this->addSql('ALTER TABLE api_user DROP FOREIGN KEY FK_AC64A0BAA76ED395');
        $this->addSql('DROP TABLE api_user');
        $this->addSql('ALTER TABLE admins DROP api_client_id, DROP api_client_secret');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE api_user (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, client_id VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, roles JSON NOT NULL, client_secret VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_AC64A0BA19EB6921 (client_id), INDEX IDX_AC64A0BAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE api_user ADD CONSTRAINT FK_AC64A0BAA76ED395 FOREIGN KEY (user_id) REFERENCES admins (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE api_integration DROP FOREIGN KEY FK_979EE715A76ED395');
        $this->addSql('DROP TABLE api_integration');
        $this->addSql('ALTER TABLE `admins` ADD api_client_id VARCHAR(255) DEFAULT NULL, ADD api_client_secret VARCHAR(255) DEFAULT NULL');
    }
}
