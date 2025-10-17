# Projeto Carrinho de Compras (pt-BR)

Repositório de exemplo com um pequeno carrinho de compras.

Tecnologias
- Backend: PHP + Slim (sem banco, dados em memória)
- Frontend: Vue 3 + TypeScript (Vite)
- Opcional: Docker / Docker Compose

Quick start — desenvolvimento (Windows / PowerShell)

1. Backend (servidor PHP embutido)
```powershell
cd backend
php -S localhost:8000 -t public
```
Endpoints principais:
- GET /products — lista de produtos (JSON)
- POST /cart/total — recebe payload do carrinho e retorna total

2. Frontend (Vite)
```powershell
cd frontend
npm install
npm run dev
# acessa em http://localhost:3000 (ou porta configurada)
```

Docker (construir e rodar)
- Requisitos: Docker Desktop (Windows)
```powershell
# build e subir containers
docker compose up --build --force-recreate
# ver containers e portas
docker compose ps
# ver logs
docker compose logs -f backend
docker compose logs -f frontend
# parar e remover
docker compose down
```
- Backend disponível em http://localhost:8000
- Frontend disponível em http://localhost:3000

Testes (backend)
```powershell
cd backend
./vendor/bin/phpunit tests/Unit --testdox
```
