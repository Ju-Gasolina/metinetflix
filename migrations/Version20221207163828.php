<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221207163828 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE watchlist_item ADD serie_id INT DEFAULT NULL, ADD season_id INT DEFAULT NULL, ADD episode_id INT DEFAULT NULL, ADD saga_id INT DEFAULT NULL, ADD movie_id INT DEFAULT NULL, ADD start_date DATE DEFAULT NULL, ADD finish_date DATE DEFAULT NULL, ADD episode_progress INT DEFAULT NULL, ADD mark INT DEFAULT NULL, ADD personnal_note VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE watchlist_item ADD CONSTRAINT FK_1DEA83F6D94388BD FOREIGN KEY (serie_id) REFERENCES serie (id)');
        $this->addSql('ALTER TABLE watchlist_item ADD CONSTRAINT FK_1DEA83F64EC001D1 FOREIGN KEY (season_id) REFERENCES season (id)');
        $this->addSql('ALTER TABLE watchlist_item ADD CONSTRAINT FK_1DEA83F6362B62A0 FOREIGN KEY (episode_id) REFERENCES episode (id)');
        $this->addSql('ALTER TABLE watchlist_item ADD CONSTRAINT FK_1DEA83F6B2CCEE2E FOREIGN KEY (saga_id) REFERENCES saga (id)');
        $this->addSql('ALTER TABLE watchlist_item ADD CONSTRAINT FK_1DEA83F68F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('CREATE INDEX IDX_1DEA83F6D94388BD ON watchlist_item (serie_id)');
        $this->addSql('CREATE INDEX IDX_1DEA83F64EC001D1 ON watchlist_item (season_id)');
        $this->addSql('CREATE INDEX IDX_1DEA83F6362B62A0 ON watchlist_item (episode_id)');
        $this->addSql('CREATE INDEX IDX_1DEA83F6B2CCEE2E ON watchlist_item (saga_id)');
        $this->addSql('CREATE INDEX IDX_1DEA83F68F93B6FC ON watchlist_item (movie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE watchlist_item DROP FOREIGN KEY FK_1DEA83F6D94388BD');
        $this->addSql('ALTER TABLE watchlist_item DROP FOREIGN KEY FK_1DEA83F64EC001D1');
        $this->addSql('ALTER TABLE watchlist_item DROP FOREIGN KEY FK_1DEA83F6362B62A0');
        $this->addSql('ALTER TABLE watchlist_item DROP FOREIGN KEY FK_1DEA83F6B2CCEE2E');
        $this->addSql('ALTER TABLE watchlist_item DROP FOREIGN KEY FK_1DEA83F68F93B6FC');
        $this->addSql('DROP INDEX IDX_1DEA83F6D94388BD ON watchlist_item');
        $this->addSql('DROP INDEX IDX_1DEA83F64EC001D1 ON watchlist_item');
        $this->addSql('DROP INDEX IDX_1DEA83F6362B62A0 ON watchlist_item');
        $this->addSql('DROP INDEX IDX_1DEA83F6B2CCEE2E ON watchlist_item');
        $this->addSql('DROP INDEX IDX_1DEA83F68F93B6FC ON watchlist_item');
        $this->addSql('ALTER TABLE watchlist_item DROP serie_id, DROP season_id, DROP episode_id, DROP saga_id, DROP movie_id, DROP start_date, DROP finish_date, DROP episode_progress, DROP mark, DROP personnal_note');
    }
}
