<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221024135158 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_badge ADD id_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_badge ADD CONSTRAINT FK_1C32B34579F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_1C32B34579F37AE5 ON user_badge (id_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_badge DROP FOREIGN KEY FK_1C32B34579F37AE5');
        $this->addSql('DROP INDEX IDX_1C32B34579F37AE5 ON user_badge');
        $this->addSql('ALTER TABLE user_badge DROP id_user_id');
    }
}
