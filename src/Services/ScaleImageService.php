<?php

namespace MarcinMadejskiRekrutacjaSmartiveapp\Services;

use Intervention\Gif\Exceptions\NotReadableException;
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
        try {
            $thumbnailPath = $destinationFolder . '/' . basename($inputPath);
            $this->createThumbnail($inputPath, $thumbnailPath);

            return 'The thumbnail has been generated.';
        } catch (NotReadableException $e) {
            throw new ScaleImageServiceException($e->getMessage());
        }
    }

    public function saveInDropbox(string $sourcePath, string $accessToken): string
    {
        $thumbnailPath = tempnam(sys_get_temp_dir(), 'thumbnail');
        $this->createThumbnail($sourcePath, $thumbnailPath);
        $thumbnailContent = file_get_contents($thumbnailPath);
        unlink($thumbnailPath);

        return "";
    }

    private function createThumbnail(string $inputPath, string $destinationPath): void
    {
        try {
            $image = $this->imageManager->read($inputPath);

            [$scaledWidth, $scaledWHeight] = $this->getScaledImageDimensions($image->width(), $image->height());

            $image->resize($scaledWidth, $scaledWHeight);
            $image->save($destinationPath);
        } catch (NotReadableException $e) {
            throw new ScaleImageServiceException($e->getMessage());
        }
    }

    private function getScaledImageDimensions(int $width, int $height): array
    {
        if ($width >= $height) {
            return [self::$LONGER_SIDE, round(($height / $width) * self::$LONGER_SIDE)];
        } else {
            return [round(($width / $height) * self::$LONGER_SIDE), self::$LONGER_SIDE];
        }
    }
}