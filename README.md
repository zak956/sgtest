## StadiumGoods Test

Requires PHP 7.3

Install: `composer install`

Help: `php sg_test.php scrape --help` to get description and arguments

Run: `php sg_test.php scrape https://www.stadiumgoods.com/adidas 10 3 --products`

Run tests:`vendor/bin/phpunit -c phpunit.xml --coverage-text`

Script response goes to stdout, can be redirected to file via adding ` > filename.json`

Parameters: `php sg_test.php scrape [url] [number of items to get] [max async requests] [--products optional parameter to get products instead of pages]`

Code coverage can be seen after running tests in `log/index.php`