# Hospital_Sys

Hospital management system (Laravel 8, PHP 8.2, MySQL/MariaDB via XAMPP, Livewire 2, Pusher, Blade + Valex Bootstrap 4 template).
Bilingual ar/en (mcamara localization + astrotomic translatable). Owner plans to grow it into a large international product.

**Full reference: `docs/PROJECT_REFERENCE.md`** — routes, data model, every module's logic, known bugs (section 7), roadmap (section 8). Read the relevant section before changing a module, and update it when behaviour changes.

## Run locally
- MySQL from XAMPP, DB `Hos_db` (root, no password). `php artisan serve` (or `start.bat`) → http://localhost:8000
- Use `localhost`, not `127.0.0.1`: a Service Worker from another project (ReliefFlow) is registered on `127.0.0.1:8000` in Edge.
- Reset data: `php artisan migrate:fresh --seed` (one account per role, password `12345678`: admin@hospital.com,
  doctor@, patient@, ray@, lab@, pharmacy.manager@, pharmacy.employee@ — all `@hospital.com`).

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
- Comments and UI strings in the codebase are mostly Arabic; keep new UI text in `resources/lang/{ar,en}`.
