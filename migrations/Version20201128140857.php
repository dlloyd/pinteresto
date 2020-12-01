<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201128140857 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images ADD category_id INT NOT NULL, ADD author_id INT NOT NULL');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A12469DE2 FOREIGN KEY (category_id) REFERENCES image_categories (id)');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6AF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_E01FBE6A12469DE2 ON images (category_id)');
        $this->addSql('CREATE INDEX IDX_E01FBE6AF675F31B ON images (author_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A12469DE2');
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6AF675F31B');
        $this->addSql('DROP INDEX IDX_E01FBE6A12469DE2 ON images');
        $this->addSql('DROP INDEX IDX_E01FBE6AF675F31B ON images');
        $this->addSql('ALTER TABLE images DROP category_id, DROP author_id');
    }
}
