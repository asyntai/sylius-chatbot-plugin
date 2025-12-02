<?php

declare(strict_types=1);

namespace Asyntai\SyliusChatbotPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Asyntai - AI Chatbot for Sylius
 * Creates the asyntai_config table for storing plugin settings.
 */
final class Version20250101000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create asyntai_config table for Asyntai Chatbot plugin';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE IF NOT EXISTS asyntai_config (
            id INT AUTO_INCREMENT NOT NULL,
            config_key VARCHAR(255) NOT NULL,
            config_value LONGTEXT DEFAULT NULL,
            UNIQUE INDEX UNIQ_ASYNTAI_CONFIG_KEY (config_key),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS asyntai_config');
    }
}
