<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221207190451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE movie CHANGE saga_id saga_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE watchlist_item DROP INDEX UNIQ_1DEA83F683DD0D94, ADD INDEX IDX_1DEA83F683DD0D94 (watchlist_id)');
        $this->addSql('ALTER TABLE watchlist_item CHANGE serie_id serie_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE movie CHANGE saga_id saga_id INT NOT NULL');
        $this->addSql('ALTER TABLE watchlist_item DROP INDEX IDX_1DEA83F683DD0D94, ADD UNIQUE INDEX UNIQ_1DEA83F683DD0D94 (watchlist_id)');
        $this->addSql('ALTER TABLE watchlist_item CHANGE serie_id serie_id INT NOT NULL');
    }
}
