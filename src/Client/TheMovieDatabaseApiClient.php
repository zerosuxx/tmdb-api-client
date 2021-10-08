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

        return $this->getResult($response)['results'];
    }

    /**
     * @throws GuzzleException
     */
    public function fetchMovieDetails(int $movieId): array
    {
        $url = self::API_BASE_URL . "/movie/{$movieId}?api_key={$this->apiToken}";
        $response = $this->httpClient->request('GET', $url);

        return $this->getResult($response);
    }

    /**
     * @throws GuzzleException
     */
    public function fetchMovieCredits(int $movieId): array
    {
        $url = self::API_BASE_URL . "/movie/{$movieId}/credits?api_key={$this->apiToken}";
        $response = $this->httpClient->request('GET', $url);

        return $this->getResult($response);
    }

    private function getResult(ResponseInterface $response): array
    {
        $contents = $response->getBody()->getContents();

        return json_decode($contents, true);
    }
}
