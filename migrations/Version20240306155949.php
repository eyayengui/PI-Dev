<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306155949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE answer (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, proposition_choisie_id INT NOT NULL, id_user_id INT DEFAULT NULL, INDEX IDX_DADD4A251E27F6BF (question_id), UNIQUE INDEX UNIQ_DADD4A258D1C6A16 (proposition_choisie_id), INDEX IDX_DADD4A2579F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `like` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, publication_id INT NOT NULL, type TINYINT(1) NOT NULL, INDEX IDX_AC6340B3A76ED395 (user_id), INDEX IDX_AC6340B338B217A7 (publication_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A251E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A258D1C6A16 FOREIGN KEY (proposition_choisie_id) REFERENCES proposition (id)');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A2579F37AE5 FOREIGN KEY (id_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B3A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B338B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE proposition ADD question_id INT DEFAULT NULL, ADD id_user_id INT DEFAULT NULL, ADD score INT NOT NULL');
        $this->addSql('ALTER TABLE proposition ADD CONSTRAINT FK_C7CDC3531E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE proposition ADD CONSTRAINT FK_C7CDC35379F37AE5 FOREIGN KEY (id_user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_C7CDC3531E27F6BF ON proposition (question_id)');
        $this->addSql('CREATE INDEX IDX_C7CDC35379F37AE5 ON proposition (id_user_id)');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EDB96F9E');
        $this->addSql('DROP INDEX IDX_B6F7494EDB96F9E ON question');
        $this->addSql('ALTER TABLE question CHANGE proposition_id id_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E79F37AE5 FOREIGN KEY (id_user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_B6F7494E79F37AE5 ON question (id_user_id)');
        $this->addSql('ALTER TABLE questionnaire DROP FOREIGN KEY FK_7A64DAF6F858F92');
        $this->addSql('DROP INDEX UNIQ_7A64DAF6F858F92 ON questionnaire');
        $this->addSql('ALTER TABLE questionnaire ADD id_user_id INT DEFAULT NULL, ADD date DATETIME NOT NULL, ADD description LONGTEXT DEFAULT NULL, DROP id_u_id');
        $this->addSql('ALTER TABLE questionnaire ADD CONSTRAINT FK_7A64DAF79F37AE5 FOREIGN KEY (id_user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_7A64DAF79F37AE5 ON questionnaire (id_user_id)');
        $this->addSql('ALTER TABLE user CHANGE profile_picture profile_picture VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A251E27F6BF');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A258D1C6A16');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A2579F37AE5');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3A76ED395');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B338B217A7');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE `like`');
        $this->addSql('ALTER TABLE proposition DROP FOREIGN KEY FK_C7CDC3531E27F6BF');
        $this->addSql('ALTER TABLE proposition DROP FOREIGN KEY FK_C7CDC35379F37AE5');
        $this->addSql('DROP INDEX IDX_C7CDC3531E27F6BF ON proposition');
        $this->addSql('DROP INDEX IDX_C7CDC35379F37AE5 ON proposition');
        $this->addSql('ALTER TABLE proposition DROP question_id, DROP id_user_id, DROP score');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E79F37AE5');
        $this->addSql('DROP INDEX IDX_B6F7494E79F37AE5 ON question');
        $this->addSql('ALTER TABLE question CHANGE id_user_id proposition_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EDB96F9E FOREIGN KEY (proposition_id) REFERENCES proposition (id)');
        $this->addSql('CREATE INDEX IDX_B6F7494EDB96F9E ON question (proposition_id)');
        $this->addSql('ALTER TABLE questionnaire DROP FOREIGN KEY FK_7A64DAF79F37AE5');
        $this->addSql('DROP INDEX IDX_7A64DAF79F37AE5 ON questionnaire');
        $this->addSql('ALTER TABLE questionnaire ADD id_u_id INT NOT NULL, DROP id_user_id, DROP date, DROP description');
        $this->addSql('ALTER TABLE questionnaire ADD CONSTRAINT FK_7A64DAF6F858F92 FOREIGN KEY (id_u_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7A64DAF6F858F92 ON questionnaire (id_u_id)');
        $this->addSql('ALTER TABLE `user` CHANGE profile_picture profile_picture VARCHAR(255) DEFAULT NULL');
    }
}
