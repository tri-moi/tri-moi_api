<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221024135742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trash ADD id_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trash ADD CONSTRAINT FK_528BB4D1BD125E3 FOREIGN KEY (id_type_id) REFERENCES type (id)');
        $this->addSql('CREATE INDEX IDX_528BB4D1BD125E3 ON trash (id_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trash DROP FOREIGN KEY FK_528BB4D1BD125E3');
        $this->addSql('DROP INDEX IDX_528BB4D1BD125E3 ON trash');
        $this->addSql('ALTER TABLE trash DROP id_type_id');
    }
}
