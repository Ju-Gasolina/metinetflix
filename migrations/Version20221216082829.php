<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221216082829 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE episode ADD air_date VARCHAR(255) NOT NULL, ADD poster_path VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE movie ADD release_date VARCHAR(255) NOT NULL, ADD poster_path VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE saga ADD poster_path VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE season ADD air_date VARCHAR(255) NOT NULL, ADD poster_path VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE serie ADD first_air_date VARCHAR(255) NOT NULL, ADD poster_path VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE episode DROP air_date, DROP poster_path');
        $this->addSql('ALTER TABLE movie DROP release_date, DROP poster_path');
        $this->addSql('ALTER TABLE saga DROP poster_path');
        $this->addSql('ALTER TABLE season DROP air_date, DROP poster_path');
        $this->addSql('ALTER TABLE serie DROP first_air_date, DROP poster_path');
    }
}
