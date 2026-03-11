# 🌸 House of Parfum

A Laravel-based web application for managing and browsing perfume collections.

---

## 📋 Prerequisites

- PHP (latest stable)
- Composer
- Node.js & npm
- MySQL
- phpMyAdmin *(optional)*

---

## 🚀 First-Time Setup

> Run these steps **once** to initialize the project.

```bash
cd HouseOfParfum
composer update
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh
```

Then update your `.env` file with the following database credentials:

```env
DB_DATABASE=house_of_parfum
DB_USERNAME=root
DB_PASSWORD=root
```

---

## ▶️ Running the Project

Open **3 separate terminals** and run the following:

### Terminal 1 — Laravel Dev Server
```bash
cd HouseOfParfum
php artisan serve
```

### Terminal 2 — Vite Asset Bundler
```bash
cd HouseOfParfum
npm install
npm run dev
```

### Terminal 3 — phpMyAdmin *(optional)*
```bash
cd /opt/homebrew/share/phpmyadmin
php -S localhost:8888
```

---

## 🌐 Service URLs

| Service      | URL                       |
|--------------|---------------------------|
| App          | http://localhost:8000     |
| phpMyAdmin   | http://localhost:8888     |
