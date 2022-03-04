<div align="left" style="margin-left: -1em"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="150"></a></div>

# Pet Shop eCommerce API


## About Pet shop eCommerce API
This Laravel-based API backend was developed for the purpose of a test conducted by [Buckhill](https://www.buckhill.co.uk/) for the position of Laravel PHP developer.

## Configuration
The following needs to be specified in the .env before properly running the API server (_please refer `.env.example` in the root of the project_):

* `TIMEZONE` - Can be set to a proper timezone value (e.g. `Asia/Kabul`)
* `SESSION_MAX_AGE` - This needs to be set to specify the defualt _age_ of a generated token for a user. It can be set to any value acceptable by `DateTimeImmutable` (`modify` method) such as `+1 day`, `+6 hour`, etc.

After the values on the `.env` file have been updated, please run `php artisan config:cache` to update the config cache values for the server.

## Serve
`php artisan serve --port 8001`


## Swagger API documentation
To run Swagger documentation server for this application you need to install `npx` and `swagger-ui-serve` as `npm` dependencies:

* ### NPX
      npm i -g npx

* ### Swagger UI Serve package
      npm i swagger-ui-serve

To run the Swagger API server run 
`npm run swagger`
from the project root directory.
You should be guided to a tab opened on your browser on port `3000`.


## Testing
To execute the tests included with this API please run `php artisan test`

or

`./vendor/bin/phpunit` on the terminal.
