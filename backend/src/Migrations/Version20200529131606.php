<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200529131606 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE participant (id INT AUTO_INCREMENT NOT NULL, history_id INT NOT NULL, player_id INT NOT NULL, elo_change INT NOT NULL, INDEX IDX_D79F6B111E058452 (history_id), INDEX IDX_D79F6B1199E6F5DF (player_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B111E058452 FOREIGN KEY (history_id) REFERENCES history (id)');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B1199E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('DROP TABLE roster');
        $this->addSql('DROP TABLE team');
        $this->addSql('ALTER TABLE history DROP winner, DROP loser, DROP winner_gain, DROP loser_gain, CHANGE create_time creation_time DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE roster (id INT AUTO_INCREMENT NOT NULL, team INT NOT NULL, player INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE participant');
        $this->addSql('ALTER TABLE history ADD winner INT NOT NULL, ADD loser INT NOT NULL, ADD winner_gain INT NOT NULL, ADD loser_gain INT NOT NULL, CHANGE creation_time create_time DATETIME NOT NULL');
    }
}
