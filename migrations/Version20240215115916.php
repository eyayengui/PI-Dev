<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240215115916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consultation DROP id_p, DROP id_t, DROP heure_c, DROP duree, CHANGE date_c date_c DATETIME DEFAULT NULL, CHANGE remarques remarques LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE fichemedicale ADD id_t INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consultation ADD id_p INT NOT NULL, ADD id_t INT NOT NULL, ADD heure_c TIME NOT NULL, ADD duree INT NOT NULL, CHANGE date_c date_c DATE NOT NULL, CHANGE remarques remarques VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE fichemedicale DROP id_t');
    }
}
