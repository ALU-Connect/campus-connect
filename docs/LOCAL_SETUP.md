# Local Development Setup Guide

This guide provides detailed instructions for setting up the Campus Connect application for local development and testing.

## 1. Prerequisites

Make sure you have the following installed on your local machine:

- **PHP 8.1 or higher**
- **Composer** (PHP package manager)
- **Node.js 18+ and npm**
- **PostgreSQL 13+**
- **Git**

### Installation Guides

- **PHP:** [https://www.php.net/manual/en/install.php](https://www.php.net/manual/en/install.php)
- **Composer:** [https://getcomposer.org/download/](https://getcomposer.org/download/)
- **Node.js:** [https://nodejs.org/en/download/](https://nodejs.org/en/download/)
- **PostgreSQL:** [https://www.postgresql.org/download/](https://www.postgresql.org/download/)

## 2. Clone the Repository

```bash
git clone https://github.com/ALU-Connect/campus-connect.git
cd campus-connect
```

## 3. Install Dependencies

### Install PHP Dependencies

```bash
composer install
```

### Install Node.js Dependencies

```bash
npm install
```

## 4. Environment Configuration

### Create `.env` File

```bash
cp .env.example .env
```

### Generate Application Key

```bash
php artisan key:generate
```

### Configure Database

1. **Create a PostgreSQL database** for the project. You can use a GUI tool like pgAdmin or the command line:
   ```sql
   CREATE DATABASE campus_connect;
   ```

2. **Edit the `.env` file** and update the database connection details:
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=campus_connect
   DB_USERNAME=your_postgres_username
   DB_PASSWORD=your_postgres_password
   ```

## 5. Run Database Migrations

This will create all the necessary tables in your database.

```bash
php artisan migrate
```

## 6. Seed the Database (Optional)

This will populate your database with dummy data, including an admin user.

```bash
php artisan db:seed
```

**Default Admin Credentials:**
- **Email:** `admin@alu.edu`
- **Password:** `password`

## 7. Build Frontend Assets

This command compiles the Vue.js and Tailwind CSS assets.

```bash
npm run dev
```

Leave this process running in a separate terminal to automatically recompile assets when you make changes.

## 8. Start the Development Server

In another terminal, start the Laravel development server:

```bash
php artisan serve
```

Your application is now running at **[http://localhost:8000](http://localhost:8000)**

## 9. Troubleshooting

- **`could not find driver (SQL: ...)`**
  - Make sure you have the `php-pgsql` extension installed and enabled.

- **`npm ERR! ...`**
  - Try deleting the `node_modules` directory and `package-lock.json` file, then run `npm install` again.

- **`Class '...' not found`**
  - Run `composer dump-autoload`.

- **404 errors on some pages**
  - Make sure your `.env` file is configured correctly and you have run the migrations.
