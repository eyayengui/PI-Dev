<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240221213721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activite (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, date DATE NOT NULL, genre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE activite_user (activite_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_FA43CF3B9B0F88B1 (activite_id), INDEX IDX_FA43CF3BA76ED395 (user_id), PRIMARY KEY(activite_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, contenu_c VARCHAR(255) NOT NULL, publication_id INT DEFAULT NULL, id_user_id INT DEFAULT NULL, INDEX IDX_67F068BC38B217A7 (publication_id), INDEX IDX_67F068BC79F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE consultation (id INT AUTO_INCREMENT NOT NULL, date_c DATETIME NOT NULL, remarques LONGTEXT DEFAULT NULL, pathologie VARCHAR(255) NOT NULL, confirmation TINYINT(1) DEFAULT NULL, fichemedicale_id INT DEFAULT NULL, INDEX IDX_964685A674F33188 (fichemedicale_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE exercice (id INT AUTO_INCREMENT NOT NULL, nom_coach VARCHAR(255) NOT NULL, duree VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE fichemedicale (id INT AUTO_INCREMENT NOT NULL, date_creation DATE NOT NULL, derniere_maj DATE NOT NULL, id_p_id INT NOT NULL, id_t_id INT NOT NULL, INDEX IDX_85AA310585B7FA0 (id_p_id), INDEX IDX_85AA310D739E8F7 (id_t_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE programme (id INT AUTO_INCREMENT NOT NULL, lieu VARCHAR(255) NOT NULL, but VARCHAR(255) NOT NULL, nom_l VARCHAR(255) NOT NULL, activite_id INT DEFAULT NULL, exercice_id INT DEFAULT NULL, INDEX IDX_3DDCB9FF9B0F88B1 (activite_id), INDEX IDX_3DDCB9FF89D40298 (exercice_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE publication (id INT AUTO_INCREMENT NOT NULL, titre_p VARCHAR(255) NOT NULL, description_p VARCHAR(255) NOT NULL, image_p VARCHAR(255) NOT NULL, date_p DATE DEFAULT NULL, id_user_id INT NOT NULL, INDEX IDX_AF3C677979F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, age INT DEFAULT NULL, certificat VARCHAR(255) DEFAULT NULL, is_banned TINYINT(1) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE activite_user ADD CONSTRAINT FK_FA43CF3B9B0F88B1 FOREIGN KEY (activite_id) REFERENCES activite (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activite_user ADD CONSTRAINT FK_FA43CF3BA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC38B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC79F37AE5 FOREIGN KEY (id_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A674F33188 FOREIGN KEY (fichemedicale_id) REFERENCES fichemedicale (id)');
        $this->addSql('ALTER TABLE fichemedicale ADD CONSTRAINT FK_85AA310585B7FA0 FOREIGN KEY (id_p_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE fichemedicale ADD CONSTRAINT FK_85AA310D739E8F7 FOREIGN KEY (id_t_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE programme ADD CONSTRAINT FK_3DDCB9FF9B0F88B1 FOREIGN KEY (activite_id) REFERENCES activite (id)');
        $this->addSql('ALTER TABLE programme ADD CONSTRAINT FK_3DDCB9FF89D40298 FOREIGN KEY (exercice_id) REFERENCES exercice (id)');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C677979F37AE5 FOREIGN KEY (id_user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activite_user DROP FOREIGN KEY FK_FA43CF3B9B0F88B1');
        $this->addSql('ALTER TABLE activite_user DROP FOREIGN KEY FK_FA43CF3BA76ED395');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC38B217A7');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC79F37AE5');
        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A674F33188');
        $this->addSql('ALTER TABLE fichemedicale DROP FOREIGN KEY FK_85AA310585B7FA0');
        $this->addSql('ALTER TABLE fichemedicale DROP FOREIGN KEY FK_85AA310D739E8F7');
        $this->addSql('ALTER TABLE programme DROP FOREIGN KEY FK_3DDCB9FF9B0F88B1');
        $this->addSql('ALTER TABLE programme DROP FOREIGN KEY FK_3DDCB9FF89D40298');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C677979F37AE5');
        $this->addSql('DROP TABLE activite');
        $this->addSql('DROP TABLE activite_user');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE consultation');
        $this->addSql('DROP TABLE exercice');
        $this->addSql('DROP TABLE fichemedicale');
        $this->addSql('DROP TABLE programme');
        $this->addSql('DROP TABLE publication');
        $this->addSql('DROP TABLE `user`');
    }
}
