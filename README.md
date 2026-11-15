# Styled in the Cloud
### A Multi-Tenant Point of Sale and Branch Governance System for Signature Salon Operations

> **IT14/L — Professional Track for IT 5**  
> University of Mindanao Tagum College  
> Team: Reajzedrik F. Dabi · Diana Grace V. Cinco · Sebastian Lastimoso · Shen Jl Blessele T. Fuentes

---

## Table of Contents
1. [Project Overview](#project-overview)
2. [Tech Stack](#tech-stack)
3. [Architecture](#architecture)
4. [Repository Structure](#repository-structure)
5. [Getting Started (Local Dev)](#getting-started-local-dev)
6. [Git Workflow](#git-workflow)
7. [Coding Standards](#coding-standards)
8. [Sprint Roadmap](#sprint-roadmap)
9. [Environment Variables](#environment-variables)
10. [Running Tests](#running-tests)
11. [Team](#team)

---

## Project Overview

Signature Salon currently relies on **manual, paper-based logbooks** across branches to record daily transactions, assign technicians, track cosmetic supply usage, and compute staff commissions. This causes billing miscalculations, commission disputes, lost records, delayed reconciliations, and zero real-time visibility for Headquarters (HQ).

This system replaces that workflow with a **centralized, multi-tenant cloud POS and branch governance platform** where:

- **HQ Super Admin** provisions branches, manages global catalogs, and monitors consolidated analytics.
- **Branch Managers** run branch operations, manage staff, restock inventory, and generate reports.
- **Branch Cashiers** process checkouts, assign technicians, apply discounts, and print receipts.

**Core beyond-CRUD features:**
- Exact `DECIMAL(10,2)` split-commission math wrapped in atomic `DB::transaction()`
- Transactional per-service inventory deduction with configurable low-stock alerts
- Bulk CSV/Excel import pipeline with row-level validation and error reporting
- Append-only audit trail: `who`, `what`, `when`, `branch_id`, `old_value`, `new_value`

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8.3+ · Laravel 11 (MVC) |
| Database | MySQL 8.0 (InnoDB, FK constraints, composite indexes on `branch_id`) |
| Frontend | Laravel Blade · Tailwind CSS · Alpine.js · Chart.js |
| Data Import | `maatwebsite/excel` (CSV / Excel import with validation) |
| Auth & RBAC | Laravel Sanctum · Spatie Permission |
| Testing | Pest (feature) · PHPUnit (unit) |
| DevOps | Docker · Docker Compose · GitHub Actions CI/CD |
| Deployment | Linux VPS (Nginx + PHP-FPM + MySQL containers) |

---

## Architecture

```
┌───────────────────────────────────────────────────────┐
│                      Browser / Client                 │
└────────────────────────┬──────────────────────────────┘
                         │ HTTPS
┌────────────────────────▼──────────────────────────────┐
│                   Nginx (reverse proxy)                │
└────────────────────────┬──────────────────────────────┘
                         │
┌────────────────────────▼──────────────────────────────┐
│              Laravel 11 Application (PHP-FPM)          │
│                                                        │
│  Routes ──► Middleware (Auth, TenantScope, RBAC)       │
│                │                                       │
│  Controllers (thin) ──► FormRequests (validation)      │
│                │                                       │
│  Services/ (all business logic)                        │
│    ├── CheckoutService      (DB::transaction)          │
│    ├── CommissionService    (split math, ledger)       │
│    ├── InventoryService     (deduct, alert)            │
│    └── CsvImportService     (validate, batch insert)   │
│                │                                       │
│  Models/ (Eloquent, global branch scope)               │
│                │                                       │
│  Jobs/ (async: low-stock alerts, report export)        │
└────────────────────────┬──────────────────────────────┘
                         │
┌────────────────────────▼──────────────────────────────┐
│               MySQL 8.0  (InnoDB)                      │
│  branches · users · services · products                │
│  transactions · transaction_items · commissions        │
│  inventory_logs · audit_logs · imports                 │
└───────────────────────────────────────────────────────┘
```

### Multi-Tenancy Strategy

Tenancy is enforced via a **global Eloquent scope** (`BranchScope`) applied to every model that carries a `branch_id` column. The authenticated user's `branch_id` is injected into every query automatically at the middleware layer. HQ Super Admin bypasses this scope via an explicit policy check.

```php
// Applied globally — no developer can forget it
protected static function booted(): void
{
    static::addGlobalScope(new BranchScope());
}
```

---

## Repository Structure

```
signature-pos/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── HQ/            # HQ-only controllers (BranchController, GlobalCatalogController…)
│   │   │   └── Branch/        # Branch-scoped controllers (PosController, StaffController…)
│   │   ├── Middleware/
│   │   │   ├── EnforceTenantScope.php
│   │   │   └── RequireRole.php
│   │   └── Requests/          # FormRequest validation classes
│   ├── Models/                # Eloquent models with BranchScope
│   ├── Services/
│   │   ├── CheckoutService.php
│   │   ├── CommissionService.php
│   │   ├── InventoryService.php
│   │   └── CsvImportService.php
│   ├── Jobs/                  # Queued jobs (alerts, exports)
│   └── Policies/              # RBAC authorization policies
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── tests/
│   ├── Feature/
│   │   ├── HQ/
│   │   ├── Branch/
│   │   └── POS/
│   └── Unit/
├── .github/
│   └── workflows/
│       └── ci.yml             # GitHub Actions: lint + test + deploy
├── docker/
│   ├── nginx.conf
│   └── php.ini
├── docs/
│   ├── architecture.md
│   ├── er-diagram.png
│   └── api-endpoints.md
├── docker-compose.yml
├── .env.example
├── .editorconfig
└── README.md
```

---

## Getting Started (Local Dev)

### Prerequisites
- Docker Desktop (or Docker Engine + Compose v2)
- Git

### 1. Clone the repository
```bash
git clone https://github.com/ReajDabi/signature-pos.git
cd signature-pos
```

### 2. Copy and configure environment
```bash
cp .env.example .env
# Edit .env — set DB_PASSWORD, APP_KEY will be generated in step 4
```

### 3. Start Docker containers
```bash
docker compose up -d --build
```

### 4. Install dependencies and set up the application
```bash
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
docker compose exec app php artisan storage:link
```

### 5. Open in your browser
```
http://localhost:8080
```

Default seeded accounts:

| Role | Email | Password |
|---|---|---|
| HQ Super Admin | hq@signature.test | password |
| Branch Manager | manager@branch1.test | password |
| Branch Cashier | cashier@branch1.test | password |

---

## Git Workflow

We follow a **feature-branch workflow** with Conventional Commits.

```
main
 └── develop
      ├── feature/sprint1-auth-tenancy      ← Reajzedrik
      ├── feature/sprint2-catalog-import    ← Diana
      ├── feature/sprint3-pos-checkout      ← Sebastian
      └── feature/sprint4-analytics-audit   ← Shen
```

### Branch naming
```
feature/<sprint>-<short-description>
fix/<short-description>
hotfix/<short-description>
```

### Commit message format (Conventional Commits)
```
<type>(<scope>): <short description>

feat(pos): add split-commission calculator
fix(tenant): enforce branch scope on sales query
test(checkout): add atomic rollback on stock failure
chore(ci): add Laravel Pint to GitHub Actions
```

**Types:** `feat` · `fix` · `test` · `refactor` · `docs` · `chore` · `style`

### Pull Request rules
- Every PR targets `develop`, never `main` directly.
- At least **1 peer review** required before merging.
- All CI checks (Pint + Pest) must pass.
- Squash-merge to keep `develop` history clean.

---

## Coding Standards

### PHP / Laravel
- **PSR-12** enforced via **Laravel Pint** (runs in CI on every push).
- Controllers must stay **thin**: no business logic, only request → service → response.
- All financial and commission writes go inside `DB::transaction()` in a Service class.
- All currency columns use `DECIMAL(10,2)` — never `float` or `double`.
- Every model with a `branch_id` column **must** apply `BranchScope` in `booted()`.
- Use `FormRequest` classes for all input validation — no inline `$request->validate()` in controllers.
- Use `Policy` classes for all authorization — no inline `Gate::check()` in controllers.

### Database
- Every migration uses InnoDB, FK constraints, and appropriate indexes.
- `audit_logs` rows are **never updated or deleted** — INSERT only.
- Composite index `(branch_id, created_at)` on all transaction tables.

### Frontend
- Tailwind utility classes only — no custom CSS files unless strictly necessary.
- All interactive POS state (cart, totals) managed by Alpine.js `x-data` components.
- Chart.js data sourced from JSON API endpoints, not inline Blade variables.

### Testing
- Every Service class has a corresponding **Unit test**.
- Every Controller endpoint has a corresponding **Feature test**.
- Test naming: `it_computes_commission_correctly()`, `it_prevents_cashier_accessing_hq_data()`.
- Use `RefreshDatabase` on all feature tests.
- Minimum target: **80% code coverage** on `app/Services/`.

---

## Sprint Roadmap

| Sprint | Scope | Owner(s) | Target |
|---|---|---|---|
| **1** | Repo setup, Docker, GitHub Actions CI, Auth, Multi-Tenancy, RBAC middleware, Branch provisioning | Reajzedrik | Week 1–2 |
| **2** | Global service/product catalog, Technician profiles + commission rates, CSV/Excel import pipeline | Diana | Week 3–4 |
| **3** | POS cashier screen, `CheckoutService` (transaction + commissions + stock), receipt generator, low-stock alerts | Sebastian | Week 5–6 |
| **4** | Analytics dashboards (Chart.js), Audit log viewer, end-to-end Pest tests, production deployment | Shen | Week 7–8 |

---

## Environment Variables

| Variable | Description | Example |
|---|---|---|
| `APP_NAME` | Application name | `SignaturePOS` |
| `APP_ENV` | `local` / `production` | `local` |
| `APP_KEY` | Laravel encryption key (auto-generated) | |
| `DB_CONNECTION` | `mysql` | |
| `DB_HOST` | MySQL host | `db` (Docker service name) |
| `DB_PORT` | `3306` | |
| `DB_DATABASE` | Database name | `signature_pos` |
| `DB_USERNAME` | DB user | `signature` |
| `DB_PASSWORD` | DB password | *(set locally, never commit)* |
| `QUEUE_CONNECTION` | `database` for async jobs | `database` |
| `MAIL_MAILER` | For low-stock email alerts | `smtp` / `log` (local) |

---

## Running Tests

```bash
# All tests (Pest + PHPUnit)
docker compose exec app php artisan test

# With coverage report
docker compose exec app php artisan test --coverage --min=80

# Single suite
docker compose exec app php artisan test --testsuite=Feature
docker compose exec app php artisan test --testsuite=Unit

# Code style check (Laravel Pint)
docker compose exec app ./vendor/bin/pint --test
```

---

## Team

| Name | Role | GitHub |
|---|---|---|
| Reajzedrik F. Dabi | Lead Developer · Sprint 1 | [@ReajDabi](https://github.com/ReajDabi) |
| Diana Grace V. Cinco | Backend Developer · Sprint 2 | |
| Sebastian Lastimoso | Backend Developer · Sprint 3 | |
| Shen Jl Blessele T. Fuentes | Frontend & Analytics · Sprint 4 | |

---

*University of Mindanao Tagum College — IT14/L · August 2026*
