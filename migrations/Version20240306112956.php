<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306112956 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE program (id INT AUTO_INCREMENT NOT NULL, headquarter_id INT NOT NULL, code VARCHAR(150) NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, date_start DATE NOT NULL, date_end DATE NOT NULL, INDEX IDX_92ED778473478E8C (headquarter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE program ADD CONSTRAINT FK_92ED778473478E8C FOREIGN KEY (headquarter_id) REFERENCES headquarter (id)');
        $this->addSql('ALTER TABLE document ADD program_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A763EB8070A FOREIGN KEY (program_id) REFERENCES program (id)');
        $this->addSql('CREATE INDEX IDX_D8698A763EB8070A ON document (program_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A763EB8070A');
        $this->addSql('ALTER TABLE program DROP FOREIGN KEY FK_92ED778473478E8C');
        $this->addSql('DROP TABLE program');
        $this->addSql('DROP INDEX IDX_D8698A763EB8070A ON document');
        $this->addSql('ALTER TABLE document DROP program_id');
    }
}
