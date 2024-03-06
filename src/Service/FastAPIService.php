<?php
// src/Service/FastAPIService.php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class FastAPIService
{
    private $client;
    private $fastApiUrl;

    public function __construct(HttpClientInterface $client, string $fastApiUrl)
    {
        $this->client = $client;
        $this->fastApiUrl = $fastApiUrl;
    }

    public function predict(array $features): array
    {
        try {
            $response = $this->client->request('POST', $this->fastApiUrl, [
                'json' => ['features' => $features],
                'timeout' => 60,
            ]);
        } catch (\Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface $e) {
            // Handle timeout or other transport errors
            return ['success' => false, 'error' => 'Request to FastAPI server timed out.'];
        }
        
        
        if ($response->getStatusCode() === 200) {
            $data = $response->toArray();
            return [
                'success' => true,
                'prediction' => $data['prediction'] ?? null,
            ];
        }

        return ['success' => false];
    }
}
