# The Movie Database Api Client

[![CI](https://github.com/zerosuxx/tmdb-api-client/actions/workflows/ci.yml/badge.svg)](https://github.com/zerosuxx/tmdb-api-client/actions/workflows/ci.yml)

## Install package
```shell
$ composer require zerosuxx/tmdb-api-client
```

## Usage

```php
use GuzzleHttp\Client;
use App\Client\TheMovieDatabaseApiClient;

require __DIR__ . '/vendor/autoload.php';

$httpClient = new Client();
$apiToken = 'abc...';
$tmdbClient = new TheMovieDatabaseApiClient($httpClient, $apiToken);

$pageNumber = 10;
$movies = $tmdbClient->fetchTopRatedMovies($pageNumber);
$movieDetails = $tmdbClient->fetchMovieDetails($movies[0]['id']);
```

## Run tests
```shell
$ composer test
```
