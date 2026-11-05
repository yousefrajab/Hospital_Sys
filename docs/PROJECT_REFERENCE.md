# Hospital_Sys — Full Project Reference

Working reference built from a file-by-file review of the codebase (Nov 2026).
Purpose: complete context for future development. Sections are filled in as the review progresses.

Repo: https://github.com/yousefrajab/Hospital_Sys · Local: `H:\Hospital_Sys` · DB: MySQL `Hos_db` (XAMPP, root, no password)

---

## 1. Routing

### 1.1 How routes are loaded
`app/Providers/RouteServiceProvider.php` loads, all with the `web` middleware group:
`api.php` (prefix `api`), `web.php`, `Backend.php` (admin), `doctor.php`, `ray_employee.php`,
`laboratorie_employee.php`, `patient.php`, `pharmacy_employee.php`, `pharmacy_manager.php`.

Every role file wraps its routes in the mcamara localization group:
`prefix => LaravelLocalization::setLocale()` + `localeSessionRedirect, localizationRedirect, localeViewPath`.
So real URLs are `/{en|ar}/...`; a URL without locale is redirected to the session/default locale.

**`routes/auth.php` is `require`d inside EVERY role file (7 times).** All login/logout/password routes are
therefore registered 7 times under the locale prefix; the last registration wins. Harmless at runtime but
slows route registration and confuses `route:list`. → Should be required once.

`web.php`: `/` → redirect `/login`; plus an empty localized `auth` group (dead code).

### 1.2 Auth routes (`routes/auth.php`)
- `guest:web`: `/register`, `/login` (GET = the single shared login page for all roles), `/forgot-password`, `/reset-password/{token}`.
- Per guard: `POST /login/{guard}` (`login.{guard}`), `POST /logout/{guard}` (`logout.{guard}`).
  Guards: `admin, doctor, patient, ray_employee, laboratorie_employee, pharmacy_employee, pharmacy_manager`.
- Password reset (forgot/reset) exists for admin, doctor, patient, ray_employee, laboratorie_employee
  (URLs use `ray-employee` / `laboratorie-employee`). **Not** for pharmacy roles.
- Patient self-registration: `GET|POST /register/patient`.

### 1.3 Admin (`routes/Backend.php`) — `auth:admin`, prefix `admin`, name `admin.`
- `GET /dashboard/admin` → `AdminProfileController@dashboard` (`dashboard.admin`)
- Profile: `admin/profile` show/edit/update(PATCH)
- Resources: `Sections`, `Doctors`, `Service` (single services), `insurance`, `Ambulance`, `Patients`,
  `Receipt`, `Payment`, `ray_employee`, `laboratorie_employee`, `rooms`, `beds`,
  `patient-admissions` (names `patient_admissions`), `diseases`, `pharmacy_employee`, `medications`,
  `pharmacy_manager`, `testimonials` (except show) + `approve`/`reject` (PATCH)
- Doctors extras: `POST update_password`, `POST update_status`, `doctors/{id}/edit-schedule`, `PUT doctors/schedule/{id}`
- Views via `Route::view`: `Add_GroupServices` (Livewire group services), `single_invoices`, `Print_single_invoices`,
  `group_invoices`, `group_Print_single_invoices` (Livewire invoices)
- Appointments: `appointments` (index = pending), `appointments/approval` (index2 = confirmed),
  `PUT appointments/approval/{id}`, `DELETE appointments/destroy/{id}`, `completed`, `cancelled`,
  `appointments/lapsed`, `PATCH admin-cancel/{appointment}`
- QR: `QR/patient/{id}/showQR`
- Users & roles: `users-roles`, `users-roles/{role_key}/{id}/edit`, PUT/PATCH update
- Vital signs: `POST patient-admissions/{pa}/vital-signs`, `GET .../vital-signs-monitoring`, edit/update/delete `vital-signs/{id}`
- Quirks: `Route::delete('/patients/{patient}')->name('admin.Patients.destroy')` inside the `admin.` group ⇒ real name
  `admin.admin.Patients.destroy` (duplicate of the resource route). Duplicate `GET {x}/{id}/edit` lines for
  ray/lab/pharmacy employees/managers repeat the resource routes.

### 1.4 Doctor (`routes/doctor.php`) — `auth:doctor` + `doctor.status`, prefix `doctor`, name `doctor.`
- `GET /dashboard/doctor` (`dashboard.doctor`)
- `services-management/*`: index, create, store, edit, update, destroy, `attach-existing`, `detach-existing/{service}`
- `Diagnostics` resource + `POST add_review`
- Schedule: `schedule` (show), `schedule/edit/{id}`, `PUT schedule/update`, plus duplicates `doctors/{doctor}/edit-schedule` (`editSchedulee`) / `update-schedule` (`updateSchedulee`)
- `rays`, `Laboratories` resources (doctor requests X-ray / lab tests); `show_laboratorie/{id}`; `patient_details/{id}` (`indexx`)
- Chat (Livewire full-page): `list/patients` (ChatWindow), `chat/patients` (Main)
- Prescriptions refill: `prescriptions/approval-requests`, `{p}/approve-refill`, `{p}/deny-refill`, `prescriptions/adherence-dashboard`
- `invoices` resource, `/404` view
- **OUTSIDE the auth group (no `auth:doctor` middleware!):** `/profile`, `/profile/edit`, `PUT /profile`
  (names `doctor.profile.*`), `Route::resource('prescriptions')`, `/patients/search-for-prescription`,
  `/patient-details/{id}`, `/my-appointments` + `confirm`/`cancel`/`complete` (PATCH).
  `completed_invoices` / `review_invoices` have the middleware individually.
  ⚠️ Security: these rely on controller code only. Also `/profile` collides in URL with other roles' files.

### 1.5 Patient (`routes/patient.php`)
- `GET /home` → `WebsiteController@home` (`auth:patient`) — the patient landing page is the public-style website.
- `auth:patient`, prefix `patient` (no name prefix): website pages (`departments`, `department/{id}`, `services`,
  `group-services`, `doctors`, `doctor/{id}`), `dashboard/patient` (`dashboard.patient`), `my-appointments` + cancel,
  `my-invoices` + print, `my-account` (statement), `my-receipts/{r}/print`, `profile` show/edit/update
  (names `profile.show` etc. — global names!), `invoices`, `laboratories`, `view_laboratoriess/{id}`, `rays`,
  `view_rayss/{id}`, `payments`, chat `list/doctors` (Createchat) / `chat/doctors` (Main),
  `appointments/upcoming|past`, `PATCH appointments/{a}/cancel-by-patient`, `dashboard` view (Livewire appointment form).
- Booking (auth:patient): `patient/appointments/create-form`, `POST patient/appointments`,
  `ajax/doctors-by-section`, `ajax/available-times`, `patient/appointment/success`.
- `ajax/get-doctor-available-dates` — **no auth**.
- Pharmacy: `patient/pharmacy/` (index) — **no auth middleware**.
- ⚠️ `GET /{prescription}` → `PatientPharmacyController@show` and `POST /{prescription}/request-refill` are registered at the
  locale root without route middleware. The controller's constructor applies `auth:patient`, so data is protected, but
  `/{prescription}` is a catch-all for any one-segment URL (`/en/anything`) ⇒ guests are sent to login instead of a 404.
  `refill-requests/pending`, `testimonials/create`, `POST testimonials` are protected the same way (constructor middleware).
  Only `ajax/get-doctor-available-dates` is truly public. → Move all of these into the `auth:patient` + `patient` prefix group.

### 1.6 Ray employee / Lab employee
- Ray: `GET /dashboard/ray_employee`; `auth:ray_employee`, prefix/name `ray_employee.`: `invoices_ray_employee` resource,
  `completed_invoices`, `view_rays/{id}`, `profile` show/edit/update.
- Lab: `GET /dashboard/laboratorie_employee`; prefix/name `laboratorie_employee.`: `invoices_laboratorie_employee` resource,
  `completed_invoicess`, `view_laboratories/{id}`, `profile` (`showw`/`editt`/`updatee`).

### 1.7 Pharmacy
- Manager: `GET /dashboard/pharmacy_manager` (`PharmacyManagercontroller@indexx`); prefix `PharmacyManager`, name
  `pharmacy_manager.`: profile, `medications` resource, `medications/{m}/stocks` index/create/store, `stocks/{s}` show/edit/update/destroy.
- Employee: `GET /dashboard/pharmacy_employee` (closure view); prefix/name `pharmacy_employee.`: profile,
  `prescriptions` index, `{p}/dispense` form + POST, `prescriptions/dispensed`, `prescriptions/on-hold`,
  `medications/search`, `medications/{m}/stocks`.
  ⚠️ `prescriptions/{prescription}/dispense` is declared before `prescriptions/dispensed`/`on-hold` but those are
  different segment counts, so no conflict.

### 1.8 Broadcast channels (`routes/channels.php`)
- `create-invoice.{doctor_id}` — notifies the doctor when an invoice is created for them.
- `chat.{receiver_id}` (closure typed `Doctor $user`) and `chat2.{receiver_id}` (typed `Patient $user`) — real-time chat.
- Guards list per channel; pharmacy guards not included.

### 1.9 API
Only the default `GET /api/user` (sanctum). No real API yet.

---

## 2. Infrastructure (providers, middleware, config)

### 2.1 Providers
- `AppServiceProvider`: view composer `HeaderDataComposer` on `Dashboard.layouts.main-header`.
- `AuthServiceProvider`: one Gate `manage-appointment` (Doctor owns Appointment). No policies.
- `BroadcastServiceProvider`: `Broadcast::routes()` (default `web` middleware) + `routes/channels.php`.
- `EventServiceProvider`: observers for Admin, Doctor, Patient, LaboratorieEmployee, RayEmployee, PharmacyEmployee,
  PharmacyManager (they maintain `global_emails` / `global_identifiers` — cross-table uniqueness of email & national id).
- `RepositoryServiceProvider`: binds Interfaces → Repositories (Sections, Doctors, SingleService, insurance, Ambulance,
  Patients, Receipt, Payment, RayEmployee, doctor_dashboard Invoices/Diagnosis/Rays/Laboratories, LaboratorieEmployee,
  Ray & Lab employee Invoices).
- `RouteServiceProvider` home constants: HOME `/dashboard/user`, ADMIN `/dashboard/admin`, DOCTOR `/dashboard/doctor`,
  RayEmployee, LABORATORIEEmployee, PHARMACYEmployee, PharmacyManager `/dashboard/{role}`, PATIENT `/home`.

### 2.2 Middleware
- Route middleware: Laravel defaults + mcamara (`localize`, `localizationRedirect`, `localeSessionRedirect`,
  `localeCookieRedirect`, `localeViewPath`) + `doctor.status` (`CheckDoctorStatus`: logs out a doctor whose `status` is false).
- `RedirectIfAuthenticated` (`guest`): ignores the guard argument — if ANY guard is logged in it redirects to that
  role's home (checks web, admin, doctor, ray, lab, patient, pharmacy_employee, pharmacy_manager in that order).
  ⇒ Only one role can be logged in per browser session in practice.
- `Authenticate` → `route('login')`.
- Unused (not registered): `AuthenticateAllRoutes`, `CheckGlobalEmail`.

### 2.3 Header data (`app/Http/View/Composers/HeaderDataComposer.php`)
Supplies `unreadMessagesForHeader`, `latestMessagesForHeader`, `generalNotificationsCountForHeader`,
`latestGeneralNotificationsForHeader`.
Note on guards: the `auth:{guard}` middleware calls `Auth::shouldUse($guard)`, so on protected routes `Auth::user()` IS the
logged-in role. Outside such routes (guest pages, Livewire AJAX calls `/livewire/message/*`, routes missing the middleware)
`Auth::user()` falls back to the `web` guard and is null.
⚠️ Bug: `property_exists($model, 'email')` / `property_exists($model, 'id')` are always false on Eloquent models (attributes are
magic) ⇒ the composer never queries ⇒ **header message & notification counters are always 0**. Fix: `isset($user->email)`.
Also notifications are fetched by `user_id` only (no role type) — see 2.4.

### 2.4 Notifications storage
`App\Models\Notification` (table `notifications`, custom, not Laravel's) with `user_id`, `message`, `reader_status` —
**no user type column**, so a doctor #1 and a patient #1 share notifications. Needs a polymorphic `notifiable_type`.
`YourCustomDbChannel` exists twice (`app/Broadcasting` and `app/Channels`) — identical, writes chat-message notifications.

### 2.5 Traits
`UploadTrait`:
- `verifyAndStoreImage($request, $input, $folder, $disk, $id, $type)` — stores the file as `Str::slug(name).ext`
  (⚠️ collisions: two people with the same name overwrite each other's photo) and creates an `images` row (morph).
  On invalid file it calls `flash()` (laracasts/flash is NOT installed → fatal error).
- `verifyAndStoreImageForeach` — unique `time()_uniqid.ext` names (used for multiple uploads: rays/labs).
- `Delete_attachment($disk,$path,$id,$type,$filename=null)`.
Disk `upload_image` = `public/Dashboard/img` (images are stored directly in public/, not storage/).

### 2.6 Config highlights
- `app.php`: name "Hospital Management System", timezone `Asia/Gaza`, locale `ar`, fallback `en`, faker `ar`.
- Locales (mcamara): `en`, `ar`; `hideDefaultLocaleInURL = true`, `useAcceptLanguageHeader = true`.
- `translatable.php` (astrotomic): locales `ar`, `en`, fallback `ar`, `use_fallback` true.
- Auth: guards `web, admin, doctor, ray_employee, laboratorie_employee, patient, pharmacy_employee, pharmacy_manager, api`
  (all session, api = token). Each has a provider + password broker (all use `password_resets`).
- Session: file driver, lifetime default **20000 minutes** (~14 days). Queue `sync`. Cache `file`. Mail `smtp`.
- Broadcasting: Pusher (keys in `.env`, cluster mt1, TLS).
- Filesystems: `upload_image` disk → `public/Dashboard/img`.
- Other packages: `joedixon/laravel-translation` (has `config/translation.php` + views under `resources/views/vendor/translation`),
  simple-qrcode (`QrCode` alias), twilio/sdk, stichoza/google-translate-php, livewire 2, pusher.
- Console: `FixTranslatableData` is an empty stub (`command:name`). No scheduled tasks.

---

## 3. Data model

### 3.1 Tables (actual MySQL schema, 59 tables) — grouped by domain
Notation: `?` nullable, `!` unique. All PKs `id` bigint unsigned. FK delete rule in [ ].

**People / auth (one table per role, each its own guard)**
- `admins` (name, email!, phone!, password, remember_token)
- `doctors` (national_id varchar(9)!, email!, password, section_id→sections [CASCADE], phone!, status bool,
  number_of_statements int) + `doctor_translations` (name, locale)
- `patients` (national_id varchar(9)!, email!, password, Date_Birth date, Phone!, Gender tinyint 1=male 2=female,
  Blood_Group) + `patient_translations` (name, Address, locale)
  ⚠️ No `allergies` / `chronic_diseases` / `remember_token` / `email_verified_at` columns, although the model's
  `$fillable` and the admin create/edit forms (`Dashboard/Patients/create|edit`, `auth/register2`) use allergies.
- `ray_employees` (national_id varchar(9)!), `laboratorie_employees`, `pharmacy_employees`, `pharmacy_managers`
  (national_id bigint!, name, email!, phone!, password, status, remember_token)
- `users` (default Laravel, only `user@gmail.com` seeded; `web` guard — effectively unused)
- `global_emails` (email!, owner_type, owner_id), `global_identifiers` (national_id!, owner_type, owner_id) —
  maintained by model observers to keep email / national id unique across ALL role tables.
- `password_resets`, `personal_access_tokens`, `failed_jobs`

**Organisation**
- `sections` (only timestamps) + `section_translations` (name, description)
- `rooms` (section_id [CASCADE], room_number, type enum(patient_room, private_room, semi_private_room, icu_room,
  examination_room, consultation_room, treatment_room, operating_room, radiology_room, laboratory_room, office, other),
  gender_type enum(male, female, mixed, any), floor, status enum(available, partially_occupied, fully_occupied, out_of_service), notes)
- `beds` (room_id [CASCADE], bed_number, type enum(standard, icu_bed, pediatric_bed, special_care_bed, other), status enum(available, occupied))

**Scheduling**
- `doctor_working_days` (doctor_id, day enum Saturday..Friday, start_time, end_time, appointment_duration int (minutes), active)
- `doctor_breaks` (doctor_working_day_id, start_time, end_time, reason)
- `appointments` (doctor_id, section_id, patient_id? [all CASCADE], name, email, phone, **type** = the status, an enum of
  5 Arabic strings: غير مؤكد / مؤكد / منتهي / ملغي / فات الموعد ; appointment datetime, notes)
  + `appointment_translations` (name) + `appointment_doctor` pivot (legacy)
- `waiting_lists` (patient_id, doctor_id [RESTRICT], preferred_date, is_notified) — table exists, no model.

**Services & billing**
- `services` (price decimal(8,2), description, status, doctor_id? [SET NULL], creator_type?, creator_id?) + `service_translations` (name; FK column `Service_id`)
- `groups` = service packages (Total_before_discount, discount_value, Total_after_discount, tax_rate str, Total_with_tax, doctor_id?)
  + `group_translations` (name, notes; FK `Group_id`) + `service_group` pivot (Group_id, Service_id, quantity)
- `invoices` — the unified invoice table used by the app (invoice_type 1=single 2=group, invoice_date, patient_id, doctor_id,
  section_id, Group_id?, Service_id?, price, discount_value, tax_rate str, tax_value str, total_with_tax, type 1=cash 2=credit,
  invoice_status — see controllers)
- `single_invoices`, `group_invoices` — older separate tables (still have models).
- `receipt_accounts` (receipts: date, patient_id, service_id?, group_id?, Debit, amount, description)
- `payment_accounts` (payment vouchers: date, patient_id, amount, description)
- `patient_accounts` (patient ledger: date, patient_id, invoice_id?, receipt_id?, Payment_id?, Debit, credit)
- `fund_accounts` (cash-box ledger: date, invoice_id?, receipt_id?, Payment_id?, Debit, credit)
- `insurances` (insurance_code, discount_percentage str, Company_rate str, status) + translations — feature hidden in UI.
- `ambulances` (car_number, car_model, car_year_made, driver_license_number, driver_phone, is_available, car_type) + translations — hidden in UI.
- Money columns mix `double(8,2)` (invoices) and `decimal(8,2)`; tax_rate / tax_value stored as **strings**.

**Clinical**
- `diagnostics` (date, review_date?, diagnosis longtext, medicine longtext, invoice_id, patient_id, doctor_id)
- `rays` / `laboratories` (description, invoice_id, patient_id, doctor_id, employee_id?, description_employee?, case tinyint 0=pending 1=done)
  — result images via `images` morphMany.
- `diseases` (name!, description, is_chronic) ; `disease_patient` pivot ; `patient_chronic_diseases` (patient_id, disease_id,
  diagnosed_at, diagnosed_by str, current_status enum(active, controlled, in_remission, resolved), treatment_plan, notes)
- `patient_admissions` (patient_id [CASCADE], bed_id? / doctor_id? / section_id? [SET NULL], admission_date, discharge_date?,
  reason_for_admission, discharge_reason, admitting_diagnosis, discharge_diagnosis,
  status enum(admitted, discharged, transferred_out, transferred_in, cancelled), notes)
- `vital_signs` (patient_admission_id [CASCADE], recorded_at, temperature(4,1), systolic_bp, diastolic_bp, heart_rate,
  respiratory_rate, oxygen_saturation(4,1), pain_level, recorded_by_user_id → **users** [SET NULL], notes)

**Pharmacy**
- `medications` (name, generic_name, description, category, manufacturer, dosage_form, strength, unit_of_measure,
  barcode?!, minimum_stock_level, maximum_stock_level?, purchase_price, selling_price, requires_prescription,
  contraindications, side_effects, status)
- `pharmacy_stocks` (medication_id [CASCADE], batch_number, expiry_date, quantity_on_hand, initial_quantity,
  cost_price_per_unit, supplier_name, received_date, stock_notes)
- `prescriptions` (prescription_number?! auto `PRSC-YYYYMMDD-XXXXXXXX`, patient_id [CASCADE], doctor_id [RESTRICT],
  patient_admission_id? [SET NULL], prescription_date, status enum(new, pending_review, approved, processing,
  ready_for_pickup, dispensed, partially_dispensed, on_hold, cancelled_by_doctor, cancelled_by_pharmacist,
  cancelled_by_patient, expired, refill_requested, refill_denied_by_doctor), doctor_notes, pharmacy_notes,
  dispensed_by_pharmacy_employee_id?, dispensed_at, total_amount, is_chronic_prescription, next_refill_due_date)
- `prescription_items` (prescription_id [CASCADE], medication_id [RESTRICT], dosage, frequency, duration,
  route_of_administration, quantity_prescribed, instructions_for_patient, refills_allowed, refills_done, is_prn)

**Communication**
- `conversations` (sender_email, receiver_email, last_time_message) — chat keyed by **email**, doctor↔patient only.
- `messages` (conversation_id [RESTRICT], sender_email, receiver_email, read, body, type)
- `notifications` (custom: user_id int, message, reader_status) — no user type.
- `testimonials` (patient_id?, patient_name, comment, status enum(pending, approved, rejected), approved_at)

### 3.2 Models — key behaviour
- Translatable (astrotomic): Doctor(name), Patient(name, Address), Section(name, description), Service(name),
  Group(name, notes), Insurance(name, notes), Ambulance(driver_name, notes). Medication is NOT translatable.
- `Admin::setPasswordAttribute` stores the value as-is (no hashing) — callers must `Hash::make`.
- `Appointment` status constants are Arabic strings stored in the DB: PENDING, CONFIRMED, COMPLETED, CANCELLED, LAPSED;
  CANCELLED_BY_PATIENT / CANCELLED_BY_DOCTOR exist in the model but **not in the DB enum** (currently unused).
  Accessors `status_display`, `type_key`.
- `Patient`: relations doctor, image, diseases, admissions, currentAdmission, currentBed (hasOneThrough),
  chronicDiseasesRecords, diagnosedChronicDiseases (with pivot data), appointments, upcomingAppointments,
  pastAppointments, prescriptions, testimonials. `generateQrCode()` / `generateQrCodeSvg()` (near-duplicates) build an
  SVG QR with name, national id, DOB, age, gender, blood group, allergies, chronic diseases and the admin profile URL.
  ⚠️ `pastAppointments()` uses `->where(...)->orWhereIn('type', …)` without grouping ⇒ SQL
  `patient_id = ? AND appointment < now OR type IN (…)` ⇒ **leaks other patients' completed/cancelled appointments.**
- `PatientAdmission` `booted()`: on save marks bed occupied/available (frees the old bed on bed change); on delete frees
  the bed. It uses `saveQuietly()` on the bed ⇒ `Bed::saved` does not fire ⇒ **room occupancy status is not recalculated
  on admission/discharge** (only when a bed itself is edited).
- `Room::updateOccupancyStatus()` computes available / partially / fully occupied for bed-room types.
- `Prescription`: 20 status constants; 6 of them (refill_internal_approved, refill_denied_by_pharmacy, renewal_*) are
  **not in the DB enum** — `STATUS_RENEWAL_APPROVED` is referenced by PharmacyEmployeePrescriptionController.
  Auto number on creating; `status_display` via `trans('patients.status_*')`; `can_request_refill`.
- `Medication`: category / dosage-form / unit lists via `trans('phrM.*')`; `total_stock` (non-expired), `is_low_stock`.
- `PharmacyStock`: `is_expiring_soon` (`config('pharmacy.stock_expiry_warning_days', 90)` — no such config file ⇒ 90),
  `is_expired`, stock values.
- `Conversation` (`doctor()` via receiver_email, `patient()` via sender_email, scope `chekConversation`); `Message`.
- Legacy/wrong relations: `Doctor::prescription()/PrescriptionItem()`, `Patient::prescription()/PrescriptionItem()`
  (wrong-direction belongsTo), `Section::Service()`, `Patient::employee()` (column RayEmployee_id doesn't exist).
- `VitalSign::recordedBy()` → `User` (web users), but vital signs are recorded by admins.
- PharmacyEmployee / PharmacyManager have no password-reset support.

---

## 4. Application logic (controllers, repositories, Livewire)

Conventions seen everywhere: Repository pattern for the older admin modules (controller → `*RepositoryInterface` → repository
returning views/redirects directly); newer modules (rooms, beds, admissions, pharmacy, prescriptions, appointments) put logic in
controllers. Flash keys used by views: `add`, `edit`, `delete`, `update_password`, `update_status`, `success`, `error`, `warning`.
Lots of `Log::info` calls (stripped from the summaries below).

### 4.1 Doctors (admin side) — `Dashboard\DoctorController` + `Repository\Doctors\DoctorRepository`
- index: all doctors with translations, section, image, workingDays (no pagination).
- create/edit pass `Appointment::all()` to the form (legacy many-to-many `appointment_doctor`, synced on save — pointless & heavy).
- store: national_id, email, `Hash::make(password)`, section_id, phone, status=1, number_of_statements, then name (translation),
  then photo via `UploadTrait` (`public/Dashboard/img/doctors/{slug-name}.{ext}`).
- update: same + optional password (bcrypt) + replace photo.
- destroy: `page_id == 1` single delete, else bulk delete via comma list `delete_select_id`.
  ⚠️ passes the filename as `imageable_type` to `Delete_attachment` ⇒ image DB rows are never removed.
- `update_password`, `update_status` (validated in controller).
- Schedule (admin): `editSchedule($id)` → `getScheduleForEdit` → view `Dashboard.Doctors.edit_Days`;
  `updateSchedule` → `updateWorkingHours` (upsert active days, deactivate + delete breaks of unticked days). Breaks are
  **never created** by admin (`updateBreaks()` exists but is unused). Dead code after `return` in both controller methods.
- Schedule (doctor): `showSchedule` (view `Dashboard.Doctors.schedule.show_schedule`), `editSchedulee(Doctor)` /
  `updateSchedulee` — doctor can change start/end/duration of already-active days only (breaks validated but not saved).
  ⚠️ redirects to route `admin.doctor.schedule.show`, which does not exist ⇒ RouteNotFoundException after a successful save.
  ⚠️ `editSchedulee/updateSchedulee` take any `{doctor}` id — no ownership check (a doctor can edit another doctor's hours).
- Doctor dashboard (`dashboard`): counts (invoices, today's confirmed appointments, prescriptions this month, unread messages,
  refill requests, upcoming confirmed), lists (next 5 upcoming, today's, 5 recent prescriptions) → `Dashboard.doctor.dashboard`.
- `myAppointments` (upcoming confirmed, paginated), `cancelAppointment` (JSON; sets 'ملغي', emails + SMS patient),
  `completeAppointment` (JSON; only confirmed & already started; sets 'منتهي', emails + SMS).
  ⚠️ Route `doctor.appointments.confirm` points to `confirmAppointment`, **which does not exist** in DoctorController.
  ⚠️ These routes are outside `auth:doctor`; `Auth::guard('doctor')->id()` is null for guests (the ownership check then
  fails with 403, so no data leak, but `myAppointments` shows an empty page instead of redirecting to login).
- `searchPatientsForPrescription`: search ALL patients by name / national id / email / phone (doctor sees every patient).
- `sendTwilioSms()` duplicated here and in AppointmentController (reads `TWILIO_SID`, `TWILIO_TOKEN`, `TWILIO_FROM` via getenv;
  silently returns false when missing). Mailables: `AppointmentCompleted`, `AppointmentCancelledByDoctor`.

### 4.2 Appointments
**Admin — `Dashboard\appointments\AppointmentController`**
- `index` pending ('غير مؤكد') from today-1 day (`config('appointments.admin_pending_grace_days',1)` — no such config file),
  `index2` confirmed from now-3h, `indexCompleted`, `indexCancelled`, `lapsedAppointments` (past & still pending/confirmed;
  search + date filter). Search inputs on index/index2 are accepted but **ignored** (empty `if`).
- `approval($id)`: only pending & not past → 'مؤكد'; emails `AppointmentConfirmation`, SMS via Twilio; flash `warning`/`add`.
  ⚠️ then `redirect()->route('appointments.index')` — **route doesn't exist** (real name `admin.appointments.index`) ⇒ exception is
  caught ⇒ admin sees "حدث خطأ حرج…" although the appointment WAS confirmed and the notifications WERE sent.
- `adminCancelAppointment`: 'ملغي' + `AppointmentCancelledByAdmin` mail + SMS, then `route('appointments.index2')` (missing) ⇒
  the catch block is empty ⇒ **blank page** after a successful cancel.
- `destroy($id)` hard delete.
- Nothing ever sets 'فات الموعد' automatically (no scheduler); "lapsed" is computed on the fly.

**Patient booking — `Dashboard\PatientSideAppointmentController`** (form `Dashboard.Patients.appointments.patient_create`)
- `create`: sections + (if section chosen) active doctors; pre-fills name/email/phone from the logged-in patient.
- AJAX `getDoctorsBySection` (JSON), `getDoctorAvailableDates` (next 3 months, doctor's active weekdays, skips days that reached
  `number_of_statements` = max appointments per day), `getAvailableTimes` (slots of `appointment_duration` between start/end,
  minus breaks, minus booked, minus past).
- `store(CreatePatientAppointmentRequest)`: re-checks slot + daily limit inside a transaction (no row lock ⇒ race possible), creates
  appointment 'غير مؤكد' → `patient.appointment.success`.
- JSON errors return `$e->getMessage()` to the browser (info leak).
- There is also a Livewire booking flow (`Livewire\Appointments\Create`, `Livewire\Dashboard\PatientAppointmentForm`) — see 4.x.

### 4.3 Broken route references (verified against `route:list`)
Will throw `RouteNotFoundException` when reached:
- `appointments.index`, `appointments.index2` (AppointmentController after confirm / cancel) → should be `admin.appointments.*`.
- `admin.doctor.schedule.show` (DoctorController::updateSchedulee / editSchedule dead code) → should be `doctor.schedule.show`.
- `PharmacyManager.profile.show` (ProfilePharmacyManagerController:120) → should be `pharmacy_manager.profile.show`.
- `admin.login.form`, `ray_employee.login.form`, `laboratorie_employee.login.form`, `pharmacy_employee.login.form`,
  `pharmacy_manager.login.form` (profile controllers, only when not logged in) → should be `login`.
- `route('404')` in the 3 invoice repositories → real name is `doctor.404`.
- Views (rendered unconditionally ⇒ crash): `doctor.schedule.updatee` (edit_schedule.blade:563, real `doctor.doctor.schedule.updatee`),
  `doctors.schedule.edit` (weekly_schedule.blade), `patient.appointments.past` (upcoming.blade:254, real `appointments.past`),
  `patient.appointments.index` (appointment_success.blade:66), `patient.profile.edit` (Patients/profile/show.blade:382, real `profile.edit`),
  `patient.pharmacy.cancel-refill-request` (pending_refills.blade:142), `pharmacy_employee.medications.low_stock` /
  `soon_to_expire` (pharmacy employee dashboard), `pharmacy_manager.stocks.index|create` (inventory_overview.blade),
  `appointments.book` (past.blade:199), `contact` (department_details.blade:562), `completedInvoices` / `invoices.index` /
  `reviewInvoices` (old `main-sidebar-doctor.blade`, not used by the current layout).
  Guarded by `Route::has()` (harmless): `admin.login`, `doctor.login`, `patient.login`, `ray_employee.login`,
  `laboratorie_employee.login` (forgot-password pages). Breeze leftovers (`layouts/navigation`, `auth/verify-email`,
  `auth/confirm-password`) reference `logout`, `dashboard.user`, `verification.send`, `password.confirm` — unused views.
  Note: several of these pages answered 200 in the crawl because the `route()` call sits inside a branch that only renders
  with data (e.g. a list item) — they will crash as soon as data exists.

### 4.4 Patients (admin) — `Dashboard\PatientController` → `Repository\Patients\PatientRepository`
- index: stats (total, currently admitted, new this month) + filters (search name/national id/email/phone, gender, blood group,
  admitted / not admitted), paginate 20 → `Dashboard.Patients.index`.
- create/edit: chronic disease picker (`Disease::is_chronic`) with per-disease pivot data (diagnosed_at, diagnosed_by,
  current_status, treatment_plan, notes) synced into `patient_chronic_diseases`.
- store: `Patient::create($request->except(...))` + name/Address translations + chronic diseases + photo
  (`public/Dashboard/img/patients`). Validation `StorePatientRequest`: national_id 9 digits unique in patients AND
  `global_identifiers`; email unique in patients AND `global_emails`; password min 8 confirmed; Phone numeric unique;
  Date_Birth before today; Gender 1|2; Blood_Group in 8 groups.
- update: same, optional password; unticked chronic diseases are detached.
- show: patient + chronic diseases + admissions + invoices (`Invoice`) + receipts + patient ledger + rays + labs + prescriptions
  (via the wrong `prescription` relation — returns nothing) → `Dashboard.Patients.show`.
- showQR: QR SVG + info table (`Dashboard.Patients.showQR`).
- destroy: delete photo + patient (cascades to appointments, invoices, admissions, … via FKs).
- Note: `allergies` exists in `$fillable` / QR code but has no DB column; the admin forms don't post it, so it is latent only.

### 4.5 Admissions & vital signs — `Dashboard\PatientAdmissionController`, `Dashboard\VitalSignController`
- index: filters status (admitted/discharged), patient search, section, doctor, admission date range; loads ALL patients,
  doctors, sections for the filter dropdowns.
  ⚠️ Patient search uses `whereHas('patient', fn => where('name', …))` — `patients` has no `name` column (it is translated) ⇒
  **SQL error when searching**. Same bug in AppointmentController::lapsedAppointments search (`patient` and `doctor`).
- create: patients without a current admission, active doctors, rooms available/partially, available beds.
- store: creates admission; marks bed occupied with a normal `save()` (⇒ room occupancy recalculated); fills section from the
  bed's room if missing.
- show: admission + vitals + chart datasets (temperature, heart rate, BP, SpO2, respiratory rate) → `PatientAdmissions.show`.
- update: discharge / transfer / bed change. Bed release happens via `saveQuietly()` (model hook + controller) ⇒
  room occupancy NOT recalculated on discharge / bed change.
- destroy: forbidden while still admitted.
- `vitalSignsMonitoringSheet`: vitals table + chart (needs ≥2 readings).
- VitalSignController: store / edit / update / destroy readings. `recorded_by_user_id = Auth::id()` = the **admin's** id (the
  `auth:admin` middleware makes admin the default guard) but the FK points to `users` ⇒ wrong attribution, or an FK error when
  no user with that id exists.

### 4.6 Rooms, beds, diseases (admin CRUD)
- `RoomController`: index filters (room number, section, type, status), paginate 15; create/edit forms; store/update via
  `StoreRoomRequest`/`UpdateRoomRequest`; destroy blocked if any bed occupied. Room-type / gender / status label arrays are
  **hard-coded Arabic, duplicated 3×** in the controller (the same lists exist as `Room::getRoomTypes()` etc.).
- `BedController`: index filters (bed number, room, section, type, status), paginate 25; show with current admission;
  update can set status by hand (can contradict admissions); destroy blocked if occupied.
- `DiseaseController`: CRUD, search name/description, chronic filter, paginate 40; delete blocked if used by patients.

### 4.7 Invoicing (Livewire) — `Livewire\SingleInvoices` (`<livewire:single-invoices/>`), `Livewire\GroupInvoices`
Pages: `admin/single_invoices`, `admin/group_invoices` (`Route::view` → `livewire.single_invoices.index` / `livewire.Group_invoices.index`
— note the capital `G` while the folder is `group_invoices`: works on Windows only).
- Both write to the unified `invoices` table: `invoice_type` 1 = single service, 2 = package. `type` 1 = cash (نقدي), 2 = credit (آجل).
- Single: pick patient, doctor (→ section name auto-filled), service (→ price), discount; tax 17 % default (editable);
  tax_value = (price − discount) × rate; total = price − discount + tax.
  - cash ⇒ row in `fund_accounts` (Debit = total). credit ⇒ row in `patient_accounts` (Debit = total).
  - On create: the doctor's first confirmed appointment matching the patient's **email** is set to
    `trans('AdminSb.appointment_status_completed')` — 'منتهي' in Arabic but **'Completed' in English**, which is not in the enum ⇒
    in the English UI the SQL error rolls back the whole invoice.
  - Cash single invoices also create a custom `Notification` (user_id = doctor id) and fire `CreateInvoice` (Pusher → doctor).
    Credit single invoices and ALL group invoices do not notify.
  - Group invoices: no DB transaction; price/discount/tax copied from the `groups` row.
- Section handling is fragile: the component keeps the section **name** and resolves it with
  `section_translations WHERE name = ? → section_id`; edit() looks up `section_translations WHERE id = invoices.section_id`
  (translation-row id ≠ section id) ⇒ wrong/empty section on edit.
- Updating an invoice whose payment type changed (cash ↔ credit) looks for a ledger row that doesn't exist ⇒ error.
- Deleting an invoice: `Invoice::destroy` (ledger rows cascade). `delete()` method only flashes and redirects (does nothing).
- `print()` redirects to the print view passing names/amounts as query-string params.
- `mount()` reads `auth()->user()->name` (works: the page route runs `auth:admin`, which makes admin the default guard).
- Loads `Patient::all()`, `Doctor::all()`, `Service::all()` / `Group::all()` and all invoices on every render (no pagination).

### 4.8 Sections, services, packages
- `SectionController` → `SectionRepository`: index paginate 10; store/update auto-translate name & description into the other
  language with **stichoza GoogleTranslate (live HTTP call to Google — fails offline / rate-limited ⇒ exception, no try/catch)**;
  show = doctors of the section (`Dashboard.Sections.show_doctors`); destroy supports AJAX.
  ⚠️ `doctors.section_id` is `ON DELETE CASCADE` ⇒ deleting a section **deletes all its doctors** and, by cascade, their
  appointments, invoices, rays, labs, diagnostics… No guard.
- `SingleServiceController` → `SingleServiceRepository`: index/store/update/destroy (services have price, status, optional doctor,
  translated name + description). The controller has **no `create`/`edit` methods** although `Route::resource` registers them
  (`/admin/Service/create`, `/admin/Service/{id}/edit` ⇒ "method does not exist"); the UI uses modals on the index page.
- `Livewire\CreateGroupServices` (page `admin/Add_GroupServices`): build a package from active services with quantities,
  discount, tax (default 17 %) ⇒ `groups` + `service_group` pivot. Edit / delete supported. `group_doctor_id` and
  `availableDoctors` are loaded but **never saved** (groups.doctor_id stays null). No transaction.

### 4.9 Receipts, payments, ledgers (accounting model)
Double-entry-ish ledgers:
- `fund_accounts` = cash box: Debit = cash in, credit = cash out.
- `patient_accounts` = patient balance: Debit = patient owes / was paid out, credit = patient paid.
- Cash invoice → fund Debit. Credit invoice → patient Debit.
- Receipt (`ReceiptRepository`, سند قبض): validated (patient, Debit amount, optional `item_type_id` = `service_{id}` | `group_{id}`,
  description auto-filled with "رسوم خدمة:/رسوم باقة:" + name) ⇒ receipt_accounts.amount, fund Debit, patient credit. Update keeps the
  ledgers in sync; destroy cascades. Show = printable receipt (`Dashboard.Receipt.print`).
- Payment (`PaymentRepository`, سند صرف): **no validation at all**; date stored with `date('y-m-d')` (2-digit year);
  ⇒ payment_accounts.amount, fund credit, patient Debit. Redirects to create after store.
- `receipt_accounts.Debit` column is unused (amount is used). Lists load everything (no pagination).
- There is no general ledger / balance report beyond the patient statement pages (see patient side).

### 4.10 Doctor clinical workflow (invoice → diagnosis / X-ray / lab)
Invoice life-cycle for the doctor (`invoices.invoice_status`): **1 = new (كشوفات)**, **2 = under review (مراجعات)**, **3 = completed**.
- `doctor\InvoiceController` → `doctor_dashboard\InvoicesRepository`: `index` (status 1), `reviewInvoices` (2), `completedInvoices` (3)
  for `Auth::user()` (= doctor); `show($id)` = view an X-ray request (`view_rays`), `showLaboratorie($id)` (`view_laboratories`),
  both check ownership but redirect to the non-existent `route('404')`. create/store/edit/update/destroy are empty stubs.
- `Dashboard_Doctor\DiagnosticController` → `DiagnosisRepository`:
  `store` = write diagnosis + medicine (free text) and set invoice → 3; `addReview` = diagnosis + `review_date` and invoice → 2;
  `show($id)` = patient's diagnosis history (`Dashboard.doctor.invoices.patient_record`).
- `Dashboard_Doctor\RayController` / `LaboratorieController` → repositories: create a request (description, invoice, patient,
  doctor), update description, destroy.
  ⚠️ All of these trust `doctor_id`, `patient_id`, `invoice_id` from hidden form fields — **no validation, no ownership check**
  (a doctor can create records for any invoice / delete any ray or lab request by id).
- `PatientDetailsController`: `index($id)` → `Dashboard.Doctors.Patients.details`, `indexx($id)` → `Dashboard.doctor.invoices.patient_details`
  (identical code): patient, chronic diseases, admissions, diagnoses, rays, labs + small stats. Any doctor can open any patient.
- `doctor\ServiceManagementController` (`doctor/services-management`): a doctor CRUDs his own services (unique name per doctor,
  ownership checks on edit/update/destroy). ⚠️ Routes `attach-existing` / `detach-existing/{service}` point to
  `attachExistingService` / `detachExistingService`, **which do not exist**. `services.creator_type/creator_id` are never set.

### 4.11 Radiology & laboratory employees
- Admin CRUD: `Dashboard\RayEmployeeController` → `RayEmployeeRepository` and `Dashboard\LaboratorieEmployeeController` →
  `LaboratorieEmployeeRepository` (national_id, name, email, password, phone, status, photo in `rayEmployees/` /
  `laboratorieEmployees/`).
- Work queue: `Dashboard_Ray_Employee\InvoiceController` → `Repository\Dashboard_Ray_Employee\InvoicesRepository` (lab = same in
  `Dashboard_Laboratorie_Employee`):
  - `index`: ALL pending requests (`case = 0`) — one shared queue, no assignment/locking.
  - `edit($id)` → form `add_diagnosis`; `update`: sets `employee_id = me`, `description_employee`, `case = 1`, uploads result
    photos (`public/Dashboard/img/Rays` or `laboratories`, unique names, `images` morph rows).
  - `completed_invoices`: my completed ones; `view_rays/{id}` / `view_laboratories/{id}`: only if I completed it (else `route('404')` — missing).
  - No validation on the result text / files (any file type is accepted and stored in `public/`).

### 4.12 Pharmacy staff management & users overview (admin)
- `PharmacyEmployeeController`, `PharmacyManagercontroller` (file name lower-case `c`): admin CRUD like ray/lab employees
  (photos in `pharmacyEmployees/` and `pharmacy_managers/`). `PharmacyManagercontroller::indexx()` is ALSO the pharmacy
  manager's own dashboard (active medications, low stock count, expiring within 90 days, in/out-of-stock chart, 5 recent stock entries).
  No `create`/`show` methods (resource routes exist; UI uses modals).
- `Admin\UserRoleController` (`admin/users-roles`): merges ALL doctors, patients, ray, lab, pharmacy users into one in-memory
  collection, filter by role / status / search, sorts, paginates manually. `editUser` redirects to the role's own edit page.
  ⚠️ status filter & status display use `property_exists($user,'status')` (always false on Eloquent) ⇒ status filter returns
  nothing and every user shows "active". ⚠️ Route `users.roles.update` → `updateUser()` **does not exist**. Loads every user
  of every table on each request (won't scale).

### 4.13 Role dashboards & profiles
- Admin dashboard (`AdminProfileController::dashboard` → `Dashboard.Admin.dashboard`): counts of doctors, patients, staff per role,
  sections, rooms, beds (available / occupied / occupancy %), current admissions, ICU admissions, pending appointments, total users;
  5 latest pending appointments, 5 newest patients. Admin profile show/edit/update (photo in `admin_photos/`).
- Ray / Lab dashboards (`ProfileRayController::dashboard`, `ProfileLabController::dashboard`): totals, pending/completed,
  5 latest, monthly chart for the current year (month names from `trans('ray.months')`). **Global numbers, not per employee.**
- Pharmacy manager dashboard: see 4.12. Pharmacy employee dashboard: closure view `Dashboard.dashboard_PharmacyEmployee.dashboard`.
- Profiles (show/edit/update) for every role. Staff & patient profiles require `current_password` to change password;
  the doctor profile does not.
  ⚠️ Photo upload bug in `ProfileRayController`, `ProfilePatController`, `ProfilePharmacyController`,
  `ProfilePharmacyManagerController`: `verifyAndStoreImage()` is inside `if ($user->image)` ⇒ a user **without** a photo can
  never add one from the profile page (only replace). Lab and doctor profiles are correct.
  ⚠️ `ProfilePatController` renders `Dashboard.patients.profile.*` while the folder is `Dashboard/Patients` — works on Windows
  only (case-insensitive FS). Redirect on missing patient uses `route('login.patient')` (a POST route).
- `DashboardController::index` → `Dashboard.index` (unused legacy).

### 4.14 Prescriptions (doctor) — `Dashboard\PrescriptionController` (resource `prescriptions`, OUTSIDE the doctor auth group)
- Flow: doctor searches a patient (`doctor/patients/search-for-prescription`) → `prescriptions/create?patient_id=` → items
  (medication from active `medications`, dosage, frequency, duration, route, quantity, instructions, refills_allowed, is_prn)
  → `StorePrescriptionRequest` → prescription (status `new` by default, auto number) + items.
- index: my prescriptions, search number / patient name / national id, status & date filters (aborts 403 when not a doctor).
- edit/update only for statuses new / approved and only the owner; update deletes & recreates all items.
- destroy = soft-cancel (status `cancelled_by_doctor`) for new / approved / partially_dispensed / on_hold.
- ⚠️ **`show(Prescription)` has no auth or ownership check and the route has no middleware ⇒ anyone (even logged-out visitors)
  can open `/{locale}/prescriptions/{id}` and see the patient, doctor and medications.**
- Refill workflow: patient requests refill (status `refill_requested`) → doctor `approvalRequests` list → `approveRefill`
  (per item: renew + new refills_allowed, reset refills_done; optional new `next_refill_due_date`; status → `approved`, clears
  dispense info) or `denyRefill` (reason ≥ 10 chars; status `refill_denied_by_doctor`; reason appended to doctor_notes).
- `adherenceDashboard`: chronic prescriptions monitoring — counts (needs decision, non-compliant = refill date passed by > 7
  days, upcoming refills within 7 days), filter tabs, custom ordering, compliance badge per prescription.
  Uses `config('your_config_file.*')` placeholders (defaults 7 / 7 / 45 days).

### 4.15 Pharmacy dispensing & patient pharmacy
**Pharmacy employee — `PharmacyEmployee\PharmacyEmployeePrescriptionController`**
- `index`: queue of prescriptions in new / approved / renewal_approved / partially_dispensed not taken by another employee;
  search number / patient / doctor, status & date filters.
- `showDispenseForm`: prescription + patient (with chronic diseases) + per item the non-expired stock batches (FEFO order).
- `processDispense`: per item choose batch + quantity ⇒ validates batch belongs to the medication and has enough quantity ⇒
  `decrement('quantity_on_hand')`; status → `dispensed` (all items fully) or `partially_dispensed`; stamps dispensed_at/by.
  ⚠️ Gaps: `refills_done` is **never incremented** (refill limits never consumed); dispensed quantities per item are not stored
  (a partially dispensed prescription can be dispensed in full again); no sale/invoice/payment is created; no stock movement log.
- `dispensedPrescriptions` (mine), `onHoldPrescriptions`.
  ⚠️ `onHoldPrescriptions` eager-loads `patient:id,name` / `doctor:id,name` — those tables have **no `name` column** ⇒ SQL error as
  soon as an on-hold prescription exists. (Nothing in the code ever sets `on_hold` yet.)

**Patient — `Patient\PatientPharmacyController`** (constructor middleware `auth:patient`)
- `index` (my prescriptions + filters, `patient_dashboard.pharmacy.index`), `show` (owner only), `requestRefill`
  (allowed when `can_request_refill` ⇒ status `refill_requested`), `pendingRefillRequests`.
- Because the constructor applies `auth:patient`, the root catch-all route `/{prescription}` (see 1.5) sends guests to login for
  ANY unknown one-segment URL (e.g. `/en/anything`) instead of a 404, and gives logged-in users a 404 via model binding.

### 4.16 Medications & stock (pharmacy manager; also exposed to admin)
- `Dashboard\MedicationController` is mounted BOTH at `admin/medications` (admin) and `PharmacyManager/medications`
  (pharmacy manager): index (search name / generic / barcode; status, category, dosage form, Rx filters; paginate 20), create,
  store (`StoreMedicationRequest`), show (+stocks), edit, update, destroy (blocked if used in prescriptions or stock > 0).
  ⚠️ store/update/destroy always redirect to `admin.medications.index` ⇒ a pharmacy manager is bounced to an admin URL
  (→ login) after every save.
- `searchIndexForPharmacy` (pharmacy employee `medications/search`): all active medications with computed stock status
  (in / low / out) and expiry status (valid / soon / expired / none); filters applied in PHP and paginated manually — one
  stock query per medication (N+1).
- `PharmacyManager\PharmacyStockController`: per-medication batches — index (batch / supplier search, expiry filter, total
  valid quantity), create/store, show, edit/update, destroy (only when quantity = 0). Also reachable by the pharmacy employee
  (index only).

### 4.17 Patient website — `WebsiteController` (all behind `auth:patient`; there is **no public website** — `/` → login)
- `home` (`welcome.blade`): sections (+doctors, +`Service` relation which is wrong/always null), 7 featured doctors,
  6 latest services, 6 latest packages, counters (patients, doctors, appointments, "successful surgeries" = completed
  appointments — passed? no: computed but not passed to the view), approved testimonials.
- `showAllDepartments` (paginate 9), `showDepartmentDetails` ("other sections" query includes the current one),
  `showAllDoctors` (section filter, paginate 12), `showDoctorDetails` (weekly schedule with breaks, 4 related doctors),
  `showAllServices` (paginate 9), `showAllGroupServices` (paginate 6).
- `myAppointments`: upcoming (pending/confirmed from today) + past (before today OR completed/cancelled/lapsed) — correctly grouped here.
- `cancelAppointmentFromWebsite` (JSON): owner only, pending/confirmed only, blocked within 24 h before the slot
  (`config('settings.patient_appointment_cancellation_window_hours', 24)`); a **past** appointment can still be cancelled;
  sets 'ملغي', mails the doctor (`AppointmentCancelledByPatientToDoctor`).
- `myInvoices` (paginate 10) + `printInvoice` (owner check), `myAccountStatement` (receipts only — not the full ledger) +
  `printReceipt`. Print header uses **hard-coded placeholder hospital data** (`path/to/your/logo_for_print.png`,
  "123 شارع الصحة…", fake phone/email/tax number) — needs a settings table.

### 4.18 Patient dashboard — `Dashboard_Patient\PatientController` (constructor middleware `auth:patient`)
- `dashboard` (`Dashboard.dashboard_patient.dashboard`): total invoiced, total paid (receipts), upcoming appointments count, prescriptions
  ready for pickup, unread chat messages, chronic refill due within 7 days, appointment today/tomorrow, next 5 appointments.
- `invoices`, `laboratories` (+ `viewLaboratories`), `rays` (+ `viewRays`), `payments` (receipts) — owner checks OK.
- `upcomingAppointments`, `pastAppointments` (uses the leaky `Patient::pastAppointments()` relation ⇒ **shows other patients'
  completed/cancelled appointments** on `patient/appointments/past`), `cancelAppointmentByPatient` (duplicate of the website version).
- `create`/`store`: a third, simpler booking path (no slot/limit checks) — not routed.
- Testimonials: `createTestimonial` (one pending at a time), `storeTestimonial` (status pending → admin approves via
  `Admin\TestimonialController`). These and the refill routes ARE protected thanks to the constructor middleware
  (correction to 1.5: only `ajax/get-doctor-available-dates` is truly public).

### 4.19 Livewire booking components
- `Livewire\Appointments\Create` (`<livewire:appointments.create/>` on the patient home page `welcome.blade`) — the booking
  widget actually used by patients: section → doctor → calendar of enabled dates (2 months, browser event `update-calendar`)
  → time slots (duration, breaks, booked, past) → store. Listeners `setSelectedDoctor`, `dateSelected`, `setServiceDetails`
  (pre-fill from a service/package card).
  ⚠️ `store()` saves `patient_id => Auth::id()`. Livewire AJAX calls (`/livewire/message/*`) only run the `web` middleware, so the
  default guard is `web` ⇒ **`patient_id` is always NULL** for appointments booked here ⇒ they don't appear in the patient's
  "my appointments", dashboard counts, or cancel flows (which all filter by `patient_id`). Must use `Auth::guard('patient')->id()`.
  ⚠️ Booked-slot and daily-limit checks here do NOT exclude cancelled appointments (the controller version does).
- `Livewire\Dashboard\PatientAppointmentForm` — **dead/broken**: its view `livewire/dashboard/patient-appointment-form` does not
  exist and `mount()` queries `sections.status` (no such column). Route `patient/dashboard`
  (`Route::view(..., 'livewire.dashboard.patient-appointment-form')`) ⇒ **HTTP 500** (verified).
- So there are 4 booking implementations: Livewire Create (used), PatientSideAppointmentController (used, `patient/appointments/create-form`),
  PatientAppointmentForm (broken), Dashboard_Patient\PatientController::store (unrouted).

### 4.20 Chat (Livewire + Pusher) — doctor ↔ patient only
Pages: doctor `doctor/list/patients` (`ChatWindow`: list ALL patients, start conversation), `doctor/chat/patients` (`Main`);
patient `patient/list/doctors` (`Createchat`: list ALL doctors), `patient/chat/doctors` (`Main`). `Main` hosts `Chatlist` +
`Chatbox` + `SendMessage` (components talk via `emitTo`).
- Conversations are keyed by **email**: sender = patient, receiver = doctor (both creation paths respect this; the
  `Conversation::doctor()/patient()` relations depend on it). First message is auto-generated.
- `SendMessage::sendMessage` ⇒ `messages` row, update `last_time_message`, `pushMessage` to Chatbox, refresh Chatlist, then:
  `NewChatMessageNotification` (broadcast channel, empty payload), a custom `notifications` row, and `broadcast(MassageSent | MassageSent2)->toOthers()`
  on private channel `chat.{doctorId}` / `chat2.{patientId}`. Chatbox listens with `echo-private:` and marks messages read.
- `Chatlist`: conversations of the current user with last message & unread count (one count query per conversation);
  **opening the chat marks ALL of the user's custom notifications as read** (not only chat ones); search term unused.
- Any patient can message any doctor and vice versa (no relationship / appointment requirement). Text only (no attachments).
- Events: `CreateInvoice` (doctor channel `create-invoice.{id}`, event name `create-invoice`, payload patient name, invoice id,
  message "كشف جديد : "), `MassageSent`, `MassageSent2` (sic). `MyEvent.php` is an empty file (referenced in Backend.php `use`).
- Broadcasting runs synchronously (queue `sync`) — a slow/unreachable Pusher slows every send (errors are swallowed).

### 4.21 Authentication flow (all roles)
- One login page `GET /login` (`AuthenticatedSessionController::create` → `Dashboard.User.auth.signin`) with a role selector; each
  role's form posts to `POST /login/{guard}` → `Auth\{Role}Controller::store({Role}LoginRequest)`.
- ⚠️ **Inverted logic in every role's `*LoginRequest::authenticate()`** (Admin, Doctor, Patient, Ray, Lab, PharmacyEmployee,
  PharmacyManager): `if (auth($guard)->attempt(...)) { RateLimiter::hit(); throw ValidationException('auth.failed'); }`.
  What actually happens:
  - Correct password ⇒ `attempt()` logs the user in, then an "invalid credentials" exception redirects back to `/login`, where the
    `guest` middleware sees the logged-in guard and forwards to the dashboard. Login "works" only by this side effect;
    `session()->regenerate()` in the controller never runs (**session fixation**), and every successful login counts toward the
    5-per-minute lock-out.
  - Wrong password ⇒ the limiter is **cleared** and `authenticate()` returns null ⇒ controller redirects back with an error.
    ⇒ **No brute-force protection at all.**
  Fix: `if (! auth($guard)->attempt(...)) { RateLimiter::hit(...); throw ...; }` then `RateLimiter::clear(...)` and return true.
  (Only the unused default `LoginRequest` for the `web` guard is correct.)
- Logout per guard: logout, invalidate session, regenerate token, redirect `/login`.
- Patient self-registration (`GET|POST /register/patient`, view `auth.register2`, `StoreRegPatientRequest`): creates the patient
  (passes `initial_allergies_text` — not fillable/not a column, silently dropped), name/Address translations, photo, chronic
  diseases; fires `Registered` (email verification listener — patients don't implement MustVerifyEmail), logs in, → `/home`.
- Password reset: Admin, Doctor, Patient, Ray, Lab each have Forgot/Reset controllers + custom notifications
  (`*ResetPasswordNotification`, 106 lines each, nearly identical) using the shared `password_resets` table and per-guard brokers.
  Pharmacy roles have none.

### 4.22 Small modules
- `Admin\TestimonialController` (`admin/testimonials`, views `admin.testimonials.*`): CRUD + approve / reject; approved ones show on the patient home page.
- Insurance (`InsuranceController` → `insuranceRepository`) and Ambulance (`AmbulanceController` → `AmbulanceRepository`):
  basic CRUD, **no validation in the repositories**, `$model->update($request->all())`; insurance uses the lower-case class
  name `insurance::` (fails on case-sensitive autoloading / Linux) and redirects to the relative URL `insurance` (wrong).
  Both are hidden from the menus ("محذوفة مؤقتاً").
- View components: `AppLayout`, `GuestLayout` (Breeze), `Breadcrumb(title, subtitle)` → `components.breadcrumb`.

### 4.23 Validation (FormRequests) — highlights
- Staff create requests check uniqueness of national_id / email per table AND (via closures) in `global_identifiers` /
  `global_emails`; phone unique per table.
- Password policy is inconsistent: patients/admin 8+, doctors (see StoreDoctorsRequest), ray profile 8 + mixed case + numbers,
  pharmacy profiles 8 + numbers. ⚠️ `StoreLaboratorieEmployeeRequest`, `StorePharmacyEmployeeRequest`,
  `StorePharmacyManagerRequest` make `password` **nullable on create** ⇒ an account can be created with `Hash::make(null)`.
- Doctors: `number_of_statements` 1–20 (max appointments per day); photo ≤ 2 MB.
- Vital signs ranges: temp 30–45, systolic 50–300, diastolic 30–200, HR 30–250, RR 5–60, SpO2 70–100, pain 0–10.
- Stock: expiry after today, quantity_on_hand ≤ initial_quantity, unique batch per medication.
- Prescription: ≥1 item, each with medication, dosage, frequency; date ≤ today.
- Beds / rooms: unique number per room / section. Admission: bed must be available; discharge date required when discharging.
- Ambulance / insurance requests exist but the repositories bypass them partially.
- Mailables (`app/Mail`): AppointmentConfirmation, AppointmentCancelledByAdmin, AppointmentCancelledByDoctor,
  AppointmentCancelledByPatientToDoctor, AppointmentCompleted → views `emails/appointments/*`. Sent synchronously (no queue).

---

## 5. Views & front-end

### 5.1 Size & composition (304 Blade files, 80,746 lines)
- Markup ≈ 40,200 lines · inline `<style>` ≈ 30,300 lines (37 %) · inline `<script>` ≈ 10,300 lines.
- **108 views define their own `:root { --… }` design tokens** inline — there is no shared design system / CSS file for the
  custom UI; the same card / badge / table styles are copy-pasted across pages. Fonts: Tajawal (61 views), Cairo (19), Inter, Segoe UI.
- 4,558 translation calls; ~713 lines of hard-coded Arabic UI text outside `trans()` (worst: `main-header`, `Payment/print`,
  `livewire/group_invoices/*`, `Ambulances/*`, mail templates, staff profile edit pages).
- Biggest views: `welcome` (patient home, 1,874), `auth/register2` (1,479), `Doctors/Patients/details` (1,430),
  `Patients/edit` (1,363), `Style/Style` (1,344), `WebSite/doctors/all_doctors` (1,219).

### 5.2 Layouts
- Admin template: **"Valex" by Spruko** (commercial ThemeForest template, Bootstrap 4 + jQuery). The footer still says
  "Copyright © 2020 Valex. Designed by Spruko" and the `<meta>` description/keywords are the template's. ⚠️ Check the licence
  before shipping commercially / internationally.
- `Dashboard.layouts.master` (used by 147 views): `head` (DataTables CSS, flatpickr from CDN without version, RTL vs LTR CSS sets
  chosen by `App::getLocale()`), `main-sidebar` (includes one role sidebar per logged-in guard), `main-header`, content,
  right `sidebar`, `models`, `footer`, `footer-scripts` (jQuery, Bootstrap bundle, ionicons, moment, rating, perfect-scrollbar,
  sparkline, mCustomScrollbar, sidebar, eva-icons, sticky, custom, sidemenu, **DataTables loaded twice** (min + full builds),
  buttons, jszip, pdfmake, `table-data.js`, `@livewireScripts`). `<html lang="en">` always; no `dir` attribute.
  Malformed: `<title>` outside `<head>`, unclosed `div`s at the end of `master`.
- Other layouts: `Dashboard.layouts.master-auth` (10 auth pages), `master2`, `master3`, root `layouts.master` (11 views),
  `layouts.app`/`guest` (Breeze), `WebSite.layouts.master` (+ header, footer, style, scripts — a separate medical website
  template; most website pages are "standalone" views with their own `<html>`), `translation::layout` (joedixon package UI).
- `main-header`: logo links, language switcher (mcamara `getLocalizedURL`), messages dropdown (hard-coded "Messages"),
  notifications dropdown — ⚠️ it queries `Notification::CountNotification(auth()->user())` / `where('user_id', auth()->user())`
  passing the **whole user model** as the id ⇒ always 0 / empty (and ignores the composer variables); user card with role-specific
  profile links; Pusher subscription for `create-invoice.{doctorId}` (doctors only).
- Role sidebars (`Dashboard/layouts/main-sidebar/*`): admin (dashboard, profile, users & roles, sections, doctors, pharmacy
  managers & employees, ray & lab staff, patients, admissions, appointments ×5 lists, diseases, rooms, beds, services, packages,
  single & group invoices, receipts, payments, testimonials — no link to medications), doctor (dashboard, profile, prescriptions
  ×4, services, examinations ×3, appointments, chat ×2, schedule, logout), ray / lab (dashboard, profile, queue, completed),
  patient (dashboard, profile, appointments ×3, prescriptions ×2, invoices, labs, rays, chat ×2, testimonial, logout),
  pharmacy employee (dashboard, queue, dispensed, on-hold, medication search, profile), pharmacy manager (dashboard, profile, medications).
  `main-sidebar-doctor.blade.php` and `sidebar.blade.php` are older/unused variants.

### 5.3 Livewire views
`livewire/appointments/create` (booking widget, 717 lines), `livewire/chat/{main,chatlist,chatbox,send-message,chat-window,createchat}`,
`livewire/GroupServices/*`, `livewire/single_invoices/*`, `livewire/group_invoices/*` (+ print views).
Tags in use: `appointments.create`, `chat.chatbox`, `chat.chatlist`, `chat.send-message`, `create-group-services`,
`group-invoices`, `single-invoices`.

### 5.4 Dead / leftover views (49 never referenced)
Valex demo pages (`Dashboard/{mail*,map-*,media-object,margin,list-group,lockscreen,images,invoice}.blade.php`),
`Dashboard/User/{dashboard,auth/login}`, `Dashboard/Patients/Deleted`, `Dashboard/Admin/users_roles/edit_doctor`,
`Dashboard/Doctors/schedule/weekly_schedule`, `Dashboard/PharmacyManager/Stocks/inventory_overview`,
`Dashboard/dashboard_RayEmployee/invoices/view_rays`, `main-sidebar-doctor`, Breeze `components/*`.
(Mail views and `errors/404` ARE used — via `->markdown()` and the framework.)

### 5.5 Missing views (code renders a view that does not exist ⇒ 500)
`Dashboard.PharmacyManager.Stocks.show` (PharmacyStockController::show), `admin.testimonials.create` / `admin.testimonials.edit`
(TestimonialController), `livewire.dashboard.patient-appointment-form` (route `patient/dashboard` — verified 500),
`Dashboard.Patients.appointments.create` (unrouted).

### 5.6 Third-party front-end assets
- Local: everything under `public/Dashboard/plugins` (Valex bundle: select2 in 72 views, datatables, flatpickr in 29, apexcharts in 9,
  notifIt, amazeui datetimepicker, pickerjs, spectrum, ion-rangeslider, sweetalert, …).
- CDN (no SRI, mostly unversioned): cdnjs (152 refs), jsdelivr (132), unpkg (58), npmcdn (32), Google Fonts, datatables.net,
  code.jquery.com, kit.fontawesome.com, fonts.bunny.net. ⇒ The UI needs internet access and is exposed to CDN changes.
- Built bundle: `public/js/app.js` (Laravel Echo + Pusher + Alpine; Alpine is also loaded by Livewire ⇒ console warning),
  `public/css/app.css` (Tailwind v4 — only used by Breeze layouts).

### 5.7 Linux / production portability (works on Windows only)
- (fixed 2026-11-06: file renamed to `doctors.php`) **1,156 calls to `trans('doctors.…')` while the file was `Doctors.php`** ⇒ on a case-sensitive filesystem every one of them shows
  the raw key. (Only 7 calls use `Doctors.`.)
- **232 `asset('dashboard/…')` references in 66 views** while the folder is `public/Dashboard` ⇒ 404 on Linux (select2, notify, pickers…).
- View names with wrong case: `Dashboard.patients.profile.*`, `livewire.Group_invoices.*`; class name `insurance::`.
- Missing asset files regardless of OS: `Admin/assets/...` (chart.js, notify), `assets/img/faces/*`, `assets/img/brand/favicon.png`,
  `Dashboard/plugins/neomorphic-ui/*`, `WebSite/images/resource/doctor-placeholder.png`, `WebSite/images/icons/search-not-found.svg`.

### 5.8 Translations (`resources/lang/{ar,en}`, 2 locales)
Files: Admin, AdminSb (≈800 keys — admin UI), Doctors (≈650), patients (≈650), phrM (pharmacy manager, 265), phrE (pharmacy
employee, 160), ray (79), lab (64), register (≈70), signinn (32), Services, insurance, sections_trans, main-sidebar_trans,
Doctors.days, routes, validation, `Dashboard/*` (auth, login_trans, main-sidebar_trans, messages, pagination, passwords,
sections_trans, validation), `Website/*`, `vendor/*`; `doctorSb.php` exists only in `ar`.
- ar/en parity is good (small gaps: Doctors 28 keys missing in en, main-sidebar_trans 6, register 5, AdminSb 3).
- 2,792 distinct keys are referenced; **85 missing in ar, 94 in en** (shown raw on screen), mostly in `doctors.*` (23/30),
  `admissions.*` (16 — the file doesn't exist), `AdminSb.*` (15/16), `register.*` (10/11), typo `doctos.*`.
- Appointment statuses are stored as Arabic strings in the DB and some code writes `trans()` output into the DB (see 4.7) — a
  structural i18n problem: statuses must become language-neutral keys.

---

## 6. Tests & tooling
- `tests/` only has the 10 default Breeze/Laravel example tests (auth scaffolding for the `users` table) — **no tests for any
  hospital feature**. `phpunit.xml` default. `phpstan/phpstan` is installed (no config).
- No CI, no Docker (Sail installed but unused), no `.env` documentation beyond `.env.example`.
- Front-end build: Laravel Mix 6 (`webpack.mix.js`: `resources/js/app.js`, `resources/css/app.css` Tailwind v4). A `vite.config.js`
  also exists but is unused (Laravel 8 + Mix).

---

## 7. Findings — prioritised

### 7.1 Critical (security / data integrity)
**Fixed on branch `fix/critical-security` (2026-11-05)** — each verified with HTTP tests:
1. ✅ Login logic in all 7 role login requests corrected (`if (! attempt)`, returns true) ⇒ direct redirect to dashboard,
   session regenerated, lockout after 5 failures works. Added `resources/lang/{ar,en}/auth.php` (failed / throttle messages).
2. ✅ Doctor routes outside the auth group now wrapped in `auth:doctor` + `doctor.status` (same URLs and names). Patient pharmacy
   moved to `patient/pharmacy/*` with `auth:patient` (removes the root `/{prescription}` catch-all ⇒ unknown URLs give 404);
   testimonials wrapped in `auth:patient`. `PrescriptionController::show` now requires the owning doctor.
3. ✅ `Patient::pastAppointments()` conditions grouped — no more leak of other patients' appointments.
4. ✅ Diagnosis / ray / lab requests take patient & doctor from the invoice, which must belong to the logged-in doctor;
   ray/lab update & destroy limited to the owner. `editSchedulee/updateSchedulee` limited to the doctor himself; fixed the
   `doctor.schedule.updatee` route name and the `doctor.schedule.show` redirect.
5. ✅ Section delete is refused while the section has doctors or rooms (message shown; AJAX gets 422).
6. ✅ Uploads: unique file names (`slug-time-random.ext`); removed the call to the missing `flash()` helper; ray/lab result uploads
   validated server-side (description required, images jpeg/png/webp ≤ 5 MB) and only pending requests can be completed.
7. ✅ Password required when creating lab / pharmacy employee / pharmacy manager accounts.
8. ⏳ Still open: `APP_DEBUG=true` in `.env` (fine locally, must be false in production) and controllers returning `$e->getMessage()`.
   Doctors can still open any patient's details (clinical access policy to be decided).

### 7.2 Broken features
**Fixed on branch `fix/full-cleanup` (2026-11-06)** — verified by a crawl of every page for all 7 roles in English and Arabic
(~580 pages, 0 errors) plus functional tests (booking, admin confirm/cancel, doctor confirm, searches, payments, receipts,
partial dispensing, profile photo, Livewire invoice in English, Livewire booking widget, doctor header notifications):
- Appointments: admin confirm/cancel redirect to `admin.appointments.*` (cancel no longer returns a blank page); doctor
  `confirmAppointment` implemented; admin search on pending/confirmed/lapsed lists works (translated names); patients can't
  cancel past appointments.
- Booking widget (`Livewire\Appointments\Create`) saves `patient_id` via the patient guard and ignores cancelled appointments
  when computing free slots / daily limit. Broken `PatientAppointmentForm` component and its `patient/dashboard` route removed.
- Invoices: completed status written as `Appointment::STATUS_COMPLETED` (works in English); section taken from the doctor
  (no more translation-name lookups); cash/credit change on edit keeps exactly one matching ledger row; group invoices run in a
  transaction. Payments validated (description required, 4-digit year); receipts get a default description.
- Notifications: new `notifications.user_type` column (migration) + `Notification::send($user, $msg)`, `forUser()`, `unread()`;
  `$fillable` added (creates were silently failing). Header shows real unread messages (sender names, link to chat) and real
  notifications from `HeaderDataComposer` (detects the active guard). Chat no longer duplicates messages as notifications nor
  marks every notification read. Duplicate `app/Channels/YourCustomDbChannel.php` removed.
- Admissions: patient search fixed; room occupancy recalculated on discharge / bed change / delete. Vital signs no longer write
  an admin id into the `users` FK. Users & roles: status display/filter fixed.
- Pharmacy: new `prescription_items.quantity_dispensed` (migration); dispensing can't exceed what is still owed, status computed
  from all items; refill approval resets it. On-hold list query fixed. Medication saves return the pharmacy manager to his own list.
- Profiles: users without a photo can add one (ray, patient, pharmacy employee & manager); patient profile view path case fixed.
- Routes: resources limited to existing methods (`except`/`only`); removed routes to missing methods (`users-roles` update,
  doctor `attach/detach-existing`, stock `show`); wrong names fixed (`*.login.form`, `404`, `PharmacyManager.profile.show`,
  `doctor.schedule.updatee`, hidden buttons in patient/pharmacy views, forgot-password "back to login").
- Missing views added: admin testimonials create/edit (+ buttons on the index).
- Translations: added `admissions.php`, `messages.php`, `forms.php` and ~90 missing keys; 0 keys used-but-undefined remain.
- Linux portability: `resources/lang/*/Doctors.php` renamed to `doctors.php` (1,165 uses), 232 `asset('dashboard/...')` ->
  `Dashboard/`, view/class name case fixed (`livewire.group_invoices`, `Dashboard.Patients.profile`, `Insurance`), missing assets
  in used views pointed to existing files or removed (0 left). 20 dead template/demo views deleted.
- Hospital contact/print details now come from `config/hospital.php` (`HOSPITAL_*` in `.env`) instead of placeholders and a
  personal e-mail address. Section auto-translation falls back to the original text when Google Translate is unreachable.

**Still open (design decisions / larger work, not errors):** `APP_DEBUG` must be false in production and several controllers
still show `$e->getMessage()`; doctors can open any patient's record; refill counting (`refills_done`) is reset by the doctor's
approval rather than consumed per dispense; mail/SMS are sent synchronously (slow when SMTP/Twilio are unreachable — the `.env`
currently has an inactive Twilio account and SMTP credentials that fail); Laravel 8 is end-of-life; inline CSS / Valex template.

### 7.3 Architecture / maintainability
- 7 separate user tables + guards + observers to fake a global identity (`global_emails`, `global_identifiers`) — a single
  `users` table with roles/permissions (e.g. spatie/laravel-permission) would remove most duplication.
- Mixed patterns (repositories vs fat controllers vs Livewire), 4 booking implementations, 2 invoice table generations,
  duplicated SMS / QR / cancellation code.
- Status values: Arabic strings (appointments), string enums in DB that drift from model constants (prescriptions).
- Money as `double` + tax as strings; no currency handling.
- No queues (mail/SMS/Pusher synchronous), no scheduler (lapsed appointments, expiring stock, refill reminders).
- Front-end: 37 % of the views is copy-pasted inline CSS, Valex template + many CDNs, Windows-only paths (5.7).
- Laravel 8 + PHP 8.2 — Laravel 8 is end-of-life (security fixes ended Jan 2023).

---

## 8. Roadmap ideas toward an international product (for discussion)
1. **Stabilise**: fix 7.1 + 7.2, make paths/translations case-correct, `APP_DEBUG=false`, add feature tests for the critical flows.
2. **Upgrade platform**: Laravel 8 → 11/12 (stepwise), Livewire 2 → 3, Vite, PHP 8.3; move secrets to env; queues + scheduler.
3. **Identity & permissions**: unified users table, roles & permissions, multi-branch / multi-hospital (tenant) support, audit log.
4. **i18n / l10n**: language-neutral status enums, full translation coverage, add more locales (fr, tr, …), proper RTL/LTR
   `dir`, timezone & currency per tenant, date/number formatting.
5. **Domain completeness**: real billing (insurance claims, payment gateways, multi-currency, tax rules per country), inventory
   movements, lab/radiology with structured results (LOINC) & DICOM viewer integration, e-prescriptions, discharge summaries,
   nurse/reception roles, waiting list, notifications center.
6. **Standards & compliance**: HL7 FHIR API, ICD-10/11 coding for diagnoses, HIPAA/GDPR-style privacy (consent, access logs,
   encryption at rest for sensitive fields, data retention).
7. **Product surface**: public website & online booking (no login), patient mobile app via a REST/FHIR API, SMS/WhatsApp
   reminders, telemedicine (video), analytics dashboards.
8. **Front-end**: replace the Valex template with one licensed/own design system, remove inline CSS, bundle assets locally.
9. **Ops**: Docker, CI (tests + static analysis), backups, monitoring, staging environment.
