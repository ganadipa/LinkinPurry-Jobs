
up = docker compose up
down = docker compose down

.PHONY: up down

server:
	$(up) app-docker

down:
	$(down)

seed:
	php script/RunSeed.php

migration:
	php script/RunMigrations.php
