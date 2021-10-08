<?php

declare(strict_types=1);

namespace App\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class TheMovieDatabaseApiClient
{
    private const API_BASE_URL = 'https://api.themoviedb.org/3';

    private Client $httpClient;
    private string $apiToken;

    public function __construct(Client $httpClient, string $apiToken)
    {
        $this->httpClient = $httpClient;
        $this->apiToken = $apiToken;
    }

    /**
     * @throws GuzzleException
     */
    public function fetchTopRatedMovies(int $pageNumber): array
    {
        $url = self::API_BASE_URL . "/movie/top_rated?page={$pageNumber}&api_key={$this->apiToken}";
        $response = $this->httpClient->request('GET', $url);

        return $this->transformResponse($response);
    }

    private function transformResponse(ResponseInterface $response): array
    {
        $contents = $response->getBody()->getContents();
        $result = json_decode($contents, true);

        return $result['results'];
    }
}
