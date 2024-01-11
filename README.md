# Laravel Project Installation Guide

This guide will walk you through the steps to set up and this a Laravel project.

## Prerequisites

Before you begin, make sure you have the following installed on your machine:

- [Git](https://git-scm.com/)
- [Composer](https://getcomposer.org/)
- [PHP](https://www.php.net/)

## Installation Steps

1. **Clone the Repository:**

    ```bash
    git clone https://github.com/silahpapa/psmconsult-backend.git
    ```

2. **Install Composer Dependencies:**

    ```bash
    composer install
    ```

3. **Copy Environment File:**

    ```bash
    cp .env.example .env
    ```

   Update the `.env` file with your database and  set the `FRONTEND_LINK` variable with your frontend URL:

    ```env
    FRONTEND_LINK=http://localhost:5173/
    ```

4. **Generate Application Key:**

    ```bash
    php artisan key:generate
    ```

5. **Run Database Migrations:**

    ```bash
    php artisan migrate --seed
    ```

   Make sure your database is configured in the `.env` file.

6**Start the Laravel Development Server:**

    ```bash
    php artisan serve
    ```

   Your Laravel application will be accessible at [http://localhost:8000](http://localhost:8000).

## Additional Notes

- Make sure to have [MailHog](https://github.com/mailhog/MailHog) installed and configured for email testing.

- Test users are created with the seeders. You can login with the following credentials:

  - **Admin User:**
  - Email: admin@test.com
  - Password:admin@test.com
  
