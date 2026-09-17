<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260917134548 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE orchid (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE watering (id INT AUTO_INCREMENT NOT NULL, cycle_step INT NOT NULL, orchid_id INT NOT NULL, INDEX IDX_818F9D316C397E33 (orchid_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE watering ADD CONSTRAINT FK_818F9D316C397E33 FOREIGN KEY (orchid_id) REFERENCES orchid (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE watering DROP FOREIGN KEY FK_818F9D316C397E33');
        $this->addSql('DROP TABLE orchid');
        $this->addSql('DROP TABLE watering');
    }
}
