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
        return $this->sendRequest("/movie/top_rated", ['page' => $pageNumber])['results'];
    }

    /**
     * @throws GuzzleException
     */
    public function fetchMovieDetails(int $movieId): array
    {
        return $this->sendRequest("/movie/{$movieId}");
    }

    /**
     * @throws GuzzleException
     */
    public function fetchMovieCredits(int $movieId): array
    {
        return $this->sendRequest("/movie/{$movieId}/credits");
    }

    /**
     * @throws GuzzleException
     */
    private function sendRequest(string $path, array $queryParameters = []): array
    {
        $query = http_build_query(array_merge($queryParameters, ['api_key' => $this->apiToken]));
        $url = self::API_BASE_URL . "{$path}?{$query}";
        $response = $this->httpClient->request('GET', $url);

        return $this->transformResponseBody($response);
    }

    private function transformResponseBody(ResponseInterface $response): array
    {
        $contents = $response->getBody()->getContents();

        return json_decode($contents, true);
    }
}
