Docker setup for the project

Prerequisites

- Docker and Docker Compose installed on your machine.

Quick start (development, bind-mounted):

From the repo root run:

```bash
# build images and start services
docker compose -f docker/docker-compose.yml up --build
```

- Backend will be available at http://localhost:8000 (Apache serving `backend/public`).
- Frontend will be available at http://localhost:3000 (Nginx serving built files).

Run backend unit tests inside container (one-off):

```bash
docker compose -f docker/docker-compose.yml run --rm phpunit
```

Notes & caveats

- The backend Dockerfile copies the code and runs `composer install` in an intermediate stage; a bind-mounted `../backend` volume is also used for live editing during development. If you want a fully reproducible image without mounts, remove the `volumes` entry for `backend` in `docker-compose.yml`.
- The frontend build step expects a Node toolchain; we use `npm install` then `npm run build`. For fast local dev you might prefer to run `npm run dev` locally instead of inside the container.
- No database is required for this project (products are in-memory).

If you want, I can:

- Add a Makefile with common docker commands (build, up, test).
- Add healthchecks and a small wait-for-backend script for the frontend build if needed.
