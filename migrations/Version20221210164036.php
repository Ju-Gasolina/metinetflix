<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221210164036 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE episode_actor DROP FOREIGN KEY FK_7F7FA0AD10DAF24A');
        $this->addSql('ALTER TABLE episode_actor DROP FOREIGN KEY FK_7F7FA0AD362B62A0');
        $this->addSql('ALTER TABLE episode_editor DROP FOREIGN KEY FK_A0CC6EA16995AC4C');
        $this->addSql('ALTER TABLE episode_editor DROP FOREIGN KEY FK_A0CC6EA1362B62A0');
        $this->addSql('ALTER TABLE movie_actor DROP FOREIGN KEY FK_3A374C658F93B6FC');
        $this->addSql('ALTER TABLE movie_actor DROP FOREIGN KEY FK_3A374C6510DAF24A');
        $this->addSql('ALTER TABLE movie_editor DROP FOREIGN KEY FK_35366CCF6995AC4C');
        $this->addSql('ALTER TABLE movie_editor DROP FOREIGN KEY FK_35366CCF8F93B6FC');
        $this->addSql('DROP TABLE actor');
        $this->addSql('DROP TABLE editor');
        $this->addSql('DROP TABLE episode_actor');
        $this->addSql('DROP TABLE episode_editor');
        $this->addSql('DROP TABLE movie_actor');
        $this->addSql('DROP TABLE movie_editor');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE actor (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE editor (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE episode_actor (episode_id INT NOT NULL, actor_id INT NOT NULL, INDEX IDX_7F7FA0AD362B62A0 (episode_id), INDEX IDX_7F7FA0AD10DAF24A (actor_id), PRIMARY KEY(episode_id, actor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE episode_editor (episode_id INT NOT NULL, editor_id INT NOT NULL, INDEX IDX_A0CC6EA1362B62A0 (episode_id), INDEX IDX_A0CC6EA16995AC4C (editor_id), PRIMARY KEY(episode_id, editor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE movie_actor (movie_id INT NOT NULL, actor_id INT NOT NULL, INDEX IDX_3A374C658F93B6FC (movie_id), INDEX IDX_3A374C6510DAF24A (actor_id), PRIMARY KEY(movie_id, actor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE movie_editor (movie_id INT NOT NULL, editor_id INT NOT NULL, INDEX IDX_35366CCF8F93B6FC (movie_id), INDEX IDX_35366CCF6995AC4C (editor_id), PRIMARY KEY(movie_id, editor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE episode_actor ADD CONSTRAINT FK_7F7FA0AD10DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE episode_actor ADD CONSTRAINT FK_7F7FA0AD362B62A0 FOREIGN KEY (episode_id) REFERENCES episode (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE episode_editor ADD CONSTRAINT FK_A0CC6EA16995AC4C FOREIGN KEY (editor_id) REFERENCES editor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE episode_editor ADD CONSTRAINT FK_A0CC6EA1362B62A0 FOREIGN KEY (episode_id) REFERENCES episode (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_actor ADD CONSTRAINT FK_3A374C658F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_actor ADD CONSTRAINT FK_3A374C6510DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_editor ADD CONSTRAINT FK_35366CCF6995AC4C FOREIGN KEY (editor_id) REFERENCES editor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_editor ADD CONSTRAINT FK_35366CCF8F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
    }
}
