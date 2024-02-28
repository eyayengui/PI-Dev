<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240218221217 extends AbstractMigration
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
        $this->addSql('CREATE TABLE exercice (id INT AUTO_INCREMENT NOT NULL, nom_coach VARCHAR(255) NOT NULL, duree VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE programme (id INT AUTO_INCREMENT NOT NULL, activite_id INT DEFAULT NULL, exercice_id INT DEFAULT NULL, lieu VARCHAR(255) NOT NULL, but VARCHAR(255) NOT NULL, nom_l VARCHAR(255) NOT NULL, INDEX IDX_3DDCB9FF9B0F88B1 (activite_id), INDEX IDX_3DDCB9FF89D40298 (exercice_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE proposition (id INT AUTO_INCREMENT NOT NULL, title_proposition VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, questionnaire_id INT DEFAULT NULL, proposition_id INT DEFAULT NULL, title_question VARCHAR(255) NOT NULL, INDEX IDX_B6F7494ECE07E8FF (questionnaire_id), INDEX IDX_B6F7494EDB96F9E (proposition_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE questionnaire (id INT AUTO_INCREMENT NOT NULL, id_u_id INT NOT NULL, title_questionnaire VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_7A64DAF6F858F92 (id_u_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activite_user ADD CONSTRAINT FK_FA43CF3B9B0F88B1 FOREIGN KEY (activite_id) REFERENCES activite (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activite_user ADD CONSTRAINT FK_FA43CF3BA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE programme ADD CONSTRAINT FK_3DDCB9FF9B0F88B1 FOREIGN KEY (activite_id) REFERENCES activite (id)');
        $this->addSql('ALTER TABLE programme ADD CONSTRAINT FK_3DDCB9FF89D40298 FOREIGN KEY (exercice_id) REFERENCES exercice (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494ECE07E8FF FOREIGN KEY (questionnaire_id) REFERENCES questionnaire (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EDB96F9E FOREIGN KEY (proposition_id) REFERENCES proposition (id)');
        $this->addSql('ALTER TABLE questionnaire ADD CONSTRAINT FK_7A64DAF6F858F92 FOREIGN KEY (id_u_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activite_user DROP FOREIGN KEY FK_FA43CF3B9B0F88B1');
        $this->addSql('ALTER TABLE activite_user DROP FOREIGN KEY FK_FA43CF3BA76ED395');
        $this->addSql('ALTER TABLE programme DROP FOREIGN KEY FK_3DDCB9FF9B0F88B1');
        $this->addSql('ALTER TABLE programme DROP FOREIGN KEY FK_3DDCB9FF89D40298');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494ECE07E8FF');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EDB96F9E');
        $this->addSql('ALTER TABLE questionnaire DROP FOREIGN KEY FK_7A64DAF6F858F92');
        $this->addSql('DROP TABLE activite');
        $this->addSql('DROP TABLE activite_user');
        $this->addSql('DROP TABLE exercice');
        $this->addSql('DROP TABLE programme');
        $this->addSql('DROP TABLE proposition');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE questionnaire');
    }
}
