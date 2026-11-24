# Architecture (Laravel 13 · MySQL 8 · Sail)

## Layers (one job each)
Route → Middleware → FormRequest (validate) → Controller (thin) → Service (business rules, `DB::transaction`) → Model (relations, casts, tenant scope) → MySQL.

## Tenant isolation (three layers)
1. `EnforceTenant` middleware pins the request to the user's branch (`TenantContext`); branch users without an active branch get 403 (fail closed). HQ runs unscoped.
2. `BelongsToBranch` trait: `BranchScope` filters every read; `branch_id` is filled on create; writing to another branch, or moving a row between branches, throws `TenantViolationException`. Services use `BranchOrGlobalScope` (global catalog + own).
3. Policies for who may do what. Queued jobs must wrap work in `TenantContext::run($branchId, fn () => ...)`.

## Errors
Expected failures extend `BusinessException` (safe message, stable code, HTTP status) and are rendered by `ErrorResponder` with a reference code that is also in the log. Unexpected errors fall through to Laravel's handler (no stack traces in production).

## Money
`App\Support\Money` (bcmath on strings, half-up to 2 decimals). DB columns are `DECIMAL`; MySQL CHECK constraints reject bad prices, rates, stock and totals (`total = subtotal - discount`, `line_total = unit_price * qty`).

## Audit
`AuditLogger::record()` inside the same transaction as the change. `AuditLog` refuses update/delete.

## Tables
branches · users(branch_id null = HQ, is_active) · services(branch_id null = global) · products(global) · service_products · technicians(flat commission_rate %) · discounts · branch_inventory(per-branch stock) · inventory_logs(stock ledger) · transactions(reference, idempotency_key) · transaction_items · commissions(one per item) · audit_logs · imports.
Indexes: `(branch_id, created_at)` on transactions, items, inventory_logs, audit_logs; `(branch_id, technician_id)` on commissions.

## Commission rule (v1)
`commission = Money::percent(line_total_after_discount, technician.commission_rate)`; one technician per line item; flat percentage per technician.
