<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240308123342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, headquarter_id INT NOT NULL, nif VARCHAR(9) NOT NULL, name VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, birthdate DATE DEFAULT NULL, gender VARCHAR(100) DEFAULT NULL, phone VARCHAR(12) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, INDEX IDX_34DCD17673478E8C (headquarter_id), UNIQUE INDEX UNIQ_34DCD176ADE62BBB73478E8C (nif, headquarter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD17673478E8C FOREIGN KEY (headquarter_id) REFERENCES headquarter (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD17673478E8C');
        $this->addSql('DROP TABLE person');
    }
}
