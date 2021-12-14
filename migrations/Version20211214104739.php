<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211214104739 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE movie (id INT AUTO_INCREMENT NOT NULL, imdb_id VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movie_favorite (id INT AUTO_INCREMENT NOT NULL, movie_id INT NOT NULL, user_id INT NOT NULL, date DATE NOT NULL, INDEX IDX_B43315548F93B6FC (movie_id), INDEX IDX_B4331554A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, birth_date DATE NOT NULL, join_date DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE movie_favorite ADD CONSTRAINT FK_B43315548F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('ALTER TABLE movie_favorite ADD CONSTRAINT FK_B4331554A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE movie_favorite DROP FOREIGN KEY FK_B43315548F93B6FC');
        $this->addSql('ALTER TABLE movie_favorite DROP FOREIGN KEY FK_B4331554A76ED395');
        $this->addSql('DROP TABLE movie');
        $this->addSql('DROP TABLE movie_favorite');
        $this->addSql('DROP TABLE user');
    }
}
