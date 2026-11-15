# System Architecture

## Database Schema (Key Tables)

```sql
-- Core multi-tenancy anchor
branches          (id, name, address, is_active, created_at, updated_at)

-- Users scoped to a branch; branch_id NULL = HQ Super Admin
users             (id, branch_id FK, name, email, password, role, created_at, updated_at)

-- Global service catalog (branch_id NULL) + branch-local overrides
services          (id, branch_id FK NULLABLE, name, price DECIMAL(10,2), duration_min, is_active)

-- Salon supplies consumed per service
products          (id, branch_id FK, name, qty_on_hand DECIMAL(10,2), low_stock_threshold DECIMAL(10,2))

-- Links a service to the supplies it consumes (qty per use)
service_products  (id, service_id FK, product_id FK, qty_consumed DECIMAL(10,2))

-- Technicians are branch-scoped
technicians       (id, branch_id FK, user_id FK NULLABLE, name, commission_rate DECIMAL(5,2))

-- Discounts are branch-scoped; type ∈ {'percent','flat'}
discounts         (id, branch_id FK, label, type, value DECIMAL(10,2), is_active)

-- POS transaction header
transactions      (id, branch_id FK, cashier_id FK→users, discount_id FK NULLABLE,
                   subtotal DECIMAL(10,2), discount DECIMAL(10,2), total DECIMAL(10,2),
                   status, created_at, updated_at)

-- One row per service in the cart
transaction_items (id, transaction_id FK, branch_id FK, service_id FK,
                   technician_id FK, qty INT, unit_price DECIMAL(10,2),
                   line_total DECIMAL(10,2))

-- One row per technician assignment, computed at checkout
commissions       (id, branch_id FK, transaction_item_id FK, technician_id FK,
                   service_id FK, rate DECIMAL(5,2), amount DECIMAL(10,2),
                   is_paid BOOLEAN, paid_at TIMESTAMP NULLABLE)

-- Append-only; never updated or deleted
audit_logs        (id, branch_id FK, user_id FK, action, subject, subject_id,
                   old_value JSON, new_value JSON, ip_address, created_at)

-- Tracks each CSV/Excel import job
imports           (id, branch_id FK, user_id FK, type, filename,
                   total_rows, imported, failed, status, error_log JSON, created_at)
```

## Key Indexes

```sql
-- Tenant isolation queries
CREATE INDEX idx_transactions_branch_date ON transactions (branch_id, created_at);
CREATE INDEX idx_commissions_branch_tech  ON commissions  (branch_id, technician_id);
CREATE INDEX idx_audit_branch_date        ON audit_logs   (branch_id, created_at);
CREATE INDEX idx_products_branch          ON products     (branch_id);
```

## Request Lifecycle

```
Request
  → Nginx
  → PHP-FPM (Laravel)
  → Kernel Middleware stack:
      1. VerifyCsrfToken
      2. Authenticate          (401 if unauthenticated)
      3. EnforceTenantScope    (injects branch_id into Eloquent global scope)
      4. RequireRole           (403 if wrong role for this route group)
  → Route → Controller (thin)
  → FormRequest (validation — 422 on failure)
  → Service class (business logic + DB::transaction)
  → Model → MySQL
  → Response (JSON / Blade)
```

## Commission Calculation

```
commission_amount = floor(line_total × rate / 100 × 100) / 100

Where:
  line_total = service.price × qty          (bcmul, 2 decimal places)
  rate       = technician.commission_rate   (DECIMAL(5,2) stored in DB)

All arithmetic uses PHP bcmath extension — never float or double.
```
