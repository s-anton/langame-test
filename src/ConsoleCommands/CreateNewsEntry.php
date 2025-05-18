<?php

declare(strict_types=1);

namespace App\ConsoleCommands;

use App\Dto\HabrItemDto;
use App\Repository\NewsRepository;
use App\UseCases\BulkCreateNewsEntriesUseCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-news-entry',
    description: 'Creates fake news',
)]
class CreateNewsEntry extends Command
{
    public function __construct(
        private NewsRepository $newsRepository,
        private BulkCreateNewsEntriesUseCase $bulkCreateNewsEntriesUseCase
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $lastEntry = $this->newsRepository->getLastRecord();
        $publishedAt = $lastEntry?->getPublishedAt() ?? '1970-01-01 00:00:00';

        $hash = md5($publishedAt);
        $this->bulkCreateNewsEntriesUseCase->execute(
            [new HabrItemDto(
                $hash,
                'https://ya.ru?md5=' . $hash,
                date_create($publishedAt)->modify('+10 seconds')->format(DATE_ATOM)
            )],
            $publishedAt
        );

        return Command::SUCCESS;
    }
}