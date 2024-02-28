<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240228094147 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consultation ADD idp_id INT NOT NULL, ADD idt_id INT NOT NULL');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A6B49202 FOREIGN KEY (idp_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A68FD60555 FOREIGN KEY (idt_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_964685A6B49202 ON consultation (idp_id)');
        $this->addSql('CREATE INDEX IDX_964685A68FD60555 ON consultation (idt_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A6B49202');
        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A68FD60555');
        $this->addSql('DROP INDEX IDX_964685A6B49202 ON consultation');
        $this->addSql('DROP INDEX IDX_964685A68FD60555 ON consultation');
        $this->addSql('ALTER TABLE consultation DROP idp_id, DROP idt_id');
    }
}
