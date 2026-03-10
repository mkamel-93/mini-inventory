# Inventory Management System

> A Laravel-based inventory management system with warehouse tracking, stock transfers, and low-stock alerts.

---

## 📋 Prerequisites

- [Docker](https://docs.docker.com/get-docker/)
- [Make](https://www.gnu.org/software/make/)

---

## 🚀 Quick Start

```bash
make rebuild-container
```

This single command:
- Creates all Docker containers (PHP, Nginx, MySQL, Redis, phpMyAdmin)
- Installs Composer dependencies
- Generates application key
- Runs database migrations

---

## 🎥 Video Demonstrations

- **[System Demo](https://www.loom.com/share/0dadcb28bb954443877982ef88d4755c)** — Complete walkthrough of the inventory management system
- **[Cache & Testing Demo](https://www.loom.com/share/3ad9c82479a446f1b7ec93fe274973c0)** — How caching works and how to run tests

---

## 🛠️ Tech Stack

| Layer | Technologies                      |
|-------|-----------------------------------|
| **Backend** | PHP 8.3 · Laravel 12              |
| **Authentication** | Laravel Fortify · Laravel Sanctum |
| **Database** | MySQL · Redis                     |
| **Testing** | Pest · PHPStan · Pint             |
| **Dev Tools** | Husky · Debugbar |

---

## 📚 Documentation

- **[Docker Setup](docs/docker.md)** — Container management & commands
- **[Husky Git Hooks](docs/husky/husky.md)** — Automated code quality checks
- **[API Documentation](docs/api.md)** — RESTful API endpoints and usage
- **[Database Schema](docs/erd/database.md)** — ERD and database relationships
- **[Testing Guide](docs/testing/testing.md)** — Testing strategy and best practices
