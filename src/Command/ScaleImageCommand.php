<?php

namespace MarcinMadejskiRekrutacjaSmartiveapp\Command;

use MarcinMadejskiRekrutacjaSmartiveapp\Exceptions\ScaleImageServiceException;
use MarcinMadejskiRekrutacjaSmartiveapp\Services\ScaleImageService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'scale:image',
    description: 'Command to create and save thumbnail',
    hidden: false
)]
class ScaleImageCommand extends Command
{

    private ScaleImageService $scaleImageService;

    public function __construct(ScaleImageService $scaleImageService)
    {
        parent::__construct();
        $this->scaleImageService = $scaleImageService;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Scale an image')
            ->addArgument('image-src', InputArgument::REQUIRED, 'Path to the input image')
            ->addArgument('path-or-token', InputArgument::REQUIRED, 'Describe path or access token ')
            ->addArgument('mode', InputArgument::OPTIONAL, 'Select save mode (local or dropbox)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('An error occurred: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

}