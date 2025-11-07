# Mac Local Setup Guide

This guide provides detailed instructions for setting up the Campus Connect application on a Mac, including alternatives for installing Composer without Homebrew permissions.

## 1. Install PHP

If you don't have PHP installed, you can use the official installer from [php.net](https://www.php.net/downloads.php) or use a version manager like `asdf`.

## 2. Install Composer (without Homebrew)

Since you're facing issues with Homebrew permissions, here are two alternative methods to install Composer:

### Method 1: Manual Installation (Recommended)

This method downloads the Composer PHAR file directly and moves it to a directory in your PATH.

```bash
# 1. Download the installer
php -r "copy(\'https://getcomposer.org/installer\', \'composer-setup.php\');"

# 2. Verify the installer signature (optional but recommended)
# You can skip this if it times out
# HASH=$(curl -sS https://composer.github.io/installer.sig)
# php -r "if (hash_file(\'SHA384\', \'composer-setup.php\') === \'$HASH\') { echo \'Installer verified\'; } else { echo \'Installer corrupt\'; unlink(\'composer-setup.php\'); } echo PHP_EOL;"

# 3. Run the installer
php composer-setup.php

# 4. Remove the installer
php -r "unlink(\'composer-setup.php\');"

# 5. Move Composer to your local bin directory
# This directory is usually in your PATH by default
mv composer.phar /usr/local/bin/composer
```

### Method 2: Using a PHAR file in your project

If you can't move Composer to `/usr/local/bin`, you can keep the `composer.phar` file in your project directory and run it with `php composer.phar`.

```bash
# In your project directory
php composer.phar install
```

## 3. Install Node.js and npm

Download and install Node.js from the official website: [https://nodejs.org/en/download/](https://nodejs.org/en/download/)

## 4. Install PostgreSQL

You can use the official PostgreSQL app for Mac: [https://postgresapp.com/](https://postgresapp.com/)

## 5. Project Setup

Once you have Composer, the rest of the setup is the same as in `docs/LOCAL_SETUP.md`:

```bash
# 1. Clone the repository
git clone https://github.com/ALU-Connect/campus-connect.git
cd campus-connect

# 2. Install dependencies
composer install
npm install

# 3. Configure environment
cp .env.example .env
php artisan key:generate

# 4. Edit .env and set database credentials

# 5. Run migrations
php artisan migrate

# 6. Start development servers
npm run dev  # Terminal 1
php artisan serve  # Terminal 2
```

## Troubleshooting

- **`composer: command not found`**
  - Make sure `/usr/local/bin` is in your PATH. Add `export PATH="/usr/local/bin:$PATH"` to your `~/.zshrc` or `~/.bash_profile`.

- **PostgreSQL connection issues**
  - Make sure the Postgres.app is running and you have created a database for the project.
