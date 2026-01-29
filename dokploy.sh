#!/usr/bin/env bash

set -Eeuo pipefail
IFS=$'\n\t'

# -----------------------------
# Trap
# -----------------------------
trap 'echo -e "\033[0;31m❌ Setup failed. Fix the error and re-run.\033[0m"' ERR

# -----------------------------
# Colors & helpers
# -----------------------------
GREEN="\033[0;32m"
YELLOW="\033[1;33m"
RED="\033[0;31m"
BLUE="\033[0;34m"
NC="\033[0m"

info()    { echo -e "${BLUE}ℹ️  $1${NC}"; }
success() { echo -e "${GREEN}✅ $1${NC}"; }
warn()    { echo -e "${YELLOW}⚠️  $1${NC}"; }
error()   { echo -e "${RED}❌ $1${NC}"; }

# -----------------------------
# Port check
# -----------------------------
check_port() {
  local port=$1
  if command -v ss &>/dev/null; then
    ! ss -lnt | awk '{print $4}' | grep -q ":$port$"
  else
    ! lsof -iTCP:"$port" -sTCP:LISTEN &>/dev/null
  fi
}

find_available_port() {
  local port=$1
  while [ "$port" -lt 65535 ]; do
    if check_port "$port"; then
      echo "$port"
      return
    fi
    port=$((port + 1))
  done
  error "No available ports found"
  exit 1
}

# -----------------------------
# Detect docker compose
# -----------------------------

COMPOSE_CMD="compose"

info "Using compose command: $COMPOSE_CMD"

# -----------------------------
# Docker running?
# -----------------------------
docker info &>/dev/null || {
  error "Docker is not running"
  exit 1
}
success "Docker is running"

# -----------------------------
# Project .env and port resolution
# -----------------------------
if [ ! -f .env ]; then
  info "Creating .env"
  cp .env.example .env
fi

# Safe env load
set -a
source .env
set +a

DEFAULT_BACKEND_PORT=${BACKEND_PORT:-8000}
DEFAULT_FRONTEND_PORT=${FRONTEND_PORT:-3000}
DB_PORT=${DB_PORT:-3306}

if ! check_port "$DEFAULT_BACKEND_PORT"; then
  warn "Backend port $DEFAULT_BACKEND_PORT in use"
  BACKEND_PORT=$(find_available_port $((DEFAULT_BACKEND_PORT + 1)))
else
  BACKEND_PORT=$DEFAULT_BACKEND_PORT
fi

if ! check_port "$DEFAULT_FRONTEND_PORT"; then
  warn "Frontend port $DEFAULT_FRONTEND_PORT in use"
  FRONTEND_PORT=$(find_available_port $((DEFAULT_FRONTEND_PORT + 1)))
else
  FRONTEND_PORT=$DEFAULT_FRONTEND_PORT
fi

sed -i.bak "s/^BACKEND_PORT=.*/BACKEND_PORT=$BACKEND_PORT/" .env || echo "BACKEND_PORT=$BACKEND_PORT" >> .env
sed -i.bak "s/^FRONTEND_PORT=.*/FRONTEND_PORT=$FRONTEND_PORT/" .env || echo "FRONTEND_PORT=$FRONTEND_PORT" >> .env
rm -f .env.bak

success "Ports resolved (backend=$BACKEND_PORT frontend=$FRONTEND_PORT db=$DB_PORT)"

# -----------------------------
# Backend env
# -----------------------------
if [ ! -f backend/.env ]; then
  info "Creating backend/.env"
  cp backend/.env.example backend/.env
fi

sed -i.bak "s|^APP_URL=.*|APP_URL=http://localhost:$BACKEND_PORT|" backend/.env || \
  echo "APP_URL=http://localhost:$BACKEND_PORT" >> backend/.env
rm -f backend/.env.bak

# -----------------------------
# Frontend env
# -----------------------------
if [ ! -f frontend/.env ]; then
  info "Creating frontend/.env"
  cp frontend/.env.example frontend/.env
fi

sed -i.bak "s|^NEXT_PUBLIC_API_BASE_URL=.*|NEXT_PUBLIC_API_BASE_URL=http://localhost:$BACKEND_PORT/api|" frontend/.env || \
  echo "NEXT_PUBLIC_API_BASE_URL=http://localhost:$BACKEND_PORT/api" >> frontend/.env
rm -f frontend/.env.bak

# -----------------------------
# Start containers
# -----------------------------
if [[ "${RESET:-false}" == "true" ]]; then
  warn "RESET=true → removing volumes"
  $COMPOSE_CMD down -v
else
  $COMPOSE_CMD down
fi

$COMPOSE_CMD  build --no-cache
$COMPOSE_CMD up -d
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
until docker exec loyalty-backend php --version &> /dev/null; do
  sleep 2
  echo -n "."
done
echo ""
success "Backend container is responsive"


# -----------------------------
# Laravel setup
# -----------------------------
if ! grep -q '^APP_KEY=base64:' backend/.env; then
  info "Generating Laravel APP_KEY..."
  docker exec loyalty-backend php artisan key:generate
fi

info "Running migrations and seeders..."
docker exec loyalty-backend php artisan migrate:fresh --seed --force
success "Database migrated and seeded"

# -----------------------------
# Final message
# -----------------------------
echo ""
success "🎉 Installation complete!"
echo "---------------------------------------"
echo "👉 Frontend: http://localhost:$FRONTEND_PORT"
echo "👉 Backend:  http://localhost:$BACKEND_PORT"
echo "---------------------------------------"