<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240209203934 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consultation CHANGE id_f id_f INT DEFAULT NULL');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A6B146819F FOREIGN KEY (id_f) REFERENCES fichemedicale (id)');
        $this->addSql('CREATE INDEX IDX_964685A6B146819F ON consultation (id_f)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A6B146819F');
        $this->addSql('DROP INDEX IDX_964685A6B146819F ON consultation');
        $this->addSql('ALTER TABLE consultation CHANGE id_f id_f INT NOT NULL');
    }
}
