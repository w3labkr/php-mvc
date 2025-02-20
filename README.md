# php-mvc

php simple mvc without framework

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

## License

[MIT LICENSE](LICENSE)
