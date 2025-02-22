# php-mvc

php simple mvc without framework

## Dependencies

- altorouter
- phpdotenv

## Folder and file Structure

```txt
.
├── config/
│   └── database.php
├── controllers/
│   └── HomeController.php
├── core/
│   ├── Controller.php
│   ├── Model.php
│   ├── Router.php
│   └── View.php
├── models/
│   └── HomeModel.php
├── views/
│   └── home.php
└── index.php
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

## License

[MIT LICENSE](LICENSE)
