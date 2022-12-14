<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221207184503 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE movie DROP FOREIGN KEY FK_1D5EF26F4296D31F');
        $this->addSql('ALTER TABLE serie DROP FOREIGN KEY FK_AA3A93344296D31F');
        $this->addSql('ALTER TABLE movie DROP FOREIGN KEY FK_1D5EF26FC54C8C93');
        $this->addSql('ALTER TABLE serie DROP FOREIGN KEY FK_AA3A9334C54C8C93');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP INDEX IDX_1D5EF26FC54C8C93 ON movie');
        $this->addSql('DROP INDEX IDX_1D5EF26F4296D31F ON movie');
        $this->addSql('ALTER TABLE movie ADD genres VARCHAR(255) DEFAULT NULL, DROP type_id, DROP genre_id');
        $this->addSql('DROP INDEX IDX_AA3A93344296D31F ON serie');
        $this->addSql('DROP INDEX IDX_AA3A9334C54C8C93 ON serie');
        $this->addSql('ALTER TABLE serie ADD genres VARCHAR(255) DEFAULT NULL, DROP type_id, DROP genre_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE genre (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE movie ADD type_id INT DEFAULT NULL, ADD genre_id INT DEFAULT NULL, DROP genres');
        $this->addSql('ALTER TABLE movie ADD CONSTRAINT FK_1D5EF26FC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE movie ADD CONSTRAINT FK_1D5EF26F4296D31F FOREIGN KEY (genre_id) REFERENCES genre (id)');
        $this->addSql('CREATE INDEX IDX_1D5EF26FC54C8C93 ON movie (type_id)');
        $this->addSql('CREATE INDEX IDX_1D5EF26F4296D31F ON movie (genre_id)');
        $this->addSql('ALTER TABLE serie ADD type_id INT NOT NULL, ADD genre_id INT NOT NULL, DROP genres');
        $this->addSql('ALTER TABLE serie ADD CONSTRAINT FK_AA3A93344296D31F FOREIGN KEY (genre_id) REFERENCES genre (id)');
        $this->addSql('ALTER TABLE serie ADD CONSTRAINT FK_AA3A9334C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('CREATE INDEX IDX_AA3A93344296D31F ON serie (genre_id)');
        $this->addSql('CREATE INDEX IDX_AA3A9334C54C8C93 ON serie (type_id)');
    }
}
