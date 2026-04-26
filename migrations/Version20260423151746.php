<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260423151746 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY `FK_8A8E26E9F675F31B`');
        $this->addSql('DROP INDEX IDX_8A8E26E9F675F31B ON conversation');
        $this->addSql('ALTER TABLE conversation CHANGE author_id customer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E99395C3F3 FOREIGN KEY (customer_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8A8E26E99395C3F3 ON conversation (customer_id)');
        $this->addSql('ALTER TABLE message ADD is_read TINYINT NOT NULL, ADD sender_id INT NOT NULL, DROP author');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307FF624B39D ON message (sender_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E99395C3F3');
        $this->addSql('DROP INDEX IDX_8A8E26E99395C3F3 ON conversation');
        $this->addSql('ALTER TABLE conversation CHANGE customer_id author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT `FK_8A8E26E9F675F31B` FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8A8E26E9F675F31B ON conversation (author_id)');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF624B39D');
        $this->addSql('DROP INDEX IDX_B6BD307FF624B39D ON message');
        $this->addSql('ALTER TABLE message ADD author VARCHAR(255) NOT NULL, DROP is_read, DROP sender_id');
    }
}
