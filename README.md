# Nam Laravel Project

Laravel clinic management API running with Docker and PostgreSQL 16.

## Requirements

* Ubuntu 24
* Docker Engine
* Docker Compose plugin
* PostgreSQL 16
* Laravel / PHP application

## Docker Services

| Service | Description               |
| ------- | ------------------------- |
| `app`   | Laravel / PHP application |
| `db`    | PostgreSQL 16 database    |

The PostgreSQL database is persisted using a Docker volume.

## Environment Versions

* **Docker Version:** 29.7.1
* **Docker Compose Version:** v5.3.1

## How to Run

Clone the repository:

```bash
git clone <repo>
cd <repo>
```

Create the environment file:

```bash
cp .env.example .env
```

Build and start the Docker containers:

```bash
docker compose up -d --build
```

Generate the Laravel application key:

```bash
docker compose exec app php artisan key:generate
```

Run database migrations and seeders:

```bash
docker compose exec app php artisan migrate --seed
```

Run the test suite:

```bash
docker compose exec app php artisan test
```

The API is available at:

```text
http://localhost:8000/api/...
```

## Environment Variables

| Variable               | Description                       | Example                      |
| ---------------------- | --------------------------------- | ---------------------------- |
| `DB_CONNECTION`        | Database connection               | `pgsql`                      |
| `DB_HOST`              | PostgreSQL service name in Docker | `db`                         |
| `DB_PORT`              | PostgreSQL port                   | `5432`                       |
| `DB_DATABASE`          | Database name                     | `clinic_app`                 |
| `DB_USERNAME`          | Database username                 | `clinic`                     |
| `DB_PASSWORD`          | Database password                 | `secret`                     |
| `EXAMINATION_FEE`      | Examination fee                   | `100000`                     |
| `PAYPAL_MODE`          | PayPal environment                | `sandbox`                    |
| `PAYPAL_CLIENT_ID`     | PayPal sandbox client ID          | `your-sandbox-client-id`     |
| `PAYPAL_CLIENT_SECRET` | PayPal sandbox client secret      | `your-sandbox-client-secret` |
| `PAYPAL_CURRENCY`      | Payment currency                  | `USD`                        |

## Database Configuration

The project uses PostgreSQL 16.

The Docker Compose configuration uses:

```text
Database: clinic_app
Username: clinic
Password: secret
Host: db
Port: 5432
```

The database data is persisted using the Docker volume `pgdata`.

## API

API endpoints are available under:

```text
http://localhost:8000/api/...
```

A Postman collection is included in the repository for API testing.

## Testing

Run all Laravel tests with:

```bash
docker compose exec app php artisan test
```

## Important

The `.env` file contains local environment configuration and must **not** be committed to Git.

Only `.env.example` should be committed to the repository.
