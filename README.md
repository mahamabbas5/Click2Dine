# Click2Dine

Restaurant discovery and food-ordering web app. Front end: HTML/CSS/JS. Back end: PHP (PDO). Database: MySQL.

## Modules
1. Accounts: sign up, log in (`api/signup.php`, `api/login.php`)
2. Restaurants and menus: browse restaurants and menus (`api/get_restaurants.php`, `api/get_menu.php`)
3. Cart and orders: place orders and view order history (`api/place_order.php`, `api/get_orders.php`)

## Database
Tables: `restaurants`, `menu_items`, `users`, `orders`. Schema: `db/schema.sql`.
Create the tables and sample data once with `php api/init_db.php`.

## Configuration
The app reads its database settings from environment variables (or `api/db_config.php`):

| Variable | Meaning | Default |
|---|---|---|
| `DB_HOST` (or `RDS_HOSTNAME`) | database host | 127.0.0.1 |
| `DB_PORT` (or `RDS_PORT`) | port | 3306 |
| `DB_NAME` (or `RDS_DB_NAME`) | database name | click2dine |
| `DB_USER` (or `RDS_USERNAME`) | user | c2d_app |
| `DB_PASSWORD` (or `RDS_PASSWORD`) | password | (empty) |

## Quick checks
- `/api/health_db.php` returns the table list when the database connection works.
- `/api/get_restaurants.php` returns the restaurants as JSON.

## Run locally
```
export DB_HOST=127.0.0.1 DB_NAME=click2dine DB_USER=c2d_app DB_PASSWORD=...
php api/init_db.php
php -S 127.0.0.1:8080
```

Webhook test

Webhook test
