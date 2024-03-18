<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240318085923 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE timetable (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, updated_by_id INT DEFAULT NULL, date_start DATE NOT NULL, date_end DATE DEFAULT NULL, INDEX IDX_6B1F670B03A8386 (created_by_id), INDEX IDX_6B1F670896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE timetable_person_in_program (timetable_id INT NOT NULL, person_in_program_id INT NOT NULL, INDEX IDX_B2534A30CC306847 (timetable_id), INDEX IDX_B2534A30798B5C71 (person_in_program_id), PRIMARY KEY(timetable_id, person_in_program_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE timetable ADD CONSTRAINT FK_6B1F670B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE timetable ADD CONSTRAINT FK_6B1F670896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE timetable_person_in_program ADD CONSTRAINT FK_B2534A30CC306847 FOREIGN KEY (timetable_id) REFERENCES timetable (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE timetable_person_in_program ADD CONSTRAINT FK_B2534A30798B5C71 FOREIGN KEY (person_in_program_id) REFERENCES person_in_program (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE timetable DROP FOREIGN KEY FK_6B1F670B03A8386');
        $this->addSql('ALTER TABLE timetable DROP FOREIGN KEY FK_6B1F670896DBBDE');
        $this->addSql('ALTER TABLE timetable_person_in_program DROP FOREIGN KEY FK_B2534A30CC306847');
        $this->addSql('ALTER TABLE timetable_person_in_program DROP FOREIGN KEY FK_B2534A30798B5C71');
        $this->addSql('DROP TABLE timetable');
        $this->addSql('DROP TABLE timetable_person_in_program');
    }
}
