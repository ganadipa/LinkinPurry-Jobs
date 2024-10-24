
compose = docker compose
up = $(compose) up
down = $(compose) down

.PHONY: up down

server:
	$(up) app-docker-php

down:
	$(down)

ps:
	$(compose) ps

seed:
	php php/script/RunSeed.php

migration:
	php php/script/RunMigrations.php
