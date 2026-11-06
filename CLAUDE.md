# Hospital_Sys

Hospital management system (Laravel 12, PHP 8.2, MySQL/MariaDB via XAMPP, Livewire 3, Pusher, Blade + Valex Bootstrap 4 template).
Bilingual ar/en (mcamara localization + astrotomic translatable). Owner plans to grow it into a large international product.

**Full reference: `docs/PROJECT_REFERENCE.md`** — routes, data model, every module's logic, known bugs (section 7), roadmap (section 8). Read the relevant section before changing a module, and update it when behaviour changes.

## Run locally
- MySQL from XAMPP, DB `Hos_db` (root, no password). `php artisan serve` (or `start.bat`) → http://localhost:8000
- Use `localhost`, not `127.0.0.1`: a Service Worker from another project (ReliefFlow) is registered on `127.0.0.1:8000` in Edge.
- Reset data: `php artisan migrate:fresh --seed` (one account per role, password `12345678`: admin@hospital.com,
  doctor@, patient@, ray@, lab@, pharmacy.manager@, pharmacy.employee@ — all `@hospital.com`).
- Tests: `php artisan test`. They wipe and reseed the separate DB `hos_db_test` (forced in `phpunit.xml`; `tests/TestCase.php`
  refuses any other DB). Never run them with a cached config (`php artisan config:clear` first). `PageSmokeTest` opens every
  parameter-less GET page as every role and fails on any 500.
- To click through the app against the test DB, use `php -S` from `public/` with `DB_DATABASE=hos_db_test` set:
  `php artisan serve` drops custom env vars, so it would hit the real `Hos_db`.

## Things that are easy to get wrong
- 7 roles = 7 tables + 7 guards (`admin, doctor, patient, ray_employee, laboratorie_employee, pharmacy_employee, pharmacy_manager`).
  Use `Auth::guard('<role>')` explicitly — inside Livewire AJAX requests the default guard is `web` (`Auth::id()` is null).
- Every route file wraps routes in `LaravelLocalization::setLocale()`; URLs are `/{en|ar}/...`. Route names are prefixed per role
  (`admin.`, `doctor.`, `ray_employee.`, …; patient routes have no prefix).
- Appointment status lives in `appointments.type` as Arabic strings (`Appointment::STATUS_*`); never write `trans()` output to the DB.
- `invoices.invoice_status`: 1 new, 2 review, 3 completed. `invoices.type`: 1 cash, 2 credit. `invoice_type`: 1 single, 2 package.
- Keep file/view/asset/translation names case-exact (production will be Linux): `trans('doctors.*')` lives in
  `resources/lang/*/doctors.php`, assets in `public/Dashboard/...`.
- In-app notifications: `App\Models\Notification::send($recipientModel, $message)`; read with `forUser($user)->unread()`.
- Hospital name/contact for prints: `config('hospital.*')` (`HOSPITAL_*` in `.env`).
- Uploaded images go to `public/Dashboard/img/<folder>` via `App\Traits\UploadTrait` (disk `upload_image`), tracked in `images` (morph).
- Livewire 3: components stay in `App\Http\Livewire` (set in `config/livewire.php`). Alpine ships with Livewire — never load
  another copy (removed from `resources/js/app.js`; rebuild with `npm run prod`). `wire:model` is deferred by default; use
  `wire:model.live` when the server must react per change. Events: `$this->dispatch('x', key: value)->to('component')`, JS
  `Livewire.dispatchTo(...)`, hooks `livewire:init` / `morph.updated` / `commit`. Each component view needs one root element.
- User-facing error text from exceptions goes through `error_detail($e)` (`app/helpers.php`): full message only when
  `APP_DEBUG=true`, otherwise a reference code that is logged with the exception.
- Production: `APP_DEBUG=false`, then `php artisan config:cache route:cache view:cache` must all succeed (route names must be unique).
- Comments and UI strings in the codebase are mostly Arabic; keep new UI text in `resources/lang/{ar,en}`.
