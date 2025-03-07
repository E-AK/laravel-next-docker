COMPOSE_FILE?=docker-compose.yml

up:
	docker compose -f $(COMPOSE_FILE) up -d

up-build:
	docker compose -f $(COMPOSE_FILE) up -d --build

stop:
	docker compose -f $(COMPOSE_FILE) down

clean:
	docker compose -f $(COMPOSE_FILE) down -v
