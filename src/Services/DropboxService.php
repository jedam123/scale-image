<?php

namespace MarcinMadejskiRekrutacjaSmartiveapp\Services;

use JetBrains\PhpStorm\ArrayShape;
use MarcinMadejskiRekrutacjaSmartiveapp\Exceptions\ScaleImageServiceException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class DropboxService
{
    private static string $DROPBOX_UPLOAD_API_URL = 'https://content.dropboxapi.com/2/files/upload';

    private HttpClientInterface $client;

    public function __construct()
    {
        $this->client = HttpClient::create();
    }

    public function uploadFile(string $accessToken, string $sourcePath, string $thumbnailContent): string
    {
        try {
            $response = $this->post(self::$DROPBOX_UPLOAD_API_URL, $this->getHeaderForDropbox($accessToken, $sourcePath), $thumbnailContent);
            $content = $response->getContent();

            if ($response->getStatusCode() === 200) {
                $data = json_decode($content, true);
                $path = $data['path_display'] ?? '';

                return empty($path)
                    ? 'The thumbnail has been generated'
                    : "The thumbnail has been generated in " . $path;
            }

            return $content;
        } catch (TransportExceptionInterface|ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface $e) {
            throw new ScaleImageServiceException($e->getMessage());
        }
    }

    #[ArrayShape(['Authorization' => "string", 'Dropbox-API-Arg' => "false|string", 'Content-Type' => "string"])]
    private function getHeaderForDropbox(string $accessToken, string $sourcePath): array
    {
        return [
            'Authorization' => 'Bearer ' . $accessToken,
            'Dropbox-API-Arg' => json_encode($this->getUploadArgument($sourcePath)),
            'Content-Type' => 'application/octet-stream',
        ];
    }

    #[ArrayShape(['autorename' => "false", 'mode' => "string", 'mute' => "false", 'path' => "string", 'strict_conflict' => "bool"])]
    private function getUploadArgument(string $sourcePath): array
    {
        return [
            'autorename' => false,
            'mode' => 'add',
            'mute' => false,
            'path' => '/Homework/' . basename($sourcePath),
            'strict_conflict' => true
        ];
    }

    private function post(string $apiUrl, array $headers, string $body): ResponseInterface
    {
        try {
            return $this->client->request('POST', $apiUrl, [
                'headers' => $headers,
                'body' => $body,
            ]);
        } catch (TransportExceptionInterface $e) {
            throw new ScaleImageServiceException($e->getMessage());
        }
    }
}