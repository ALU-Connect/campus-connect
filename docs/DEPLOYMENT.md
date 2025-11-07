# Campus Connect - Deployment Guide

This guide provides step-by-step instructions for deploying the Campus Connect application to a production VPS using Docker.

## 1. Prerequisites

- **VPS:** Ubuntu 22.04 LTS with at least 4GB RAM, 2 vCPUs, and 80GB storage.
- **Domain:** A registered domain name with DNS pointing to your VPS IP address.
- **Tools:** `git`, `docker`, and `docker-compose` installed on your local machine and VPS.

## 2. Server Setup

Connect to your VPS via SSH and perform the following steps:

### Install Docker & Docker Compose

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker $USER

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/download/v2.23.0/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Verify installations
docker --version
docker-compose --version

# Logout and login again for docker group to take effect
exit
```

### Install Git

```bash
sudo apt install -y git
```

## 3. Application Deployment

### Clone the Repository

```bash
# Create project directory
sudo mkdir -p /opt/campus-connect
sudo chown $USER:$USER /opt/campus-connect
cd /opt/campus-connect

# Clone your repository
git clone https://github.com/your-org/campus-connect.git .
```

### Configure Environment

1. **Create `.env` file:**
   ```bash
   cp .env.example .env
   ```

2. **Edit `.env` file:**
   ```bash
   nano .env
   ```
   **Important:** Update the following variables:
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_URL=https://your-domain.com`
   - `DB_PASSWORD` (set a strong password)
   - `REDIS_PASSWORD` (set a strong password)
   - Mail server credentials (`MAIL_*` variables)

3. **Generate Application Key:**
   ```bash
   docker-compose run --rm app php artisan key:generate
   ```

## 4. Launch the Application

### Build and Start Containers

```bash
# Build and start all services in detached mode
docker-compose up -d --build
```

### Initialize the Database

```bash
# Run database migrations
docker-compose exec app php artisan migrate --force

# Seed the database with initial data (optional)
docker-compose exec app php artisan db:seed --force
```

### Optimize for Production

```bash
# Cache configuration and routes
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache

# Optimize autoloader
docker-compose exec app composer dump-autoload --optimize
```

## 5. Configure Nginx for SSL (Recommended)

For production, you should use a reverse proxy like Traefik or Caddy to handle SSL certificates automatically. The provided `docker-compose.yml` uses a simple Nginx setup. For SSL, you would typically:

1. Use a Traefik-based `docker-compose.yml` (see the `laravel_campus_connect_plan.md` for an example).
2. Configure your DNS to point to the server.
3. Traefik will automatically obtain Let's Encrypt certificates.

## 6. Maintenance

### View Logs

```bash
# View logs for all services
docker-compose logs -f

# View logs for a specific service
docker-compose logs -f app
```

### Update Application

```bash
# Pull latest code
git pull

# Rebuild and restart containers
docker-compose up -d --build

# Run migrations and optimize
docker-compose exec app php artisan migrate --force
docker-compose exec app php artisan config:cache
```

### Backup Database

```bash
# Create a backup
docker-compose exec postgres pg_dump -U campus_user campus_connect > backup_$(date +%Y%m%d).sql
```

## 7. Admin Panel

Access the admin panel at `https://your-domain.com/admin`

**Default Admin Credentials (after seeding):**
- **Email:** `admin@alu.edu`
- **Password:** `password`

**⚠️ Change these credentials immediately after your first login!**
