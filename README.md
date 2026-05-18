# Interboard - Single-ISP Billing and Management System

Interboard is a professional, high-performance Single-ISP Billing and Management System. Designed for ISP providers to manage Mikrotik router connections, customer accounts, and billing packages seamlessly.

## Stack
- **Framework**: Laravel 11/12
- **PHP Version**: 8.3
- **Database**: PostgreSQL

## Database Core Architecture

This project initiates with three core tables defining the backbone of the ISP system:

### 1. MikroTik Routers (`mikrotik_routers`)
Manages API and connection parameters for one or more MikroTik routers.
- Automatically handles API password security using modern Laravel encryption casting (`casts(): array`).
- Stores name, IP, port (default `8728`), username, password, and active status.

### 2. Internet Packages (`internet_packages`)
Defines the subscription plans offered to customers.
- Holds plan name (e.g., 'Starter 5Mbps'), the exact corresponding PPPoE profile on the MikroTik router, and price.

### 3. Customers (`customers`)
Represents customer details, package subscriptions, and associated MikroTik routers.
- Encapsulates details such as phone (unique), PPPoE credentials, monthly bills (can override package default price), billing day (1-28), and status (`active`, `suspended`, `pending`).
- Uses a PHP 8.3 native `CustomerStatus` backing string Enum.

---

## Setup & Installations

Once PHP & Composer are installed in your target server environment, run the following to install dependencies and run migrations:

```bash
# Install packages
composer install

# Run database migrations
php artisan migrate
```
