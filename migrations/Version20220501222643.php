<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220501222643 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hobby (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE personne_hobby (personne_id INT NOT NULL, hobby_id INT NOT NULL, INDEX IDX_2D85E25EA21BD112 (personne_id), INDEX IDX_2D85E25E322B2123 (hobby_id), PRIMARY KEY(personne_id, hobby_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE personne_hobby ADD CONSTRAINT FK_2D85E25EA21BD112 FOREIGN KEY (personne_id) REFERENCES personne (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE personne_hobby ADD CONSTRAINT FK_2D85E25E322B2123 FOREIGN KEY (hobby_id) REFERENCES hobby (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE personne ADD job_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE personne ADD CONSTRAINT FK_FCEC9EFBE04EA9 FOREIGN KEY (job_id) REFERENCES job (id)');
        $this->addSql('CREATE INDEX IDX_FCEC9EFBE04EA9 ON personne (job_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE personne_hobby DROP FOREIGN KEY FK_2D85E25E322B2123');
        $this->addSql('ALTER TABLE personne DROP FOREIGN KEY FK_FCEC9EFBE04EA9');
        $this->addSql('DROP TABLE hobby');
        $this->addSql('DROP TABLE job');
        $this->addSql('DROP TABLE personne_hobby');
        $this->addSql('DROP INDEX IDX_FCEC9EFBE04EA9 ON personne');
        $this->addSql('ALTER TABLE personne DROP job_id');
    }
}
