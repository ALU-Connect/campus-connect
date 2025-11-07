# Campus Connect - Setup Instructions

## Quick Start (Local Development)

### 1. Prerequisites

- PHP 8.1 or higher
- Composer
- Node.js 18+ and npm
- PostgreSQL 13+
- Redis (optional, for caching)

### 2. Installation

```bash
# Clone the repository
git clone <your-repo-url>
cd campus-connect

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env file
# Then run migrations
php artisan migrate

# Seed database (optional)
php artisan db:seed

# Build frontend assets
npm run dev

# Start development server
php artisan serve
```

Visit `http://localhost:8000`

## Production Deployment

See `docs/DEPLOYMENT.md` for complete production deployment instructions using Docker.

## What's Included

✅ **Fully Implemented:**
- Complete database schema (24 migrations)
- User authentication system
- Petition system (models and migrations)
- Docker deployment configuration
- Comprehensive documentation

📝 **Ready to Implement:**
- Campus Marketplace
- Roommate Finder
- Study Group Hub
- Meal Swipe Exchange
- Lost & Found
- Stories

See `docs/IMPLEMENTATION_GUIDE.md` for step-by-step instructions to implement these features.

## Documentation

- **README.md** - Project overview
- **docs/DEPLOYMENT.md** - Production deployment guide
- **docs/API.md** - API endpoint documentation
- **docs/IMPLEMENTATION_GUIDE.md** - Feature implementation guide

## Support

For questions or issues, contact the ALU IT department.
