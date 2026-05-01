# Laravel Docker Infrastructure (Dev + Prod)

## Project structure

```text
.
├── .env                  # Infrastructure variables for Docker Compose
├── .env.example
├── docker-compose.yml
├── docker-compose.prod.yaml
├── Dockerfile            # Multi-stage PHP image (dev/prod)
├── nginx.conf
├── Makefile
├── docker/
│   ├── nginx/
│   │   └── Dockerfile
│   └── php/
│       ├── php.ini       # dev php config (opcache.validate_timestamps=1)
│       ├── php-prod.ini  # prod php config (opcache.validate_timestamps=0)
│       ├── xdebug.ini
│       └── php-fpm.conf
└── src/                  # Laravel application
    ├── .env              # Application variables for Laravel
    └── ...
```

## Environment separation

- Root `.env` is only for infrastructure and Docker Compose values (ports, image tags, DB container credentials).
- `src/.env` is only for Laravel application values.

Create env files from templates:

```bash
make init
```

## Dev run

1. Build and start containers:

```bash
make up
```

2. Install Composer dependencies in dev mode:

```bash
make composer-install
```

3. Run migrations:

```bash
make migrate
```

4. Open app in browser:

- URL: `http://localhost:${NGINX_HOST_PORT}`
- Default from `.env`: `http://localhost:8080`

## Prod-like run

Use compose override with production Docker target and without source bind mounts:

```bash
make up-prod
```

Stop prod-like stack:

```bash
make down-prod
```

## Useful commands

- `make build` / `make build-prod`
- `make logs` / `make logs-prod`
- `make ps` / `make ps-prod`
- `make test`
- `make validate` (checks compose configs)

## Troubleshooting

- Port conflict:
  - change `NGINX_HOST_PORT` and/or `POSTGRES_HOST_PORT` in root `.env`.
- Permission denied on bind mounts:
  - ensure Docker Desktop has access to your project directory.
- App not reachable:
  - check `make ps`, then inspect logs via `make logs`.
