<?php

declare(strict_types=1);

namespace AppTest\Client;

use App\Client\TheMovieDatabaseApiClient;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class TheMovieDatabaseApiClientTest extends TestCase
{
    private const API_TOKEN = 'example-token';
    private const MOVIE_ID = 867351;

    private Client $httpClientMock;
    private TheMovieDatabaseApiClient $underTest;

    protected function setUp(): void
    {
        parent::setUp();
        $this->httpClientMock = $this->createMock(Client::class);
        $this->underTest = new TheMovieDatabaseApiClient($this->httpClientMock, self::API_TOKEN);
    }

    /**
     * @test
     */
    public function fetchTopRatedMovies_NetworkIssue_ThrowsException()
    {
        $this->httpClientMock
            ->expects($this->once())
            ->method('request')
            ->willThrowException(new TransferException('Something went wrong'));

        $this->expectException(GuzzleException::class);

        $this->underTest->fetchTopRatedMovies(1);
    }

    /**
     * @test
     */
    public function fetchTopRatedMovies_Perfect_CallsHttpClientWithProperParameters()
    {
        $token = 'example-token';
        $pageNumber = 10;

        $this->httpClientMock
            ->expects($this->once())
            ->method('request')
            ->with('GET', "https://api.themoviedb.org/3/movie/top_rated?page={$pageNumber}&api_key={$token}")
            ->willReturn(new Response(200, [], json_encode(['results' => []])));

        $this->underTest->fetchTopRatedMovies($pageNumber);
    }

    /**
     * @test
     */
    public function fetchTopRatedMovies_Perfect_Perfect()
    {
        $pageNumber = 10;
        $expectedResult = [
            [
                'title' => "Gabriel's Inferno",
                'release_data' => '2020-05-29',
            ],
            [
                'title' => 'The Godfather',
                'release_date' => '1972-03-14',
            ],
        ];

        $responseBody = json_encode([
            'page' => $pageNumber,
            'results' => $expectedResult,
            'total_pages' => 888,
            'total_results' => 17760
        ]);
        $response = new Response(200, [], $responseBody);

        $this->httpClientMock
            ->expects($this->once())
            ->method('request')
            ->willReturn($response);

        $result = $this->underTest->fetchTopRatedMovies($pageNumber);

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @test
     */
    public function fetchMovieDetails_NetworkIssue_ThrowsException()
    {
        $this->httpClientMock
            ->expects($this->once())
            ->method('request')
            ->willThrowException(new TransferException('Something went wrong'));

        $this->expectException(GuzzleException::class);

        $this->underTest->fetchMovieDetails(self::MOVIE_ID);
    }

    /**
     * @test
     */
    public function fetchMovieDetails_Perfect_CallsHttpClientWithProperParameters()
    {
        $token = 'example-token';
        $movieId = self::MOVIE_ID;

        $this->httpClientMock
            ->expects($this->once())
            ->method('request')
            ->with('GET', "https://api.themoviedb.org/3/movie/{$movieId}?api_key={$token}")
            ->willReturn(new Response(200, [], json_encode([])));

        $this->underTest->fetchMovieDetails(self::MOVIE_ID);
    }

    /**
     * @test
     */
    public function fetchMovieDetails_Perfect_Perfect()
    {
        $expectedResult = [
            'title' => "Fight Club",
            'release_data' => '1999-10-12',
            'runtime' => 139,
        ];

        $responseBody = json_encode($expectedResult);
        $response = new Response(200, [], $responseBody);

        $this->httpClientMock
            ->expects($this->once())
            ->method('request')
            ->willReturn($response);

        $result = $this->underTest->fetchMovieDetails(self::MOVIE_ID);

        $this->assertEquals($expectedResult, $result);
    }
}
