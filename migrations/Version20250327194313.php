<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250327194313 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE department (id INT AUTO_INCREMENT NOT NULL, department_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE sales (id INT AUTO_INCREMENT NOT NULL, department_id INT DEFAULT NULL, month VARCHAR(255) NOT NULL, amount INT NOT NULL, INDEX IDX_6B817044AE80F5DF (department_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sales ADD CONSTRAINT FK_6B817044AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE sales DROP FOREIGN KEY FK_6B817044AE80F5DF
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE department
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE sales
        SQL);
    }
}
