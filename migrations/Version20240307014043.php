<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307014043 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answer ADD id_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A2579F37AE5 FOREIGN KEY (id_user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_DADD4A2579F37AE5 ON answer (id_user_id)');
        $this->addSql('ALTER TABLE proposition ADD id_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE proposition ADD CONSTRAINT FK_C7CDC35379F37AE5 FOREIGN KEY (id_user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_C7CDC35379F37AE5 ON proposition (id_user_id)');
        $this->addSql('ALTER TABLE question ADD id_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E79F37AE5 FOREIGN KEY (id_user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_B6F7494E79F37AE5 ON question (id_user_id)');
        $this->addSql('ALTER TABLE questionnaire DROP FOREIGN KEY FK_7A64DAF6F858F92');
        $this->addSql('DROP INDEX UNIQ_7A64DAF6F858F92 ON questionnaire');
        $this->addSql('ALTER TABLE questionnaire ADD id_user_id INT DEFAULT NULL, DROP id_u_id');
        $this->addSql('ALTER TABLE questionnaire ADD CONSTRAINT FK_7A64DAF79F37AE5 FOREIGN KEY (id_user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_7A64DAF79F37AE5 ON questionnaire (id_user_id)');
        $this->addSql('ALTER TABLE user ADD profile_picture VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A2579F37AE5');
        $this->addSql('DROP INDEX IDX_DADD4A2579F37AE5 ON answer');
        $this->addSql('ALTER TABLE answer DROP id_user_id');
        $this->addSql('ALTER TABLE proposition DROP FOREIGN KEY FK_C7CDC35379F37AE5');
        $this->addSql('DROP INDEX IDX_C7CDC35379F37AE5 ON proposition');
        $this->addSql('ALTER TABLE proposition DROP id_user_id');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E79F37AE5');
        $this->addSql('DROP INDEX IDX_B6F7494E79F37AE5 ON question');
        $this->addSql('ALTER TABLE question DROP id_user_id');
        $this->addSql('ALTER TABLE questionnaire DROP FOREIGN KEY FK_7A64DAF79F37AE5');
        $this->addSql('DROP INDEX IDX_7A64DAF79F37AE5 ON questionnaire');
        $this->addSql('ALTER TABLE questionnaire ADD id_u_id INT NOT NULL, DROP id_user_id');
        $this->addSql('ALTER TABLE questionnaire ADD CONSTRAINT FK_7A64DAF6F858F92 FOREIGN KEY (id_u_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7A64DAF6F858F92 ON questionnaire (id_u_id)');
        $this->addSql('ALTER TABLE `user` DROP profile_picture');
    }
}
