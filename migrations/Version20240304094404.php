<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240304094404 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activite (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, date DATE NOT NULL, genre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activite_user (activite_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_FA43CF3B9B0F88B1 (activite_id), INDEX IDX_FA43CF3BA76ED395 (user_id), PRIMARY KEY(activite_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE answer (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, proposition_choisie_id INT NOT NULL, INDEX IDX_DADD4A251E27F6BF (question_id), UNIQUE INDEX UNIQ_DADD4A258D1C6A16 (proposition_choisie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, publication_id INT DEFAULT NULL, id_user_id INT DEFAULT NULL, contenu_c VARCHAR(255) NOT NULL, INDEX IDX_67F068BC38B217A7 (publication_id), INDEX IDX_67F068BC79F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE consultation (id INT AUTO_INCREMENT NOT NULL, fichemedicale_id INT DEFAULT NULL, idp_id INT NOT NULL, idt_id INT NOT NULL, date_c DATETIME NOT NULL, remarques LONGTEXT DEFAULT NULL, pathologie VARCHAR(255) NOT NULL, confirmation TINYINT(1) DEFAULT NULL, INDEX IDX_964685A674F33188 (fichemedicale_id), INDEX IDX_964685A6B49202 (idp_id), INDEX IDX_964685A68FD60555 (idt_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exercice (id INT AUTO_INCREMENT NOT NULL, nom_coach VARCHAR(255) NOT NULL, duree VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fichemedicale (id INT AUTO_INCREMENT NOT NULL, id_p_id INT NOT NULL, id_t_id INT NOT NULL, date_creation DATE NOT NULL, derniere_maj DATE NOT NULL, INDEX IDX_85AA310585B7FA0 (id_p_id), INDEX IDX_85AA310D739E8F7 (id_t_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `like` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, publication_id INT NOT NULL, type TINYINT(1) NOT NULL, INDEX IDX_AC6340B3A76ED395 (user_id), INDEX IDX_AC6340B338B217A7 (publication_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE programme (id INT AUTO_INCREMENT NOT NULL, activite_id INT DEFAULT NULL, exercice_id INT DEFAULT NULL, lieu VARCHAR(255) NOT NULL, but VARCHAR(255) NOT NULL, nom_l VARCHAR(255) NOT NULL, INDEX IDX_3DDCB9FF9B0F88B1 (activite_id), INDEX IDX_3DDCB9FF89D40298 (exercice_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE proposition (id INT AUTO_INCREMENT NOT NULL, question_id INT DEFAULT NULL, title_proposition VARCHAR(255) NOT NULL, score INT NOT NULL, INDEX IDX_C7CDC3531E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publication (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, titre_p VARCHAR(255) NOT NULL, description_p VARCHAR(255) NOT NULL, image_p VARCHAR(255) NOT NULL, date_p DATE DEFAULT NULL, INDEX IDX_AF3C677979F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, questionnaire_id INT DEFAULT NULL, title_question VARCHAR(255) NOT NULL, INDEX IDX_B6F7494ECE07E8FF (questionnaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE questionnaire (id INT AUTO_INCREMENT NOT NULL, id_u_id INT NOT NULL, title_questionnaire VARCHAR(255) NOT NULL, date DATETIME NOT NULL, description LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_7A64DAF6F858F92 (id_u_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, age INT DEFAULT NULL, is_banned TINYINT(1) DEFAULT NULL, is_verified TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activite_user ADD CONSTRAINT FK_FA43CF3B9B0F88B1 FOREIGN KEY (activite_id) REFERENCES activite (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activite_user ADD CONSTRAINT FK_FA43CF3BA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A251E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A258D1C6A16 FOREIGN KEY (proposition_choisie_id) REFERENCES proposition (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC38B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC79F37AE5 FOREIGN KEY (id_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A674F33188 FOREIGN KEY (fichemedicale_id) REFERENCES fichemedicale (id)');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A6B49202 FOREIGN KEY (idp_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A68FD60555 FOREIGN KEY (idt_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE fichemedicale ADD CONSTRAINT FK_85AA310585B7FA0 FOREIGN KEY (id_p_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE fichemedicale ADD CONSTRAINT FK_85AA310D739E8F7 FOREIGN KEY (id_t_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B3A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B338B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE programme ADD CONSTRAINT FK_3DDCB9FF9B0F88B1 FOREIGN KEY (activite_id) REFERENCES activite (id)');
        $this->addSql('ALTER TABLE programme ADD CONSTRAINT FK_3DDCB9FF89D40298 FOREIGN KEY (exercice_id) REFERENCES exercice (id)');
        $this->addSql('ALTER TABLE proposition ADD CONSTRAINT FK_C7CDC3531E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C677979F37AE5 FOREIGN KEY (id_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494ECE07E8FF FOREIGN KEY (questionnaire_id) REFERENCES questionnaire (id)');
        $this->addSql('ALTER TABLE questionnaire ADD CONSTRAINT FK_7A64DAF6F858F92 FOREIGN KEY (id_u_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activite_user DROP FOREIGN KEY FK_FA43CF3B9B0F88B1');
        $this->addSql('ALTER TABLE activite_user DROP FOREIGN KEY FK_FA43CF3BA76ED395');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A251E27F6BF');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A258D1C6A16');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC38B217A7');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC79F37AE5');
        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A674F33188');
        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A6B49202');
        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A68FD60555');
        $this->addSql('ALTER TABLE fichemedicale DROP FOREIGN KEY FK_85AA310585B7FA0');
        $this->addSql('ALTER TABLE fichemedicale DROP FOREIGN KEY FK_85AA310D739E8F7');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3A76ED395');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B338B217A7');
        $this->addSql('ALTER TABLE programme DROP FOREIGN KEY FK_3DDCB9FF9B0F88B1');
        $this->addSql('ALTER TABLE programme DROP FOREIGN KEY FK_3DDCB9FF89D40298');
        $this->addSql('ALTER TABLE proposition DROP FOREIGN KEY FK_C7CDC3531E27F6BF');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C677979F37AE5');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494ECE07E8FF');
        $this->addSql('ALTER TABLE questionnaire DROP FOREIGN KEY FK_7A64DAF6F858F92');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE activite');
        $this->addSql('DROP TABLE activite_user');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE consultation');
        $this->addSql('DROP TABLE exercice');
        $this->addSql('DROP TABLE fichemedicale');
        $this->addSql('DROP TABLE `like`');
        $this->addSql('DROP TABLE programme');
        $this->addSql('DROP TABLE proposition');
        $this->addSql('DROP TABLE publication');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE questionnaire');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
