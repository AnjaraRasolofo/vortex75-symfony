<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260424124653 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY `FK_8A8E26E9642B8210`');
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY `FK_8A8E26E99395C3F3`');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY `FK_B6BD307F9AC0396`');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY `FK_B6BD307FF624B39D`');
        $this->addSql('DROP TABLE conversation');
        $this->addSql('DROP TABLE message');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE conversation (id INT AUTO_INCREMENT NOT NULL, status VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_uca1400_ai_ci`, created_at DATETIME NOT NULL, customer_id INT NOT NULL, updated_at DATETIME NOT NULL, admin_id INT DEFAULT NULL, INDEX IDX_8A8E26E99395C3F3 (customer_id), INDEX IDX_8A8E26E9642B8210 (admin_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_uca1400_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, content LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_uca1400_ai_ci`, created_at DATETIME NOT NULL, conversation_id INT DEFAULT NULL, is_read TINYINT NOT NULL, sender_id INT NOT NULL, INDEX IDX_B6BD307F9AC0396 (conversation_id), INDEX IDX_B6BD307FF624B39D (sender_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_uca1400_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT `FK_8A8E26E9642B8210` FOREIGN KEY (admin_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT `FK_8A8E26E99395C3F3` FOREIGN KEY (customer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT `FK_B6BD307F9AC0396` FOREIGN KEY (conversation_id) REFERENCES conversation (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT `FK_B6BD307FF624B39D` FOREIGN KEY (sender_id) REFERENCES user (id)');
    }
}
