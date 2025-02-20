# php-mvc

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
