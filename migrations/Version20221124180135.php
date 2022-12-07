<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221124180135 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE actor (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE episode (id INT AUTO_INCREMENT NOT NULL, serie_id INT NOT NULL, season_id INT NOT NULL, duration INT NOT NULL, INDEX IDX_DDAA1CDAD94388BD (serie_id), INDEX IDX_DDAA1CDA4EC001D1 (season_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE episode_editor (episode_id INT NOT NULL, editor_id INT NOT NULL, INDEX IDX_A0CC6EA1362B62A0 (episode_id), INDEX IDX_A0CC6EA16995AC4C (editor_id), PRIMARY KEY(episode_id, editor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE episode_actor (episode_id INT NOT NULL, actor_id INT NOT NULL, INDEX IDX_7F7FA0AD362B62A0 (episode_id), INDEX IDX_7F7FA0AD10DAF24A (actor_id), PRIMARY KEY(episode_id, actor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genre (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movie (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, genre_id INT NOT NULL, saga_id INT NOT NULL, name VARCHAR(255) NOT NULL, duration INT NOT NULL, INDEX IDX_1D5EF26FC54C8C93 (type_id), INDEX IDX_1D5EF26F4296D31F (genre_id), INDEX IDX_1D5EF26FB2CCEE2E (saga_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movie_editor (movie_id INT NOT NULL, editor_id INT NOT NULL, INDEX IDX_35366CCF8F93B6FC (movie_id), INDEX IDX_35366CCF6995AC4C (editor_id), PRIMARY KEY(movie_id, editor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movie_actor (movie_id INT NOT NULL, actor_id INT NOT NULL, INDEX IDX_3A374C658F93B6FC (movie_id), INDEX IDX_3A374C6510DAF24A (actor_id), PRIMARY KEY(movie_id, actor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE saga (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE season (id INT AUTO_INCREMENT NOT NULL, serie_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_F0E45BA9D94388BD (serie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE serie (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, genre_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_AA3A9334C54C8C93 (type_id), INDEX IDX_AA3A93344296D31F (genre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE watchlist_item (id INT AUTO_INCREMENT NOT NULL, watchlist_id INT NOT NULL, serie_id INT NOT NULL, season_id INT DEFAULT NULL, episode_id INT DEFAULT NULL, saga_id INT DEFAULT NULL, movie_id INT DEFAULT NULL, item_type VARCHAR(255) NOT NULL, start_date DATE DEFAULT NULL, finish_date DATE DEFAULT NULL, episode_progress INT DEFAULT NULL, mark INT DEFAULT NULL, personnal_note VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_1DEA83F683DD0D94 (watchlist_id), INDEX IDX_1DEA83F6D94388BD (serie_id), INDEX IDX_1DEA83F64EC001D1 (season_id), INDEX IDX_1DEA83F6362B62A0 (episode_id), INDEX IDX_1DEA83F6B2CCEE2E (saga_id), INDEX IDX_1DEA83F68F93B6FC (movie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE episode ADD CONSTRAINT FK_DDAA1CDAD94388BD FOREIGN KEY (serie_id) REFERENCES serie (id)');
        $this->addSql('ALTER TABLE episode ADD CONSTRAINT FK_DDAA1CDA4EC001D1 FOREIGN KEY (season_id) REFERENCES season (id)');
        $this->addSql('ALTER TABLE episode_editor ADD CONSTRAINT FK_A0CC6EA1362B62A0 FOREIGN KEY (episode_id) REFERENCES episode (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE episode_editor ADD CONSTRAINT FK_A0CC6EA16995AC4C FOREIGN KEY (editor_id) REFERENCES editor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE episode_actor ADD CONSTRAINT FK_7F7FA0AD362B62A0 FOREIGN KEY (episode_id) REFERENCES episode (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE episode_actor ADD CONSTRAINT FK_7F7FA0AD10DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie ADD CONSTRAINT FK_1D5EF26FC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE movie ADD CONSTRAINT FK_1D5EF26F4296D31F FOREIGN KEY (genre_id) REFERENCES genre (id)');
        $this->addSql('ALTER TABLE movie ADD CONSTRAINT FK_1D5EF26FB2CCEE2E FOREIGN KEY (saga_id) REFERENCES saga (id)');
        $this->addSql('ALTER TABLE movie_editor ADD CONSTRAINT FK_35366CCF8F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_editor ADD CONSTRAINT FK_35366CCF6995AC4C FOREIGN KEY (editor_id) REFERENCES editor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_actor ADD CONSTRAINT FK_3A374C658F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_actor ADD CONSTRAINT FK_3A374C6510DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE season ADD CONSTRAINT FK_F0E45BA9D94388BD FOREIGN KEY (serie_id) REFERENCES serie (id)');
        $this->addSql('ALTER TABLE serie ADD CONSTRAINT FK_AA3A9334C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE serie ADD CONSTRAINT FK_AA3A93344296D31F FOREIGN KEY (genre_id) REFERENCES genre (id)');
        $this->addSql('ALTER TABLE watchlist_item ADD CONSTRAINT FK_1DEA83F683DD0D94 FOREIGN KEY (watchlist_id) REFERENCES watchlist (id)');
        $this->addSql('ALTER TABLE watchlist_item ADD CONSTRAINT FK_1DEA83F6D94388BD FOREIGN KEY (serie_id) REFERENCES serie (id)');
        $this->addSql('ALTER TABLE watchlist_item ADD CONSTRAINT FK_1DEA83F64EC001D1 FOREIGN KEY (season_id) REFERENCES season (id)');
        $this->addSql('ALTER TABLE watchlist_item ADD CONSTRAINT FK_1DEA83F6362B62A0 FOREIGN KEY (episode_id) REFERENCES episode (id)');
        $this->addSql('ALTER TABLE watchlist_item ADD CONSTRAINT FK_1DEA83F6B2CCEE2E FOREIGN KEY (saga_id) REFERENCES saga (id)');
        $this->addSql('ALTER TABLE watchlist_item ADD CONSTRAINT FK_1DEA83F68F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('ALTER TABLE watchlist ADD CONSTRAINT FK_340388D3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_340388D3A76ED395 ON watchlist (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE episode DROP FOREIGN KEY FK_DDAA1CDAD94388BD');
        $this->addSql('ALTER TABLE episode DROP FOREIGN KEY FK_DDAA1CDA4EC001D1');
        $this->addSql('ALTER TABLE episode_editor DROP FOREIGN KEY FK_A0CC6EA1362B62A0');
        $this->addSql('ALTER TABLE episode_editor DROP FOREIGN KEY FK_A0CC6EA16995AC4C');
        $this->addSql('ALTER TABLE episode_actor DROP FOREIGN KEY FK_7F7FA0AD362B62A0');
        $this->addSql('ALTER TABLE episode_actor DROP FOREIGN KEY FK_7F7FA0AD10DAF24A');
        $this->addSql('ALTER TABLE movie DROP FOREIGN KEY FK_1D5EF26FC54C8C93');
        $this->addSql('ALTER TABLE movie DROP FOREIGN KEY FK_1D5EF26F4296D31F');
        $this->addSql('ALTER TABLE movie DROP FOREIGN KEY FK_1D5EF26FB2CCEE2E');
        $this->addSql('ALTER TABLE movie_editor DROP FOREIGN KEY FK_35366CCF8F93B6FC');
        $this->addSql('ALTER TABLE movie_editor DROP FOREIGN KEY FK_35366CCF6995AC4C');
        $this->addSql('ALTER TABLE movie_actor DROP FOREIGN KEY FK_3A374C658F93B6FC');
        $this->addSql('ALTER TABLE movie_actor DROP FOREIGN KEY FK_3A374C6510DAF24A');
        $this->addSql('ALTER TABLE season DROP FOREIGN KEY FK_F0E45BA9D94388BD');
        $this->addSql('ALTER TABLE serie DROP FOREIGN KEY FK_AA3A9334C54C8C93');
        $this->addSql('ALTER TABLE serie DROP FOREIGN KEY FK_AA3A93344296D31F');
        $this->addSql('ALTER TABLE watchlist_item DROP FOREIGN KEY FK_1DEA83F683DD0D94');
        $this->addSql('ALTER TABLE watchlist_item DROP FOREIGN KEY FK_1DEA83F6D94388BD');
        $this->addSql('ALTER TABLE watchlist_item DROP FOREIGN KEY FK_1DEA83F64EC001D1');
        $this->addSql('ALTER TABLE watchlist_item DROP FOREIGN KEY FK_1DEA83F6362B62A0');
        $this->addSql('ALTER TABLE watchlist_item DROP FOREIGN KEY FK_1DEA83F6B2CCEE2E');
        $this->addSql('ALTER TABLE watchlist_item DROP FOREIGN KEY FK_1DEA83F68F93B6FC');
        $this->addSql('DROP TABLE actor');
        $this->addSql('DROP TABLE episode');
        $this->addSql('DROP TABLE episode_editor');
        $this->addSql('DROP TABLE episode_actor');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE movie');
        $this->addSql('DROP TABLE movie_editor');
        $this->addSql('DROP TABLE movie_actor');
        $this->addSql('DROP TABLE saga');
        $this->addSql('DROP TABLE season');
        $this->addSql('DROP TABLE serie');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE watchlist_item');
        $this->addSql('ALTER TABLE watchlist DROP FOREIGN KEY FK_340388D3A76ED395');
        $this->addSql('DROP INDEX UNIQ_340388D3A76ED395 ON watchlist');
    }
}
