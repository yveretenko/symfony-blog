# Symfony Blog
![Symfony](https://img.shields.io/badge/Symfony-7.3-2d2d2d?style=for-the-badge&logo=symfony&logoColor=white)

A simple blog application built with Symfony 7.3 and PHP 8.4, using MySQL as the database. The application is containerized with Docker for easy setup and deployment.

---

## Tech Stack
- **Symfony 7.3**
- **PHP 8.4**
- **MySQL** (via Docker)
- **Docker Compose**

---

## Installation

### 1. Clone the repository
```bash
git clone git@github.com:yveretenko/symfony-blog.git
cd symfony-blog
```

### 2. Copy environment file
```bash
cp .env .env.local
```

### 3. Build and start Docker containers
```bash
docker-compose up -d --build
```

### 4. Install dependencies
```bash
docker exec -it symfony-php bash
composer install
```

### 5. Run migrations
```bash
docker compose exec php php bin/console doctrine:migrations:migrate
```

App runs at **http://localhost:8000**

---

## Development workflow

- `master` – protected branch (production-ready code)
- `feature/*` – feature branches per task
- Each task → separate **Pull Request → master**
