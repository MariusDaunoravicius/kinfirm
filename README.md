# Setup

## Laravel valet
* Run `valet park`
* Open http://kinfirm.test it should display laravel 404 page

## Project
* Docker must be running
* Run cp env.example .env
* Run `./vendor/bin/sail up`
* Run `./vendor/bin/sail php artisan migrate`
* To import products run `./vendor/bin/sail php artisan app:import-products`
* To import stocks run `./vendor/bin/sail php artisan app:import-stocks`
* Use `Kinfin API.postman_collection.json` to make requests to the API
## Testing
* To run tests run `./vendor/bin/sail composer test`
* To run pest run `./vendor/bin/sail composer pest`
* To run phpstan run `./vendor/bin/sail composer stan`
