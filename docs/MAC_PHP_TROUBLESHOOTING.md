# Mac PHP Troubleshooting Guide

This guide provides solutions for common PHP extension and version issues on macOS, specifically for the Campus Connect project.

## Problem 1: Missing `intl` Extension

**Error:** `filament/support v3.3.43 requires ext-intl * -> it is missing from your system.`

This means the Internationalization extension (`intl`) is not enabled in your PHP installation. Here’s how to fix it with Homebrew:

### Solution: Reinstall PHP with `intl`

The error `Unable to load dynamic library 'intl'` means the extension file itself is missing. The easiest way to fix this is to reinstall PHP using Homebrew, which will correctly compile and link all necessary extensions.

1.  **Reinstall PHP:**

    ```bash
    brew reinstall php
    ```

    This command will recompile PHP with all its default extensions, including `intl`.

2.  **Verify the installation:**

    After the reinstallation is complete, check if the `intl.so` file exists. The path should be something like `/opt/homebrew/lib/php/pecl/20240924/intl.so` (the date might differ).

3.  **Ensure `intl` is enabled in `php.ini`:**

1.  **Check if `intl` is installed:**

    ```bash
    php -m | grep intl
    ```

    If you see `intl`, it's installed but not enabled correctly. If not, proceed to the next step.

2.  **Install the extension:**

    Homebrew's PHP formula usually includes `intl` by default. If it's missing, you might need to reinstall PHP with the correct options or ensure your `php.ini` is configured correctly.

3.  **Enable the extension in `php.ini`:**

    a.  Find your `php.ini` file:

        ```bash
        php --ini
        ```

        This will show you the path to your loaded configuration file (e.g., `/opt/homebrew/etc/php/8.4/php.ini`).

    b.  Open the `php.ini` file in a text editor:

        ```bash
        nano /opt/homebrew/etc/php/8.4/php.ini
        ```

    c.  Search for the line `;extension=intl` (it might be commented out with a semicolon).

    d.  Remove the semicolon to uncomment it:

        ```
        extension=intl
        ```

    e.  Save the file and exit the editor.

4.  **Restart your web server:**

    If you're using Laravel Valet or another local server, restart it to apply the changes:

    ```bash
    brew services restart php
    # or
    valet restart
    ```

## Problem 2: PHP Version Mismatch

**Error:** `openspout/openspout v4.25.0 requires php ~8.1.0 || ~8.2.0 || ~8.3.0 -> your php version (8.4.14) does not satisfy that requirement.`

This means a dependency is not yet compatible with your PHP version (8.4). I've updated the `composer.json` to allow for a wider range of PHP versions, but we also need to update the dependencies themselves.

### Solution: Update Composer Dependencies

After I push the updated `composer.json` and `composer.lock` files, you can run:

```bash
# 1. Pull the latest changes from GitHub
git pull origin main

# 2. Install dependencies from the updated lock file
composer install
```

This will install versions of the packages that are compatible with PHP 8.4.

## General Troubleshooting Steps

1.  **Check PHP Version:**

    ```bash
    php -v
    ```

2.  **Check Loaded Extensions:**

    ```bash
    php -m
    ```

3.  **Update Homebrew and PHP:**

    ```bash
    brew update
    brew upgrade php
    ```

4.  **Run `composer update` (as a last resort):**

    If you continue to have issues, running `composer update` will regenerate the `composer.lock` file based on your system's specifications. This should resolve any platform-specific issues.

    ```bash
    composer update
    ```

By following these steps, you should be able to resolve the `intl` extension and PHP version issues and get the project running locally.
