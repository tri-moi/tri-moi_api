<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221024135902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE history ADD id_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE history ADD CONSTRAINT FK_27BA704B1BD125E3 FOREIGN KEY (id_type_id) REFERENCES type (id)');
        $this->addSql('CREATE INDEX IDX_27BA704B1BD125E3 ON history (id_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE history DROP FOREIGN KEY FK_27BA704B1BD125E3');
        $this->addSql('DROP INDEX IDX_27BA704B1BD125E3 ON history');
        $this->addSql('ALTER TABLE history DROP id_type_id');
    }
}
