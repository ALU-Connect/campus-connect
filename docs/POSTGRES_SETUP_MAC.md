# PostgreSQL Setup Guide for macOS

This guide provides step-by-step instructions for installing and configuring PostgreSQL on your Mac for the Campus Connect project.

## Step 1: Install PostgreSQL

We'll use Homebrew to install PostgreSQL:

```bash
brew install postgresql
```

## Step 2: Start PostgreSQL Service

Start the PostgreSQL service and have it run automatically at login:

```bash
brew services start postgresql
```

## Step 3: Create a Database User

By default, PostgreSQL creates a superuser with your Mac's username (e.g., `HP`). It's good practice to create a dedicated user for your application.

1.  **Connect to PostgreSQL:**

    ```bash
    psql postgres
    ```

2.  **Create a new user with a password:**

    Replace `your_username` and `your_password` with your desired credentials.

    ```sql
    CREATE USER your_username WITH PASSWORD 'your_password';
    ```

3.  **Make the user a superuser (for local development):**

    ```sql
    ALTER USER your_username WITH SUPERUSER;
    ```

4.  **Exit `psql`:**

    ```sql
    \q
    ```

## Step 4: Create the Database

Now, create the database for the Campus Connect application:

```bash
createdb campus_connect
```

## Step 5: Configure `.env` File

Open the `.env` file in your project and update the database settings:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=campus_connect
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## Step 6: Run Migrations

Now that your database is configured, run the Laravel migrations to create all the tables:

```bash
php artisan migrate
```

## Step 7: Verify Connection

To verify that everything is working, you can connect to the database and see the tables:

1.  **Connect to the database:**

    ```bash
    psql -d campus_connect
    ```

2.  **List the tables:**

    ```sql
    \dt
    ```

    You should see a list of all the tables from the migrations (e.g., `users`, `petitions`, `events`, etc.).

## Common Commands

-   **Start PostgreSQL:** `brew services start postgresql`
-   **Stop PostgreSQL:** `brew services stop postgresql`
-   **Restart PostgreSQL:** `brew services restart postgresql`
-   **Connect to a database:** `psql -d database_name`

By following these steps, you'll have a fully functional PostgreSQL database ready for your local development environment!
