<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201111102226 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE response_to_product_review (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, respond_to_id INT NOT NULL, content LONGTEXT NOT NULL, writed_at DATETIME NOT NULL, INDEX IDX_3EFFEEEBF675F31B (author_id), INDEX IDX_3EFFEEEBC629B66F (respond_to_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE response_to_product_review ADD CONSTRAINT FK_3EFFEEEBF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE response_to_product_review ADD CONSTRAINT FK_3EFFEEEBC629B66F FOREIGN KEY (respond_to_id) REFERENCES product_review (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE response_to_product_review');
    }
}
