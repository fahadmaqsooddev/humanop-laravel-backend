# HumanOp Laravel Backend - Project Guide

## Architecture Overview

This is a **Laravel 9** human optimization platform backend with JWT authentication (via `php-open-source-saver/jwt-auth` + Sanctum), Stripe Cashier for billing, Spatie permissions for RBAC, Pusher for WebSockets, and AWS S3/SNS for storage/notifications.

### Namespace Structure

- **v4** (`app/Http/Controllers/v4/`, `app/Services/v4/`, `app/Models/v4/`, `routes/v4/`) is the **active codebase**
- Legacy v3 code exists in `app/Http/Controllers/Api/`, `app/Models/Client/`, `app/Services/`
- Both coexist; v4 is the primary development target

### Key Patterns

- **Config-driven models**: Models resolve table names, fillable, and hidden fields via `config('database.models.'.class_basename(__CLASS__))`. New models should follow this pattern.
- **Services layer**: Business logic lives in `app/Services/`, not in controllers. Controllers should be thin.
- **Helpers**: `App\Helpers\Helpers` provides `successResponse()`, `errorResponse()`, `getUser()`, `serverErrorResponse()`. Always use `Helpers::getUser()` instead of `auth()->user()` directly.
- **Response format**: All API responses must use `Helpers::successResponse($data, $message, $statusCode)` or `Helpers::errorResponse($message, $statusCode)`.
- **Jobs for async work**: External API calls (Twilio, AWS SNS, OneSignal, OpenAI) should be dispatched as Jobs when possible.
- **Event-driven notifications**: Use Laravel Events + Listeners for real-time notifications via Pusher.

### Core Systems

- **Assessment**: 28 trait/feature scores, color codes, action plans. See `app/Services/v4/Assessment/AssessmentService.php`.
- **Energy Shield**: Real-time energy tracking with capacity/shield points. See `app/Services/v4/EnergyShieldService.php`.
- **Event Detection**: 12 biometric detectors (Panic, Volatility, Manic, etc.) in `app/Services/v4/EventDetection/`.
- **Boost Sessions**: Energy replenishment via protocols (nap, movement, breathing). EBS formula in `app/Support/HumanOpFormula.php`.
- **Daily Sync**: Premium journaling feature with streak tracking.
- **Billing**: Stripe subscriptions + lifetime purchases. Plans: freemium, premium_monthly, premium_yearly, premium_lifetime, bb_onetime.

### Configuration

- All thresholds, modifiers, protocols, and event configs are in `config/humanop.php`
- Stripe settings in `config/stripeinfo.php`
- HAI Chat endpoints in `config/chat.php`

---

## PR Review Rules

### What to Check

1. **Security**
   - No hardcoded API keys, secrets, or credentials
   - No raw SQL queries or unsanitized user input (use Eloquent or query builder with bindings)
   - No mass assignment vulnerabilities (check `$fillable` / `$guarded` on models)
   - Auth middleware applied on routes handling sensitive data
   - No SSRF, XSS, or injection vulnerabilities

2. **Database & Performance**
   - No N+1 query problems (missing `with()` eager loading in loops)
   - No queries inside loops; use batch operations
   - Migrations must have both `up()` and `down()` methods
   - New indexes for columns used in `where`, `orderBy`, or `join` on large tables

3. **Laravel Best Practices**
   - Use Form Request classes for validation, not inline validation in controllers
   - Controllers should delegate to Services for business logic
   - Use `config()` and `.env` for environment-specific values, never hardcode
   - Use Laravel's built-in features (Cache, Queue, Events) instead of reinventing
   - Return proper HTTP status codes (201 for creation, 404 for not found, etc.)

4. **Error Handling**
   - External API calls (Stripe, Twilio, AWS, OpenAI, GoHighLevel) must be wrapped in try/catch
   - Use `Helpers::serverErrorResponse()` for caught exceptions in production
   - Log errors with context using `Log::error()` before returning error responses

5. **Project Conventions**
   - Use `Helpers::getUser()` to get the authenticated user
   - Use `Helpers::successResponse()` / `Helpers::errorResponse()` for all API responses
   - New models should follow the config-driven pattern if existing models in the same domain use it
   - New routes go in `routes/v4/client_apis/` for v4 API endpoints
   - Jobs must implement `ShouldQueue` with appropriate `$tries` and `$timeout`

6. **Logic & Correctness**
   - Verify business logic matches the intent (e.g., subscription changes update credits correctly)
   - Check for race conditions in concurrent operations (e.g., shield point updates)
   - Null checks on relationships before accessing properties
   - Proper use of soft deletes where applicable

### What NOT to Flag

- **Code style/formatting**: StyleCI handles this (Laravel preset). Do not flag import ordering, spacing, bracket placement, etc.
- **Refactoring suggestions**: Unless they fix an actual bug or security issue, do not suggest refactors.
- **Missing tests**: The project does not currently enforce test coverage.
- **Legacy v3 code**: Only review changes in the PR diff, do not comment on existing legacy patterns.
- **Comments/documentation**: Do not flag missing docblocks or comments unless the code is genuinely confusing.
