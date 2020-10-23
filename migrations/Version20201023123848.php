<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201023123848 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, published_at DATETIME NOT NULL, is_published TINYINT(1) NOT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_23A0E66989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_pictures (article_id INT NOT NULL, pictures_id INT NOT NULL, INDEX IDX_DA94DBAB7294869C (article_id), INDEX IDX_DA94DBABBC415685 (pictures_id), PRIMARY KEY(article_id, pictures_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pictures (id INT AUTO_INCREMENT NOT NULL, pass_of_picture VARCHAR(255) NOT NULL, name_of_picture VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_review (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, product_id INT DEFAULT NULL, content LONGTEXT NOT NULL, writed_at DATETIME NOT NULL, INDEX IDX_1B3FC062F675F31B (author_id), INDEX IDX_1B3FC0624584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stock (id INT AUTO_INCREMENT NOT NULL, quantity INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, registred_at DATETIME NOT NULL, profil_picture VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article_pictures ADD CONSTRAINT FK_DA94DBAB7294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_pictures ADD CONSTRAINT FK_DA94DBABBC415685 FOREIGN KEY (pictures_id) REFERENCES pictures (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_review ADD CONSTRAINT FK_1B3FC062F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE product_review ADD CONSTRAINT FK_1B3FC0624584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product ADD article_id INT DEFAULT NULL, ADD stock_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADDCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD7294869C ON product (article_id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADDCD6110 ON product (stock_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_pictures DROP FOREIGN KEY FK_DA94DBAB7294869C');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD7294869C');
        $this->addSql('ALTER TABLE article_pictures DROP FOREIGN KEY FK_DA94DBABBC415685');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADDCD6110');
        $this->addSql('ALTER TABLE product_review DROP FOREIGN KEY FK_1B3FC062F675F31B');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE article_pictures');
        $this->addSql('DROP TABLE pictures');
        $this->addSql('DROP TABLE product_review');
        $this->addSql('DROP TABLE stock');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX IDX_D34A04AD7294869C ON product');
        $this->addSql('DROP INDEX IDX_D34A04ADDCD6110 ON product');
        $this->addSql('ALTER TABLE product DROP article_id, DROP stock_id');
    }
}
