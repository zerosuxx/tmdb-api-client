# The Movie Database Api Client

## Usage

```php
use GuzzleHttp\Client;
use App\Client\TheMovieDatabaseApiClient;

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
