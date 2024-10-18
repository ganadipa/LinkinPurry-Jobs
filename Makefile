
up = docker compose up
down = docker compose down

.PHONY: up down

server:
	$(up) app-docker

down:
	$(down)