# 📊 Website Traffic Tracker

A simple traffic tracking system for websites. It logs page visits using a JavaScript tracker and shows analytics via a web interface.

---

## 🧱 Stack

- **Frontend:** React + Vite
- **Backend:** Symfony (PHP 8+)
- **Database:** MySQL 8
- **Web Server:** Nginx
- **Containerization:** Docker + Docker Compose

---

## ✨ Features

- ✅ JavaScript tracker for logging page visits
- ✅ REST API to store and fetch data
- ✅ UI to display unique visits per page by date range
- ✅ Dockerized setup for full development environment
- ✅ Unit and integration tests

---

## 📦 Project Structure

```
.
├── backend/                   # Symfony app (PHP 8)
├── frontend/                  # React + Vite app
├── mysql-init/                # Optional DB seed scripts
├── nginx/
│   └── default.conf           # Nginx config
├── docker-compose.yml
└── README.md
```

---

## 🛠 Requirements

- Docker
- Docker Compose
- Ports `3000`, `8080`, and `3307` available

---

## 🔧 Setup Instructions

### 1. Clone the Repository

```bash
git clone https://github.com/Kniazev/yomali_test_tracker.git
cd yomali_test_tracker
```

### 2. Start the Project

```bash
docker-compose up --build -d
```

This builds and starts all services:

- PHP (`php-fpm`)
- MySQL
- Nginx
- Vite frontend

### 3. Check Containers

```bash
docker ps
```

---

## 🌍 Access the App

| Component   | URL                                                    |
| ----------- | ------------------------------------------------------ |
| Frontend    | [http://localhost:3000](http://localhost:3000)         |
| Symfony API | [http://localhost:8080/api](http://localhost:8080/api) |
| MySQL       | localhost:3307                                         |

---

## ⚙️ Database Setup

### Run Migrations

```bash
docker exec -it php bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### Test Environment

```bash
php bin/console doctrine:database:create --env=test
php bin/console doctrine:migrations:migrate --env=test
```

---

## 🗂 Database Schema

```
page_views
├── id (INT, PK, auto_increment)
├── url (VARCHAR)
├── visitor_id (VARCHAR)
├── user_agent (TEXT)
├── ip_address (VARCHAR)
├── created_at (DATETIME)
```

- `visitor_id` is a client-generated ID stored in browser localStorage
- `created_at` is set automatically on insert

---

## 🧪 Run Tests

```bash
docker exec -it php bash
php bin/phpunit
```

---

## 📡 JavaScript Tracker

Embed the following script on any page you want to track:

```html
<script>
  (function () {
    const payload = {
      url: window.location.href,
      visitorId: localStorage.getItem('visitorId') || crypto.randomUUID(),
      userAgent: navigator.userAgent,
      ipAddress: null // filled server-side
    };

    localStorage.setItem('visitorId', payload.visitorId);

    fetch('http://localhost:8080/api/track', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
  })();
</script>
```

---

## ⚖️ Development Notes

- Vite dev server runs on port `3000`
- Symfony API runs behind Nginx on port `8080`
- Vite proxy setup forwards `/api` requests to `http://nginx`

---

## 🧹 Stop and Clean Up

```bash
docker-compose down
```

