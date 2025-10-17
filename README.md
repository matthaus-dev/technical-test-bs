# Projeto Carrinho de Compras

This repository contains a small shopping-cart example with:

- Backend: PHP (Slim), processes cart payloads and applies payment rules.
- Frontend: Vue 3 + TypeScript (Vite) — simple product list and cart UI.

## Quick start (local development)

1. Start the backend (PHP built-in server)

```bash
cd backend
php -S localhost:8000 -t public
```

The backend exposes:

- GET /products — returns an array of products in JSON with keys: `name`, `price`.
- POST /cart/total — accepts JSON payload:

  {
  "products": [{"name": "Fone Bluetooth", "price": 100.0, "quantity": 1}, ...],
  "payment_method": "PIX" | "CARTAO_CREDITO",
  "installments": 1
  }

  and returns JSON with this shape:

  {
  "total_amount": 90.0,
  "principal": 100.0,
  "payment_method": "PIX",
  "installments": 1,
  "details": { ... }
  }

2. Start the frontend (Vite)

```bash
cd frontend
npm install
npm run dev
```

## Running tests (backend)

Inside `backend/` run:

```bash
./vendor/bin/phpunit tests/Unit --testdox
```

## Docker (optional)

A simple `docker/` setup is included. To build and run containers (requires Docker and Docker Compose):

```bash
docker compose -f docker/docker-compose.yml up --build
```

- Backend will be available at http://localhost:8000
- Frontend will be available at http://localhost:3000

Run backend unit tests inside the phpunit container:

```bash
docker compose -f docker/docker-compose.yml run --rm phpunit
```
