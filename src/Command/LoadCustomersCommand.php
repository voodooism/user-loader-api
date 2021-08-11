<?php

namespace App\Command;

use App\UserImporter\UserImporterInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;
use UnexpectedValueException;

class LoadCustomersCommand extends Command
{
    protected static $defaultName = 'app:load-customers';

    protected static $defaultDescription = 'This command loads customers from a data provider';

    private UserImporterInterface $userImporter;

    private LoggerInterface $logger;

    public function __construct(
        UserImporterInterface $userImporter,
        LoggerInterface $logger
    ) {
        $this->userImporter = $userImporter;
        $this->logger = $logger;

        parent::__construct();
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        parent::interact($input, $output);

        $count = $input->getArgument('count');

        if (!is_numeric($count) || (int)$count < 1) {
            throw new UnexpectedValueException('Count argument should be a positive integer');
        }
    }

    protected function configure(): void
    {
        $this->addArgument('count', InputArgument::REQUIRED, 'Count of customers to load');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $count = (int)$input->getArgument('count');

        try {
            $this->userImporter->importBatch($count);
            $io->success(
                sprintf('%d customers successfully imported.', $count)
            );
            $returnCode = self::SUCCESS;
        } catch (Throwable $t) {
            $this->logger->critical(
                sprintf(
                    'Customers loading is failed. Error message: %s',
                    $t->getMessage()
                )
            );
            $io->error('Customers loading is failed!');
            $returnCode = self::FAILURE;
        }

        return $returnCode;
    }
}
