# Начало

Создаем .env и задаем переменные среды как в [.env.example](./.env.example)

В сервисах notification_service, task_service, user_service тоже создаем .env как .env.example

# Запуск

Есть Makefile. Для запуска со сборкой образа: 
```shell
make up-build
```

Запустить без сборки:
```shell
make up
```

Остановить:
```shell
make stop
```

Остановить с очисткой volume:
```shell
make clean
```