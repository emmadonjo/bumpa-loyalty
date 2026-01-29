#!/usr/bin/env bash

set -e

# -----------------------------
# Colors
# -----------------------------
GREEN="\033[0;32m"
YELLOW="\033[1;33m"
RED="\033[0;31m"
BLUE="\033[0;34m"
NC="\033[0m" # No Color

info()    { echo -e "${BLUE}ℹ️  $1${NC}"; }
success() { echo -e "${GREEN}✅ $1${NC}"; }
warn()    { echo -e "${YELLOW}⚠️  $1${NC}"; }
error()   { echo -e "${RED}❌ $1${NC}"; }

# -----------------------------
# Detect docker-compose command
# -----------------------------
if command -v docker-compose &> /dev/null; then
  COMPOSE_CMD="docker-compose"
elif docker compose version &> /dev/null; then
  COMPOSE_CMD="docker compose"
else
  error "Docker Compose is not installed."
  exit 1
fi

info "Using compose command: $COMPOSE_CMD"

# -----------------------------
# Check Docker is running
# -----------------------------
if ! docker info &> /dev/null; then
  error "Docker is not running. Please start Docker."
  exit 1
fi
success "Docker is running"

# -----------------------------
# Backend env
# -----------------------------
if [ ! -f backend/.env ]; then
  info "Creating backend/.env"
  cp backend/.env.example backend/.env
else
  success "backend/.env exists"
fi

# -----------------------------
# Frontend env
# -----------------------------
if [ ! -f frontend/.env.local ]; then
  info "Creating frontend/.env"
  cp frontend/.env.example frontend/.env.local
else
  success "frontend/.env exists"
fi

# -----------------------------
# Start containers
# -----------------------------
info "Building and starting Docker containers..."
$COMPOSE_CMD down -v
$COMPOSE_CMD up --build -d
success "Containers are up"

# -----------------------------
# Wait for DB
# -----------------------------
info "Waiting for database to be ready…"
until docker exec loyalty-db mysqladmin ping -h "localhost" -uroot -p"${DB_PASSWORD}" --silent; do
  sleep 2
  echo -n "."
done
echo ""
success "Database is ready"

# -----------------------------
# Wait for backend
# -----------------------------
info "Waiting for backend to be ready…"
until docker exec loyalty-backend php artisan --version &> /dev/null; do
  sleep 2
  echo -n "."
done
echo ""
success "Backend container is responsive"

# -----------------------------
# Install composer dependencies inside container
# -----------------------------
info "Installing PHP dependencies with Composer…"
docker exec loyalty-backend composer install --optimize-autoloader
success "Composer dependencies installed"

# -----------------------------
# Laravel setup
# -----------------------------
info "Generating Laravel APP_KEY..."
docker exec loyalty-backend php artisan key:generate --force
success "APP_KEY generated"

info "Running migrations and seeders..."
docker exec loyalty-backend php artisan migrate --seed --force
success "Database migrated and seeded"

# -----------------------------
# Final message
# -----------------------------
echo ""
success "🎉 Installation complete!"
echo "---------------------------------------"
echo "👉 Frontend: http://localhost:3000"
echo "👉 Backend:  http://localhost:8000"
echo "---------------------------------------"