## Installation

- $ git clone https://github.com/eugef/funda-api-example.git /path
- $ php composer.phar install
 - Specify DB access credentials 
 - Valid Funda API key is required
- $ php app/console doctrine:database:create
- $ php app/console doctrine:schema:update --force

## Usage

- Open http://path/to/app/report

## Unit tests

- $ phpunit -c app/
