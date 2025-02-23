# php-mvc

php simple mvc without framework

## Dependencies

- altorouter/altorouter
- vlucas/phpdotenv
- monolog/monolog
- peppeocchi/php-cron-scheduler

## Directory Structure

```txt
.
`-- html/
    |-- app/
    |   |-- Controllers/
    |   |-- Core/
    |   |-- Helpers/
    |   |-- Middlewares/
    |   |-- Models/
    |   `-- Views/
    |-- config/
    |-- database/
    |-- public/
    |   `-- assets/
    |-- scheduler/
    |   `-- tasks/
    |-- vendor/
    |-- .env
    `-- composer.json
```

## Installation

Build and Run the Containers

```shell
git clone https://github.com/w3labkr/docker-lamp-stack.git docker
cd docker
cp .env.example .env
sed -i '' 's/DOCUMENT_ROOT=.*/DOCUMENT_ROOT=..\/www/' .env
mv www ../www
docker compose up -d
```

Edit `docker/apache2/sites/default.conf`:

```txt
<VirtualHost *:80>
    ...
    DocumentRoot /var/www/html/public

    <Directory /var/www/html/public>
        ...
    </Directory>
</VirtualHost>
```

PHP Cron Job Scheduler

```shell
$ cp docker-entrypoint.sh bin/<PHP_VERSION>/docker-entrypoint.sh
$ docker compose up -d --build
$ docker compose exec apache2 bash
/var/www/html# composer require peppeocchi/php-cron-scheduler
/var/www/html# exit
```

## Usage

Log Level

- DEBUG (100): Detailed debug information
- INFO (200): Interesting events
- NOTICE (250): Uncommon events
- WARNING (300): Exceptional occurrences that are not errors
- ERROR (400): Runtime errors
- CRITICAL (500): Critical conditions
- ALERT (550): Action must be taken immediately
- EMERGENCY (600): Urgent alert.

## Commands

Composer is a tool for dependency management in PHP.

- `--no-interaction (-n)`: Do not ask any interactive question.

```shell
$ docker compose exec apache2 bash
/var/www/html# composer init
/var/www/html# composer install --no-interaction
```

Update the autoloader

```shell
composer dump-autoload
```

A lightning fast router for PHP

```shell
composer require altorouter/altorouter
```

Loads environment variables from `.env` to `getenv()`, `$_ENV` and `$_SERVER` automagically.

```shell
composer require vlucas/phpdotenv
```

Sends your logs to files, sockets, inboxes, databases and various web services

```shell
composer require monolog/monolog
```

PHPMailer is a full-featured email creation and transfer class for PHP

```shell
composer require phpmailer/phpmailer
```

## License

[MIT LICENSE](LICENSE)
