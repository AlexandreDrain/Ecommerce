<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201113154017 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_23A0E66F675F31B ON article (author_id)');
        $this->addSql('ALTER TABLE product DROP INDEX IDX_D34A04ADDCD6110, ADD UNIQUE INDEX UNIQ_D34A04ADDCD6110 (stock_id)');
        $this->addSql('ALTER TABLE product CHANGE stock_id stock_id INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66F675F31B');
        $this->addSql('DROP INDEX IDX_23A0E66F675F31B ON article');
        $this->addSql('ALTER TABLE product DROP INDEX UNIQ_D34A04ADDCD6110, ADD INDEX IDX_D34A04ADDCD6110 (stock_id)');
        $this->addSql('ALTER TABLE product CHANGE stock_id stock_id INT DEFAULT NULL');
    }
}
