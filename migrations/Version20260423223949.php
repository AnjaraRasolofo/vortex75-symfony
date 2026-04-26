<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260423223949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conversation ADD admin_id INT DEFAULT NULL, DROP admin, CHANGE status status VARCHAR(20) NOT NULL, CHANGE customer_id customer_id INT NOT NULL');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9642B8210 FOREIGN KEY (admin_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8A8E26E9642B8210 ON conversation (admin_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E9642B8210');
        $this->addSql('DROP INDEX IDX_8A8E26E9642B8210 ON conversation');
        $this->addSql('ALTER TABLE conversation ADD admin VARCHAR(255) DEFAULT NULL, DROP admin_id, CHANGE status status VARCHAR(255) DEFAULT NULL, CHANGE customer_id customer_id INT DEFAULT NULL');
    }
}
