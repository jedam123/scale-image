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
    private static string $IMAGE_SRC_PROP = 'image-src';
    private static string $PATH_OR_TOKEN_PROP = 'path-or-token';
    private static string $MODE_PROP = 'mode';

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
            ->addArgument(self::$IMAGE_SRC_PROP, InputArgument::REQUIRED, 'Path to the input image')
            ->addArgument(self::$PATH_OR_TOKEN_PROP, InputArgument::REQUIRED, 'Describe path or access token')
            ->addArgument(self::$MODE_PROP, InputArgument::OPTIONAL, 'Select save mode (local or dropbox)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $imagePath = $input->getArgument(self::$IMAGE_SRC_PROP);
            $pathOrToken = $input->getArgument(self::$PATH_OR_TOKEN_PROP);
            $option = $input->getArgument(self::$MODE_PROP);

            $statusMessage = match ($option) {
                'dropbox' => $this->scaleImageService->saveInDropbox($imagePath, $pathOrToken),
                default => $this->scaleImageService->saveLocally($imagePath, $pathOrToken),
            };
            $output->writeln($statusMessage);

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('An error occurred: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

}