<?php

namespace App\Command;

use App\Services\PaymentStatusUpdateService\UpdateStatusService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:payments:update-status',
    description: 'refresh payments statuses',
)]
class PaymentsUpdateStatusCommand extends Command
{
    public function __construct(
        private readonly UpdateStatusService $updateStatusService,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('refresh', null, InputOption::VALUE_NONE, 'Refresh payment statuses');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($input->getOption('refresh')) {
            $this->updateStatusService->refresh();
        }

        return Command::SUCCESS;
    }
}
