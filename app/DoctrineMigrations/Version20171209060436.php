<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171209060436 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_fight DROP FOREIGN KEY FK_C368BF0EA76ED395');
        $this->addSql('ALTER TABLE user_fight DROP FOREIGN KEY FK_C368BF0EAC6657E4');
        $this->addSql('ALTER TABLE user_fight DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE user_fight ADD id INT AUTO_INCREMENT NOT NULL, ADD PRIMARY KEY (id), ADD is_red_corner TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_fight ADD CONSTRAINT FK_C368BF0EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_fight ADD CONSTRAINT FK_C368BF0EAC6657E4 FOREIGN KEY (fight_id) REFERENCES fight (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_fight MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE user_fight DROP FOREIGN KEY FK_C368BF0EA76ED395');
        $this->addSql('ALTER TABLE user_fight DROP FOREIGN KEY FK_C368BF0EAC6657E4');
        $this->addSql('ALTER TABLE user_fight DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE user_fight DROP id, DROP is_red_corner');
        $this->addSql('ALTER TABLE user_fight ADD CONSTRAINT FK_C368BF0EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_fight ADD CONSTRAINT FK_C368BF0EAC6657E4 FOREIGN KEY (fight_id) REFERENCES fight (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_fight ADD PRIMARY KEY (user_id, fight_id)');
    }
}
