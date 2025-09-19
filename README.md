# Laravel 10 Blog API + Blade Frontend

A simple Laravel 10 application with JWT authentication and a Blade-based frontend.

Features:

- JWT Authentication (register, login, logout)
- Authenticated users can create, update, delete their own blog posts
- Public users can list and view posts
- Search posts by title/body with pagination
- Blade templates with AJAX calls to the API

---

## 📝 Requirements

- PHP >= 8.1
- Composer
- MySQL or any database supported by Laravel

---

## ⚙️ Setup

Clone the repository:

```bash
git clone https://github.com/mastarstroke/Blog_post
cd laravel-jwt-blog
```

Install dependencies:

```bash
composer install
```

Copy the environment file and set your DB credentials:

```bash
cp .env.example .env
php artisan key:generate
```

Run migrations:

```bash
php artisan migrate
```

Generate JWT secret key:

```bash
php artisan jwt:secret
```

Serve the application:

```bash
php artisan serve
```

By default the app runs at: `http://127.0.0.1:8000`

---

## 🔑 Authentication

- `POST /api/auth/register` – register user
- `POST /api/auth/login` – login user (returns token)

Use the token in `Authorization: Bearer <token>` for protected endpoints.

---

## 📝 API Endpoints

| Method | Endpoint         | Description                  |
|--------|-----------------|------------------------------|
| POST   | /api/auth/register | Register user               |
| POST   | /api/auth/login  | Login user (returns token)   |
| GET    | /api/posts       | List posts (public, paginated, search) |
| GET    | /api/posts/{id}  | View single post (public)    |
| POST   | /api/posts       | Create new post (auth)       |
| PUT    | /api/posts/{id}  | Update own post (auth)       |
| DELETE | /api/posts/{id}  | Delete own post (auth)       |

Search posts:

```bash
GET /api/posts?search=keyword&page=1
```

---

## 🖥️ Blade Frontend

Blade templates under `resources/views`:

- `auth/login.blade.php` – login page (AJAX to API)
- `auth/register.blade.php` – register page
- `posts/index.blade.php` – list all posts (public)
- `posts/show.blade.php` – single post view
- `posts/dashboard.blade.php` – dashboard for authenticated user to manage posts

The JWT token is stored in the Laravel session to use in AJAX headers for protected endpoints.

---

## 🧪 Postman Collection

A Postman collection with all endpoints is included in the repository at:

```
docs/LaravelJWTBlog.postman_collection.json
```

Import this file into Postman to test all endpoints quickly.

[Download the Postman collection here](LaravelJWTBlog.postman_collection.json)

---

## 🚀 Quick Start

1. Clone repo & install dependencies  
2. Create `.env` & configure DB  
3. Run `php artisan migrate`  
4. Run `php artisan jwt:secret`  
5. Start server with `php artisan serve`  
6. Visit `http://127.0.0.1:8000/register` to register and log in  
7. Manage posts from **Dashboard**

---

## 📄 License

MIT License
