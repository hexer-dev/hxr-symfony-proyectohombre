<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240313105720 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document ADD person_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('CREATE INDEX IDX_D8698A76217BBB47 ON document (person_id)');
        $this->addSql('ALTER TABLE person ADD created_by_id INT DEFAULT NULL, ADD drug_id INT DEFAULT NULL, ADD type VARCHAR(150) NOT NULL, ADD nationality VARCHAR(255) DEFAULT NULL, ADD homeless TINYINT(1) DEFAULT NULL, ADD created_at DATE NOT NULL, ADD contact_name VARCHAR(255) DEFAULT NULL, ADD contact_phone VARCHAR(12) DEFAULT NULL');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176AABCA765 FOREIGN KEY (drug_id) REFERENCES drug (id)');
        $this->addSql('CREATE INDEX IDX_34DCD176B03A8386 ON person (created_by_id)');
        $this->addSql('CREATE INDEX IDX_34DCD176AABCA765 ON person (drug_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76217BBB47');
        $this->addSql('DROP INDEX IDX_D8698A76217BBB47 ON document');
        $this->addSql('ALTER TABLE document DROP person_id');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD176B03A8386');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD176AABCA765');
        $this->addSql('DROP INDEX IDX_34DCD176B03A8386 ON person');
        $this->addSql('DROP INDEX IDX_34DCD176AABCA765 ON person');
        $this->addSql('ALTER TABLE person DROP created_by_id, DROP drug_id, DROP type, DROP nationality, DROP homeless, DROP created_at, DROP contact_name, DROP contact_phone');
    }
}
