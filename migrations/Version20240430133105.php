<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240430133105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contact ADD statut_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638F6203804 FOREIGN KEY (statut_id) REFERENCES statut (id)');
        $this->addSql('CREATE INDEX IDX_4C62E638F6203804 ON contact (statut_id)');
        $this->addSql('ALTER TABLE reservation ADD voyage_id INT DEFAULT NULL, ADD statut_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495568C9E5AF FOREIGN KEY (voyage_id) REFERENCES voyage (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955F6203804 FOREIGN KEY (statut_id) REFERENCES statut (id)');
        $this->addSql('CREATE INDEX IDX_42C8495568C9E5AF ON reservation (voyage_id)');
        $this->addSql('CREATE INDEX IDX_42C84955F6203804 ON reservation (statut_id)');
        $this->addSql('ALTER TABLE voyage ADD categorie_id INT DEFAULT NULL, ADD destination_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE voyage ADD CONSTRAINT FK_3F9D8955BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE voyage ADD CONSTRAINT FK_3F9D8955816C6140 FOREIGN KEY (destination_id) REFERENCES destination (id)');
        $this->addSql('CREATE INDEX IDX_3F9D8955BCF5E72D ON voyage (categorie_id)');
        $this->addSql('CREATE INDEX IDX_3F9D8955816C6140 ON voyage (destination_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE voyage DROP FOREIGN KEY FK_3F9D8955BCF5E72D');
        $this->addSql('ALTER TABLE voyage DROP FOREIGN KEY FK_3F9D8955816C6140');
        $this->addSql('DROP INDEX IDX_3F9D8955BCF5E72D ON voyage');
        $this->addSql('DROP INDEX IDX_3F9D8955816C6140 ON voyage');
        $this->addSql('ALTER TABLE voyage DROP categorie_id, DROP destination_id');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E638F6203804');
        $this->addSql('DROP INDEX IDX_4C62E638F6203804 ON contact');
        $this->addSql('ALTER TABLE contact DROP statut_id');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495568C9E5AF');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955F6203804');
        $this->addSql('DROP INDEX IDX_42C8495568C9E5AF ON reservation');
        $this->addSql('DROP INDEX IDX_42C84955F6203804 ON reservation');
        $this->addSql('ALTER TABLE reservation DROP voyage_id, DROP statut_id');
    }
}
