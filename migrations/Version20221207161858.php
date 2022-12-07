<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221207161858 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE watchlist_item DROP FOREIGN KEY FK_1DEA83F683DD0D94');
        $this->addSql('DROP INDEX UNIQ_1DEA83F683DD0D94 ON watchlist_item');
        $this->addSql('ALTER TABLE watchlist_item DROP watchlist_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE watchlist_item ADD watchlist_id INT NOT NULL');
        $this->addSql('ALTER TABLE watchlist_item ADD CONSTRAINT FK_1DEA83F683DD0D94 FOREIGN KEY (watchlist_id) REFERENCES watchlist (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1DEA83F683DD0D94 ON watchlist_item (watchlist_id)');
    }
}
