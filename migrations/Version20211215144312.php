<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211215144312 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993988F93B6FC');
        $this->addSql('DROP INDEX IDX_F52993988F93B6FC ON `order`');
        $this->addSql('ALTER TABLE `order` ADD movie VARCHAR(255) NOT NULL, DROP movie_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD movie_id INT NOT NULL, DROP movie');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993988F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('CREATE INDEX IDX_F52993988F93B6FC ON `order` (movie_id)');
    }
}
