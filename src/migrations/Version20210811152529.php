<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210811152529 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company_process (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, process_id INT NOT NULL, state_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_finished TINYINT(1) DEFAULT NULL, INDEX IDX_A64F0241979B1AD6 (company_id), INDEX IDX_A64F02417EC2F574 (process_id), INDEX IDX_A64F02415D83CC1 (state_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company_process_step (id INT AUTO_INCREMENT NOT NULL, company_process_id INT DEFAULT NULL, step_id INT DEFAULT NULL, validated_by_id INT NOT NULL, validated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_529DE6C7C34C1CE2 (company_process_id), INDEX IDX_529DE6C773B21E9C (step_id), INDEX IDX_529DE6C7C69DE5E5 (validated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE process (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE state (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE step (id INT AUTO_INCREMENT NOT NULL, process_id INT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, helper VARCHAR(255) NOT NULL, is_required TINYINT(1) NOT NULL, weight INT NOT NULL, INDEX IDX_43B9FE3C7EC2F574 (process_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE company_process ADD CONSTRAINT FK_A64F0241979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE company_process ADD CONSTRAINT FK_A64F02417EC2F574 FOREIGN KEY (process_id) REFERENCES process (id)');
        $this->addSql('ALTER TABLE company_process ADD CONSTRAINT FK_A64F02415D83CC1 FOREIGN KEY (state_id) REFERENCES state (id)');
        $this->addSql('ALTER TABLE company_process_step ADD CONSTRAINT FK_529DE6C7C34C1CE2 FOREIGN KEY (company_process_id) REFERENCES company_process (id)');
        $this->addSql('ALTER TABLE company_process_step ADD CONSTRAINT FK_529DE6C773B21E9C FOREIGN KEY (step_id) REFERENCES step (id)');
        $this->addSql('ALTER TABLE company_process_step ADD CONSTRAINT FK_529DE6C7C69DE5E5 FOREIGN KEY (validated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE step ADD CONSTRAINT FK_43B9FE3C7EC2F574 FOREIGN KEY (process_id) REFERENCES process (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company_process_step DROP FOREIGN KEY FK_529DE6C7C34C1CE2');
        $this->addSql('ALTER TABLE company_process DROP FOREIGN KEY FK_A64F02417EC2F574');
        $this->addSql('ALTER TABLE step DROP FOREIGN KEY FK_43B9FE3C7EC2F574');
        $this->addSql('ALTER TABLE company_process DROP FOREIGN KEY FK_A64F02415D83CC1');
        $this->addSql('ALTER TABLE company_process_step DROP FOREIGN KEY FK_529DE6C773B21E9C');
        $this->addSql('DROP TABLE company_process');
        $this->addSql('DROP TABLE company_process_step');
        $this->addSql('DROP TABLE process');
        $this->addSql('DROP TABLE state');
        $this->addSql('DROP TABLE step');
    }
}
