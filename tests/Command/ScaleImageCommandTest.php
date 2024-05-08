<?php

namespace MarcinMadejskiRekrutacjaSmartiveapp\Tests\Command;

use MarcinMadejskiRekrutacjaSmartiveapp\Command\ScaleImageCommand;
use MarcinMadejskiRekrutacjaSmartiveapp\Services\ScaleImageService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Tester\CommandTester;

class ScaleImageCommandTest extends TestCase
{
    public function testExecuteLocalSave(): void
    {
        $imagePath = '/path/to/image.jpg';
        $destinationFolder = '/path/to/save';

        $scaleImageServiceMock = $this->createMock(ScaleImageService::class);
        $scaleImageServiceMock->expects($this->once())
            ->method('saveLocally')
            ->with($imagePath, $destinationFolder)
            ->willReturn('Image scaled and saved locally');

        $properties = [
            'image-src' => $imagePath,
            'path-or-token' => $destinationFolder
        ];

        $this->assertStringContainsString('Image scaled and saved locally', $this->getTestResult($scaleImageServiceMock, $properties));
    }

    public function testExecuteFailedLocalSave(): void
    {
        $imagePath = '/path/to/image.jpg';

        $scaleImageServiceMock = $this->createMock(ScaleImageService::class);

        $properties = ['image-src' => $imagePath];

        $commandTester = new CommandTester($this->getScaleImageCommand($scaleImageServiceMock));

        $this->expectException(RuntimeException::class);
        $commandTester->execute($properties);
    }

    public function testExecuteDropboxSave(): void
    {
        $imagePath = '/path/to/image.jpg';
        $pathOrToken = 'dropbox-access-token';

        $scaleImageServiceMock = $this->createMock(ScaleImageService::class);
        $scaleImageServiceMock->expects($this->once())
            ->method('saveInDropbox')
            ->with($imagePath, $pathOrToken)
            ->willReturn('Image scaled and saved in Dropbox');

        $properties = [
            'image-src' => $imagePath,
            'path-or-token' => $pathOrToken,
            'mode' => 'dropbox'
        ];

        $this->assertStringContainsString('Image scaled and saved in Dropbox', $this->getTestResult($scaleImageServiceMock, $properties));
    }

    public function testExecuteFailedDropboxSave(): void
    {
        $imagePath = '/path/to/image.jpg';

        $scaleImageServiceMock = $this->createMock(ScaleImageService::class);

        $properties = [
            'image-src' => $imagePath,
            'mode' => 'dropbox'
        ];

        $commandTester = new CommandTester($this->getScaleImageCommand($scaleImageServiceMock));

        $this->expectException(RuntimeException::class);
        $commandTester->execute($properties);
    }

    private function getTestResult(MockObject $mock, array $properties): string
    {
        $command = $this->getScaleImageCommand($mock);
        $commandTester = new CommandTester($command);
        $commandTester->execute($properties);

        return $commandTester->getDisplay();
    }

    private function getScaleImageCommand(MockObject $mock): Command
    {
        $application = new Application();
        $application->add(new ScaleImageCommand($mock));
        return $application->find('scale:image');
    }
}