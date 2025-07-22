# 📚 Book Lending System API

A RESTful API for managing book loans using Laravel, Docker, and MySQL.  
Developed as part of a PHP Senior Developer test task.

---

## ⚙️ Tech Stack

- PHP 8.2+
- Laravel 10
- MySQL 8.0
- Docker / Docker Compose
- PHPUnit

---

## 🚀 Setup & Installation

```bash
git clone https://github.com/AlmirPinduk/book-lending-api-dev
cd book-lending-api-dev
cp .env.example .env
```

### Start Docker

```bash
docker-compose up -d --build
```

### Inside container:

```bash
docker exec -it laravel_app bash
composer install
php artisan key:generate
php artisan migrate
```

---

## 📬 API Endpoints

### ✅ `GET /api/books`

List all books with available copies.

---

### ✅ `POST /api/loans`

Create a new loan.

**Request Body:**

```json
{
  "book_id": 1,
  "member_id": 1
}
```

If no available copies, returns HTTP `409 Conflict`.

---

### ✅ `POST /api/loans/{id}/return`

Marks a loan as returned and increments book copies.

---

## 🧠 Architecture Highlights

- Domain Models: `Book`, `Member`, `Loan`
- Business logic in `LoanService`
- Custom exception: `OutOfStockException`
- Full PHPDoc and type-hinting
- Request validation via Laravel FormRequest (or `validate()`)

---

## 🧪 Running Tests

```bash
php artisan test
```

Unit test includes:

- Creating a loan and decrementing available copies
- Handling `OutOfStockException` when no copies are left

---

## 🕑 Bonus Feature: Overdue Loan Reminders

### Artisan Command

```bash
php artisan reminders:send-overdue
```

This command logs reminders for all overdue loans (not yet returned) to:

```
storage/logs/laravel.log
```

### Laravel Scheduler

This command is registered to run daily via:

```php
$schedule->command('reminders:send-overdue')->daily();
```

You can activate it with CRON like:

```cron
* * * * * docker exec laravel_app php artisan schedule:run >> /dev/null 2>&1
```

---

## 🌍 Application Access

After containers are running:

```
http://localhost:8000
```

---

## ✍️ Author

Developed by Almir Pinduk 
GitHub: [https://github.com/AlmirPinduk]
