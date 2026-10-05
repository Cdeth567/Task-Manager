.PHONY: up down shell migrate test lint api-doc

up:
	docker compose up -d --build

down:
	docker compose down

shell:
	docker compose exec php sh

migrate:
	docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction

test:
	docker compose exec php php bin/phpunit

lint:
	docker compose exec php composer lint

api-doc:
	@echo 'Swagger UI: http://localhost:8000/api/doc'
