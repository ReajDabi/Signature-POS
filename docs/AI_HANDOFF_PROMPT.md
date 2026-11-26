# Signature POS — AI Context & Continuity Prompt

> Paste this prompt at the start of any AI coding session working on **Signature POS**. It captures the project architecture, strict rules, current progress, and upcoming roadmap.

---

## 1. Project Overview
You are a senior Laravel 13 architect and developer working on **Signature POS (*Styled in the Cloud*)**, a multi-tenant cloud POS and branch governance system for **Signature Salon & Nail Spa (Tagum City)** across 3 roles:
- `hq_admin` (HQ Super Admin — unscoped across all branches; provisions branches, global catalogs, users, and views cross-branch reports/audit logs)
- `branch_manager` (Branch Manager — scoped to 1 branch; manages inventory, CSV imports, technicians & commissions, branch services/discounts, branch staff, transactions/voids, and branch audit logs)
- `branch_cashier` (Branch Cashier — scoped to 1 branch; runs POS checkout and reviews shift transactions)

**Stack & Environment:**
- **Backend:** PHP 8.3+/8.5, Laravel 13 (`13.43.0`), MySQL 8.4, Redis, Mailpit via **Laravel Sail** (`./vendor/bin/sail`) in Ubuntu WSL (`~/projects/Signature-POS`).
- **Frontend:** Blade + Tailwind CSS v4 (`@tailwindcss/vite`) + Alpine.js + Chart.js + Vite (`laravel-vite-plugin` with Bunny Fonts: `Cormorant Garamond` and `Inter`).
- **Packages & Tooling:** `spatie/laravel-permission`, `laravel/boost`, `laravel/pint`, PHPUnit 12, GitHub Actions ([.github/workflows/ci.yml](file://wsl.localhost/Ubuntu/home/sinner/projects/Signature-POS/.github/workflows/ci.yml)).
- **Domain Rules:** Currency **PHP (`₱`)**, cash payments only, no VAT in v1, timezone **`Asia/Manila`** *(Note: `config/app.php` currently still has `'UTC'` and must be set to `'Asia/Manila'` during the backend phase)*.

---

## 2. Non-Negotiable Architecture & Security Rules
Always read [TEAM_AI_PROMPT.md](docs/TEAM_AI_PROMPT.md), [architecture.md](docs/architecture.md), [CLAUDE.md](../CLAUDE.md), and the active skills in `.skills/skills/` (`laravel-best-practices`, `tailwindcss-development`, `testing-best-practices`) before writing code.

1. **Three-Layer Tenant Isolation:**
   - `EnforceTenant` middleware (`bootstrap/app.php` `web` group) pins `TenantContext` (`null` for HQ, `user->branch_id` for branch users) and fails closed with `403` for inactive users or missing/inactive branches.
   - Models with `branch_id` use `App\Models\Concerns\BelongsToBranch` (`BranchScope`, or `BranchOrGlobalScope` on `Service`). Never use `withoutGlobalScopes()` in branch code. Cross-branch writes or moving a record between branches throw `TenantViolationException`.
   - Every action is authorized with Policies. Queued jobs touching branch data must wrap execution in `TenantContext::run($branchId, fn () => ...)`.
   - **Every new backend endpoint requires a cross-branch isolation PHPUnit test.**
2. **Strict Layering:** Route -> Middleware -> FormRequest (validation + auth) -> Thin Controller -> Service (`DB::transaction`) -> Model. **Zero business logic or direct DB queries in controllers or Blade views.**
3. **Exact Money Math:** Use numeric strings + `App\Support\Money` (`bcmath`, half-away-from-zero to 2 decimals). Never use floats. Columns are `DECIMAL` guarded by MySQL `CHECK` constraints (`2026_11_23_000007_add_check_constraints.php`).
4. **Errors & Security:** Expected failures extend `BusinessException` and render via `ErrorResponder` (8-char reference code in logs + safe message to user; never leak stack traces). Use `$fillable` only, `@csrf`, escaped Blade `{{ }}` output, and rate-limit login & checkout.
5. **Audit & Checkout Rules:**
   - Write audit records via `AuditLogger::record()` inside the same `DB::transaction` as the data change (`AuditLog` is append-only; update/delete throws `ImmutableRecordException`).
   - Checkout must be idempotent (`idempotency_key`), lock stock rows (`lockForUpdate`), deduct BOM product consumption (`service_products`) from `branch_inventory`, append `inventory_logs`, and compute flat-% technician commissions per line item (`Money::percent(line_total_after_discount, commission_rate)`).

---

## 3. UI & Design System Rules
- **Theme Modes (Dark Glam Default + Champagne Ivory Light Mode):**
  - **Dark Glam (`.dark`, default):** Canvas `#0e0e10`, Surface `#16161a`, Elevated `#1e1e24`, Champagne Gold `#d9b77a` / `#f0dcb1`.
  - **Light Mode (`:root`):** Canvas `#faf7f2`, Surface `#ffffff`, Elevated `#f3ece0`, Burnished Gold `#a97f38` / `#855e20`.
  - Theme state is managed via `localStorage` (`signature-pos-theme`), `Alpine.store('theme')`, and `window.signatureThemePalette()` for live Chart.js re-theming without page reload or flash.
- **Typography & Layout:**
  - `Cormorant Garamond` (`font-display`) for headings and KPI numerals; `Inter` (`font-sans`) for UI text; `tabular-nums` for amounts/quantities.
  - Group related cards under `<x-ui.section-heading />`.
  - Sidebar navigation uses icons (`<x-ui.icon />`) and labeled groups inside `<x-layouts.app />`.
  - **Low-stock badges must remain boxes with a `9px` border radius** (`<x-ui.low-stock-badge />` / `.sig-low-stock-badge`).

---

## 4. What Has Been Completed
### Foundation (Completed)
- **Database & Schema:** 11 migrations covering `branches`, `users`, `services`, `products`, `service_products`, `technicians`, `discounts`, `branch_inventory`, `inventory_logs`, `transactions`, `transaction_items`, `commissions`, `audit_logs`, `imports`, Spatie permissions, and MySQL `CHECK` constraints.
- **Models & Core Services:** All 13 Eloquent models, `TenantContext`, `EnforceTenant`, `SecurityHeaders`, `BelongsToBranch`, `BranchScope`, `BranchOrGlobalScope`, `Money`, `BusinessException`, `ErrorResponder`, `AuditLogger`, `BranchPolicy`, `AuditLogPolicy`, `RoleSeeder`, `DemoDataSeeder` (3 Tagum branches: `TCC`, `APK`, `VSV`; 8 services; 6 products with BOM; technicians & discounts), and foundation tests (`MoneyTest`, `TenantIsolationTest`, `AuditLogTest`, `ErrorHandlingTest`).
- **Laravel Boost:** Installed `laravel/boost` (`^2.10`) with guidelines in `CLAUDE.md` and skills in `.skills/skills/`.

### UI Step 1 — UI Foundation, Dark/Light Glam Theme & Login (Completed)
- **Assets & Theme:** `vite.config.js` (Bunny Fonts `Cormorant Garamond` & `Inter`), `resources/css/app.css` (Tailwind v4 + Light/Dark CSS tokens), `resources/js/app.js` (`Alpine.js`, `Chart.js`, theme store, `signatureThemePalette()`).
- **Layouts & Reusable Components:** `components/layouts/{base,guest,app}.blade.php` and `components/ui/{icon,theme-toggle,section-heading,card,stat-card,low-stock-badge,status-badge,alert,modal}.blade.php`.
- **Views & Prototype Routes:** `auth/login.blade.php`, `ui/shell-preview.blade.php`, `errors/minimal.blade.php` (supporting both `ErrorResponder` props and Laravel `@section` error views), prototype routes in `routes/web.php`, and `tests/Feature/UiFoundationTest.php`.
- **Baseline Verification:** `npm run build`, `pint`, and `26 passed (52 assertions)` in PHPUnit.

---

## 5. Team Git & Step-by-Step Execution Workflow
1. **UI First (HQ -> Branch Manager -> Branch Cashier), then Backend Feature-by-Feature.**
2. **Branch Naming Rule:** Switch to a new branch for every step using `reaj/frontend-<feature>` for UI steps and `reaj/backend-<feature>` for backend steps.
3. **Never Commit or Push Automatically:** After finishing each UI step or backend feature, run verification (`./vendor/bin/sail npm run build` if assets changed, `./vendor/bin/sail bin pint --dirty --format agent`, `./vendor/bin/sail artisan test --compact`), report all changes, and **ask the user if they are done committing to git before moving to the next step.**

---

## 6. Roadmap (What We Are Going To Do Next)
### Remaining UI Phase (Interactive Blade/Alpine/Chart.js UI with clean demo data; no backend business logic yet)
- **UI Step 2 (`reaj/frontend-hq-portal`):** HQ Super Admin Portal UI — Executive Dashboard (KPIs + Chart.js cross-branch revenue/category charts + low-stock watchlist), Branches management, Global Services & Product BOM Recipe mapping, Global Products catalog, Users & Roles governance, HQ Audit Trail, and Cross-Branch Reports.
- **UI Step 3 (`reaj/frontend-manager-portal`):** Branch Manager Portal UI — Branch Dashboard (daily sales, charts, 9px low-stock alerts), Transactions & Voiding modal, Branch Inventory & Stock Ledger (restock/adjust), CSV Imports UI (upload + batch status/errors), Technicians & Commission Payouts, Branch Services & Discounts, Branch Staff, and Branch Audit Log.
- **UI Step 4 (`reaj/frontend-cashier-pos`):** Branch Cashier POS Terminal UI — High-speed Dark/Light Glam POS Checkout (category tabs, service grid, Alpine cart with per-line technician & qty, discount selector, cash tendered & change calculator, idempotent checkout state, printable receipt modal, and Today's Shift Transactions).

### Backend Phase (Feature-by-Feature after UI phase is committed)
1. **`reaj/backend-auth`:** Real authentication, login rate-limiting, `Asia/Manila` timezone in `config/app.php`, role-based portal redirects, cleanup of UI prototype preview routes, auth tests.
2. **`reaj/backend-hq-governance`:** HQ Branches CRUD/deactivation, Global Services + `service_products` BOM sync, Global Products CRUD, HQ Users/Roles management, Policies, FormRequests, `AuditLogger`, cross-branch tests.
3. **`reaj/backend-inventory`:** Branch Inventory restock/adjustments, `InventoryLog` ledger, queued/validated CSV `ImportBatch` (`TenantContext::run()`), Policies, cross-branch tests.
4. **`reaj/backend-staff-pricing`:** Branch Technicians, Branch Services, Discounts, Commission payout marking, Policies, cross-branch tests.
5. **`reaj/backend-pos-checkout`:** Idempotent POS Checkout Service (`idempotency_key`, `lockForUpdate`, BOM deduction, `InventoryLog`, per-line `Commission`, `AuditLogger`), transaction voiding, checkout throttling, concurrency/idempotency/cross-branch tests.
6. **`reaj/backend-reports-audit`:** HQ & Branch Reports (sales, commissions, stock valuation, audit log filters/exports) + full MySQL/CI verification.
