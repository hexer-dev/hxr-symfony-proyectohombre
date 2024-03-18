<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240315085644 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE person_in_program (id INT AUTO_INCREMENT NOT NULL, person_id INT NOT NULL, program_id INT NOT NULL, reduct_irpf TINYINT(1) DEFAULT NULL, INDEX IDX_EFE5377F217BBB47 (person_id), INDEX IDX_EFE5377F3EB8070A (program_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE person_in_program ADD CONSTRAINT FK_EFE5377F217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE person_in_program ADD CONSTRAINT FK_EFE5377F3EB8070A FOREIGN KEY (program_id) REFERENCES program (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person_in_program DROP FOREIGN KEY FK_EFE5377F217BBB47');
        $this->addSql('ALTER TABLE person_in_program DROP FOREIGN KEY FK_EFE5377F3EB8070A');
        $this->addSql('DROP TABLE person_in_program');
    }
}
