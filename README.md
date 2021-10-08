# The Movie Database Api Client

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
$tmdbClient->fetchTopRatedMovies($pageNumber); //[['title' => 'Movie #1', ...], ['title' => 'Movie #2', ...]]
```

## Run tests
```shell
$ composer test
```
