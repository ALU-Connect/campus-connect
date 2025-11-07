# Campus Connect - ALU Rwanda

A modern campus engagement platform built with Laravel, Vue.js, and Tailwind CSS.

**Production URL:** [https://alu.dadishimwe.com](https://alu.dadishimwe.com)

## Features

### Core Features (Fully Implemented)
- ✅ **Petition System** - Create, vote, and discuss campus petitions
- ✅ **Petition Threads** - Nested comments with voting
- ✅ **User Authentication** - Registration, login, profile management
- ✅ **Event Management** - Create and RSVP to campus events
- ✅ **Admin Panel** - Filament-based moderation dashboard

### Database Ready (Extend as Needed)
- 📦 Campus Marketplace
- 🏠 Roommate Finder with matching algorithm
- 📚 Study Group Hub
- 🍽️ Meal Swipe Exchange
- 🔍 Lost & Found
- 📸 Stories (24-hour ephemeral content)

## Tech Stack

- **Backend:** Laravel 10 + PostgreSQL
- **Frontend:** Inertia.js + Vue 3 + Tailwind CSS
- **Admin:** Filament 3
- **Auth:** Laravel Sanctum
- **Permissions:** Spatie Laravel Permission

## Quick Start

See `docs/LOCAL_SETUP.md` for detailed local development instructions.

See `docs/VPS_DEPLOYMENT.md` for complete production deployment instructions.



1. **Clone and Install**
   ```bash
   git clone <your-repo>
   cd campus-connect
   composer install
   npm install
   ```

2. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Configure Database**
   Edit `.env` and set your database credentials:
   ```
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=campus_connect
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

4. **Run Migrations**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Build Assets**
   ```bash
   npm run dev
   ```

6. **Start Server**
   ```bash
   php artisan serve
   ```

Visit `http://localhost:8000`





## Project Structure

```
campus-connect/
├── app/
│   ├── Http/Controllers/
│   │   ├── Api/          # API controllers for mobile app
│   │   └── Web/          # Web controllers for Inertia
│   ├── Models/           # Eloquent models
│   └── Services/         # Business logic services
├── database/
│   ├── migrations/       # All database migrations
│   └── seeders/          # Database seeders
├── resources/
│   ├── js/
│   │   ├── Pages/        # Inertia Vue pages
│   │   └── Components/   # Vue components
│   └── views/            # Blade templates
├── routes/
│   ├── web.php           # Web routes
│   └── api.php           # API routes
└── docker/               # Docker configuration
```

## Admin Panel

Access the admin panel at `https://alu.dadishimwe.com/admin`

Default admin credentials (after seeding):
- Email: admin@alu.edu
- Password: password

**⚠️ Change these immediately in production!**

## API Documentation

API endpoints are available at `https://alu.dadishimwe.com/api/*` for mobile app integration.

Authentication uses Laravel Sanctum tokens.

See `API.md` for detailed endpoint documentation.

## Feature Roadmap

See `docs/ROADMAP.md` for the planned feature implementation roadmap.



1. Create migration: `php artisan make:migration create_feature_table`
2. Create model: `php artisan make:model Feature`
3. Create controller: `php artisan make:controller FeatureController`
4. Add routes in `routes/web.php` or `routes/api.php`
5. Create Vue page in `resources/js/Pages/`

Example migrations for marketplace, roommate finder, etc. are already included.



1. Create a feature branch
2. Make your changes
3. Submit a pull request

## License

Proprietary - African Leadership University

## Support

For issues or questions, contact the ALU IT department.

For issues or questions, contact the ALU IT department.
