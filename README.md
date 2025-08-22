# Price Comparator

A web application for comparing product prices across multiple shops. Built with Laravel (PHP) for the backend and Vue.js for the frontend.

## Features

-   Product catalog with categories and manufacturers
-   Price comparison from multiple shops
-   Shopping cart functionality
-   User reviews and ratings
-   RESTful API
-   Admin panel for managing products, categories, and shops

## Tech Stack

-   **Backend:** Laravel (PHP)
-   **Frontend:** Vue.js
-   **Database:** MySQL (or compatible)
-   **Testing:** PHPUnit

## Getting Started

### Prerequisites

-   PHP >= 7.4
-   Composer
-   Node.js & npm
-   MySQL or compatible database

### Installation

1. **Clone the repository:**
    ```bash
    git clone https://github.com/PavelJancik/price_comparator.git
    cd price_comparator/srovnavac
    ```
2. **Install PHP dependencies:**
    ```bash
    composer install
    ```
3. **Install JavaScript dependencies:**
    ```bash
    npm install
    ```
4. **Copy and configure environment file:**
    ```bash
    cp .env.example .env
    # Edit .env to set your database and app settings
    ```
5. **Generate application key:**
    ```bash
    php artisan key:generate
    ```
6. **Run migrations and seeders:**
    ```bash
    php artisan migrate --seed
    ```
7. **Build frontend assets:**
    ```bash
    npm run dev
    ```
8. **Start the development server:**
    ```bash
    php artisan serve
    ```

Visit `http://localhost:8000` in your browser.

## Running Tests

```bash
php artisan test
```

## Project Structure

-   `app/` - Laravel application code (models, controllers, etc.)
-   `resources/` - Frontend assets (Vue.js, CSS, views)
-   `routes/` - Route definitions
-   `database/` - Migrations, seeders, factories
-   `public/` - Publicly accessible files
-   `tests/` - Test cases

## License

All Rights Reserved

See [LICENCE.md](LICENCE.md) for license information.
