<?php

declare(strict_types=1);

/**
 * Asyntai - AI Chatbot for Sylius
 *
 * @category  Asyntai
 * @package   AsyntaiSyliusChatbotPlugin
 * @author    Asyntai <hello@asyntai.com>
 * @copyright Copyright (c) Asyntai
 * @license   MIT License
 */

namespace Asyntai\SyliusChatbotPlugin\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'asyntai:install',
    description: 'Install the Asyntai Chatbot plugin (creates database table)'
)]
final class InstallCommand extends Command
{
    public function __construct(
        private readonly Connection $connection
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Installing Asyntai Chatbot Plugin');

        try {
            // Check if table already exists
            $schemaManager = $this->connection->createSchemaManager();
            $tableExists = $schemaManager->tablesExist(['asyntai_config']);

            if ($tableExists) {
                $io->success('Asyntai config table already exists. Nothing to do.');
                return Command::SUCCESS;
            }

            // Create the table
            $this->connection->executeStatement('
                CREATE TABLE asyntai_config (
                    id INT AUTO_INCREMENT NOT NULL,
                    config_key VARCHAR(255) NOT NULL,
                    config_value LONGTEXT DEFAULT NULL,
                    UNIQUE INDEX UNIQ_ASYNTAI_CONFIG_KEY (config_key),
                    PRIMARY KEY(id)
                ) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB
            ');

            $io->success('Asyntai Chatbot plugin installed successfully!');
            $io->info([
                'Next steps:',
                '1. Clear cache: php bin/console cache:clear',
                '2. Install assets: php bin/console assets:install public',
                '3. Go to Admin Panel -> Asyntai AI Chatbot -> Settings',
                '4. Click "Get started" to connect your Asyntai account',
            ]);

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('Installation failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
