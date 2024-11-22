# Начало

Создаем .env и задаем переменные среды как в [.env.example](./.env.example)

В папке [api](./api) создаем .env как в [api](./api/.env.example).

## Dev

```bash
docker compose -f docker-compose.dev.yml up -d --build
```

Если это 1 запуск, то заходим в контейнер api:

```bash
docker compose exec -it api bash
```

Создаем бд:

```bash
php bin/console doctrine:database:create
```

Запускаем миграцию:

```bash
php bin/console doctrine:migrations:migrate
```

Создае пару ключей для JWT:
```bash
php bin/console lexik:jwt:generate-keypair
```

## Prod

```bash
docker compose up -d --build
```