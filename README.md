# Kitchen test app

App should help to understand what recipes can be cooked based on products in the fridge.

## Build

Run `composer install` to install vendor dependencies.

## Running unit tests

Run `./vendor/bin/phpunit` to execute the unit tests via [PHPUnit](https://phpunit.de/).

## Tweeting

To start tweeting API's parameters need be to configured in `./app/confing/parameters.yml`. 
After that simply run console command from root dir `php bin/console tweet-lunch`.
