<?php

namespace MarcinMadejskiRekrutacjaSmartiveapp\Tests\Services;

use MarcinMadejskiRekrutacjaSmartiveapp\Services\DropboxService;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class DropboxServiceTest extends TestCase
{
    public function testUploadFileSuccess(): void
    {
        $accessToken = 'access_token';
        $sourcePath = '/path/to/test.jpg';
        $thumbnailContent = 'thumbnail_content';

        $dropboxService = new DropboxService();

        $reflection = new \ReflectionClass($dropboxService);
        $clientProperty = $reflection->getProperty('client');
        $clientProperty->setAccessible(true);
        $clientProperty->setValue($dropboxService, $this->createMockHttpClient(200));

        $result = $dropboxService->uploadFile($accessToken, $sourcePath, $thumbnailContent);

        $this->assertEquals('The thumbnail has been generated in /path/to/dropbox/file.jpg', $result);
    }

    public function testUploadFileFailed(): void
    {
        $accessToken = 'access_token';
        $sourcePath = '/path/to/test.jpg';
        $thumbnailContent = 'thumbnail_content';

        $dropboxService = new DropboxService();

        $reflection = new \ReflectionClass($dropboxService);
        $clientProperty = $reflection->getProperty('client');
        $clientProperty->setAccessible(true);
        $clientProperty->setValue($dropboxService, $this->createMockHttpClient(402));

        $result = $dropboxService->uploadFile($accessToken, $sourcePath, $thumbnailContent);

        $this->assertNotEquals('The thumbnail has been generated in /path/to/dropbox/file.jpg', $result);
    }

    private function createMockHttpClient(int $statusCode): object
    {
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getStatusCode')->willReturn($statusCode);
        $responseMock->method('getContent')->willReturn(json_encode(['path_display' => '/path/to/dropbox/file.jpg']));

        $httpClientMock = $this->getMockBuilder(HttpClientInterface::class)
            ->onlyMethods(['request', 'stream', 'withOptions'])
            ->getMock();
        $httpClientMock->method('request')->willReturn($responseMock);

        return $httpClientMock;
    }
}