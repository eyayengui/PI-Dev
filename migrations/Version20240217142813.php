<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240217142813 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, publication_id INT DEFAULT NULL, contenu_c VARCHAR(255) NOT NULL, id_user INT NOT NULL, INDEX IDX_67F068BC38B217A7 (publication_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE consultation (id INT AUTO_INCREMENT NOT NULL, fichemedicale_id INT NOT NULL, date_c DATETIME NOT NULL, remarques LONGTEXT DEFAULT NULL, pathologie VARCHAR(255) NOT NULL, INDEX IDX_964685A674F33188 (fichemedicale_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fichemedicale (id INT AUTO_INCREMENT NOT NULL, id_p_id INT NOT NULL, id_t_id INT NOT NULL, date_creation DATE NOT NULL, derniere_maj DATE NOT NULL, INDEX IDX_85AA310585B7FA0 (id_p_id), INDEX IDX_85AA310D739E8F7 (id_t_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publication (id INT AUTO_INCREMENT NOT NULL, titre_p VARCHAR(255) NOT NULL, description_p VARCHAR(255) NOT NULL, image_p VARCHAR(255) NOT NULL, date_p DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC38B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A674F33188 FOREIGN KEY (fichemedicale_id) REFERENCES fichemedicale (id)');
        $this->addSql('ALTER TABLE fichemedicale ADD CONSTRAINT FK_85AA310585B7FA0 FOREIGN KEY (id_p_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE fichemedicale ADD CONSTRAINT FK_85AA310D739E8F7 FOREIGN KEY (id_t_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC38B217A7');
        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A674F33188');
        $this->addSql('ALTER TABLE fichemedicale DROP FOREIGN KEY FK_85AA310585B7FA0');
        $this->addSql('ALTER TABLE fichemedicale DROP FOREIGN KEY FK_85AA310D739E8F7');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE consultation');
        $this->addSql('DROP TABLE fichemedicale');
        $this->addSql('DROP TABLE publication');
    }
}
