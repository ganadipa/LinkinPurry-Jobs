
up = docker compose up
down = docker compose down

.PHONY: up down

server:
	$(up) app-local

down:
	$(down)