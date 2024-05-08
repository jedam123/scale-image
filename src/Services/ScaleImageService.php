<?php

namespace MarcinMadejskiRekrutacjaSmartiveapp\Services;

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use MarcinMadejskiRekrutacjaSmartiveapp\Exceptions\ScaleImageServiceException;

class ScaleImageService
{
    private static int $LONGER_SIDE = 150;

    private ImageManager $imageManager;
    private DropboxService $dropboxService;

    public function __construct(DropboxService $dropboxService)
    {
        $this->imageManager = new ImageManager(new Driver());
        $this->dropboxService = $dropboxService;
    }

    public function saveLocally(string $inputPath, string $destinationFolder): string
    {
        return "";
    }

    public function saveInDropbox(string $sourcePath, string $accessToken): string
    {
        return "";
    }
}