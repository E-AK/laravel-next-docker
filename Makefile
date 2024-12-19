COMPOSE_FILE?=docker-compose.yml

default:
	docker compose -f $(COMPOSE_FILE) up -d

dev:
	docker compose -f docker-compose.dev.yml up -d

dev-build:
	docker compose -f docker-compose.dev.yml up -d --build

stop:
	docker compose -f $(COMPOSE_FILE) down

dev-stop:
	docker compose -f docker-compose.dev.yml down

clean:
	docker compose -f $(COMPOSE_FILE) down -v
	docker compose -f docker-compose.dev.yml down -v
