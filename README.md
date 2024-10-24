Bikin branch masing-masing yak

Run Scripts:
Note, before run make sure you have pdo_pgsql, check by doing
```bash
php -m | grep pdo_pgsql
```
Also, you must have .env.local by doing
```bash
cp php/.env.local.template php/.env.local
```
Then fill in the blanks inside the php/.env.local


1. Migration
```bash
make migrations
```

Run Application:
Must install  docker

The .env.docker must be ready
```bash
  cp php/.env.docker.template php/.env.docker
```
then fill in the blanks inside the php/.env.docker

Start the php application by doing:
```bash
make server
```

