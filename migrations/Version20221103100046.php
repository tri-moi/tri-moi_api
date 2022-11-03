<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221103100046 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE history ADD id_poubelle_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE history ADD CONSTRAINT FK_27BA704BEA237D75 FOREIGN KEY (id_poubelle_id) REFERENCES trash (id)');
        $this->addSql('CREATE INDEX IDX_27BA704BEA237D75 ON history (id_poubelle_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE history DROP FOREIGN KEY FK_27BA704BEA237D75');
        $this->addSql('DROP INDEX IDX_27BA704BEA237D75 ON history');
        $this->addSql('ALTER TABLE history DROP id_poubelle_id');
    }
}
