<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240218143730 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consultation CHANGE fichemedicale_id fichemedicale_id INT DEFAULT NULL, CHANGE date_c date_c DATETIME DEFAULT NULL, CHANGE remarques remarques LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consultation CHANGE fichemedicale_id fichemedicale_id INT NOT NULL, CHANGE date_c date_c DATETIME NOT NULL, CHANGE remarques remarques VARCHAR(255) DEFAULT NULL');
    }
}
