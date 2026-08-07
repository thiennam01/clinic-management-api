# Clinic Management REST API

A single-clinic management REST API system developed using **Laravel**, **PostgreSQL 16**, and **Docker Compose**, adhering to the **Global RBAC model via Controller@action** and a professional layered architecture.

---

## 1. Environment & Setup Guide

### System Requirements

* OS: Ubuntu 24
* Docker Engine (tested: `v29.7.1`)
* Docker Compose Plugin (tested: `v5.3.1`)
* PostgreSQL 16
* PHP 8.2+ / Laravel 11.x

### Docker Services

| Service | Description | Port |
| --- | --- | --- |
| `app` | Laravel Application Service (PHP-FPM / Server) | `8000:8000` |
| `db` | PostgreSQL 16 Database | `5432:5432` |

*PostgreSQL data is persistently stored via the `pgdata` Docker volume.*

### How to Run

```bash
# 1. Clone the repository
git clone https://github.com/thiennam01/intern_training_project.git
cd nam-laravel

# 2. Create the environment configuration file
cp .env.example .env

# 3. Build and start Docker containers
docker compose up -d --build

# 4. Generate the Laravel application key
docker compose exec app php artisan key:generate

# 5. Run Database Migrations & Seeders (Create Schema & RBAC data)
docker compose exec app php artisan migrate --seed

# 6. Run Feature Tests
docker compose exec app php artisan test

```

API Access Point: `http://localhost:8000/api/...`

---

## 2. Environment Variables

Core environment variables in the `.env` file:

| Environment Variable | Description | Sample Value |
| --- | --- | --- |
| `DB_CONNECTION` | Database connection driver | `pgsql` |
| `DB_HOST` | PostgreSQL service name in Docker | `db` |
| `DB_PORT` | PostgreSQL connection port | `5432` |
| `DB_DATABASE` | Database name | `clinic_app` |
| `DB_USERNAME` | Database username | `clinic` |
| `DB_PASSWORD` | Database password | `secret` |
| `EXAMINATION_FEE` | Default examination fee (VND) | `100000` |
| `PAYPAL_MODE` | PayPal environment | `sandbox` |
| `PAYPAL_CLIENT_ID` | PayPal Sandbox Client ID | `your-sandbox-client-id` |
| `PAYPAL_CLIENT_SECRET` | PayPal Sandbox Client Secret | `your-sandbox-client-secret` |
| `PAYPAL_CURRENCY` | Payment currency unit | `USD` |

---

## 3. Selected Architecture

The system uniformly implements **Architecture C: Controller - Service - Repository Pattern**.

All source code in the project is implemented 100% consistently with this pattern, strictly abiding by the **No Fat Controller** rule.

---

## 4. Rationale for Architecture C

1. **Adherence to Separation of Concerns:**

* **Thin Controller:** Only handles HTTP Requests, passes them through Form Request validation, invokes the Service, and returns responses formatted as API Resources.
* **Service Layer:** Focuses 100% on application business logic (orchestrating PayPal Sandbox payment flows, invoice calculations, and medical examination processes).
* **Repository Layer:** Encapsulates all PostgreSQL database queries (Eager loading to prevent N+1 issues, record locking via `lockForUpdate`, and aggregate queries).

2. **Safe Multi-Step DB Transactions:**

* Complex operations such as *automatic prescription processing with inventory reduction (`medicines.stock`)* or *invoice creation & PayPal Sandbox capture* require high data integrity. Using a repository separates query/locking logic from the service, keeping the `DB::transaction()` blocks clean and manageable.

3. **Optimization for Feature & Unit Testing:**

* Communicating through **Repository Interfaces** allows for straightforward data mocking when writing unit tests for services without relying on actual database connections.

4. **High Compatibility with Custom RBAC Mechanism:**

* Breaking down controllers into "Thin Controllers" enables the `CheckPermission` middleware to evaluate permissions in the format `CONTROLLER.ACTION` (e.g., `PATIENTS.FINDALL`) independently right at the HTTP layer.

---

## 5. Request Flow Diagram

When a client sends a request to the API, the processing flow traverses the layers in this order:

```text
 Client (Postman / Frontend)
           │
           │  1. HTTP Request (Sanctum API Token)
           ▼
┌─────────────────────────────────────────────────────────┐
│ CheckPermission Middleware                              │
│ - Automatically maps and checks permission CONTROLLER.ACTION│
│ - If unauthorized -> Returns HTTP 403 Forbidden         │
└──────────────────────────┬──────────────────────────────┘
                           │
                           │  2. Passes valid request
                           ▼
┌─────────────────────────────────────────────────────────┐
│ Thin Controller (e.g., AuthController, PatientController)│
│ - Validates input data via Form Request                 │
│ - Routes the request to the corresponding Service       │
└──────────────────────────┬──────────────────────────────┘
                           │
                           │  3. Invokes Business Logic method
                           ▼
┌─────────────────────────────────────────────────────────┐
│ Service Layer (e.g., AuthService, ExaminationService)   │
│ - Manages Business Logic & DB Transactions              │
│ - Instructs Repository to interact with Data            │
└──────────────────────────┬──────────────────────────────┘
                           │
                           │  4. Invokes Repository method
                           ▼
┌─────────────────────────────────────────────────────────┐
│ Repository Layer                                        │
│ - Executes Query statements (Eloquent ORM / SQL)        │
│ - Eager Loading, Pessimistic Locking (lockForUpdate)    │
└──────────────────────────┬──────────────────────────────┘
                           │
                           │  5. Query / Update
                           ▼
┌─────────────────────────────────────────────────────────┐
│ PostgreSQL 16 Database (`db` container)                 │
└─────────────────────────────────────────────────────────┘

```