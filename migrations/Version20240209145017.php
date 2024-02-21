<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240209145017 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fichemedicale (id INT AUTO_INCREMENT NOT NULL, id_p INT NOT NULL, date_creation DATE NOT NULL, derniere_maj DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE consultation ADD id_p INT NOT NULL, ADD id_t INT NOT NULL, ADD id_f INT NOT NULL, ADD date_c DATE NOT NULL, ADD heure_c TIME NOT NULL, ADD duree INT NOT NULL, ADD remarques VARCHAR(255) DEFAULT NULL, ADD pathologie VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE fichemedicale');
        $this->addSql('ALTER TABLE consultation DROP id_p, DROP id_t, DROP id_f, DROP date_c, DROP heure_c, DROP duree, DROP remarques, DROP pathologie');
    }
}
