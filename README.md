# Hospital Management System

A web-based hospital administration system built with **Laravel 8, PHP and MySQL**.
Bachelor graduation project – Computer Systems Engineering, Palestine Technical College (Deir El-Balah), 2024–2025.

The system keeps a hospital's patients, staff, appointments, services and billing in one database, with a separate dashboard for each role.

## Roles and dashboards

| Role | What they can do |
|---|---|
| **Admin** | Statistics dashboard; manage sections, doctors, patients, ray and laboratory staff, pharmacy staff, services and service packages, rooms and beds, patient admissions, appointments, invoices and accounts |
| **Doctor** | View patients and appointments, record diagnoses, request lab tests and X-rays, write prescriptions, set working days and breaks |
| **Patient** | Register and log in, book and track appointments, view medical history, invoices and prescriptions |
| **Ray employee** | Handle X-ray requests from doctors |
| **Laboratory employee** | Handle lab test requests |
| **Pharmacy manager / employee** | Manage medication stock and prescriptions |

## Main features

- **Patient records**: registration, medical history, chronic diseases, vital signs and admissions
- **Appointments**: booking, confirmation, cancellation, waiting list and doctor schedules
- **Billing**: single-service and package invoices, receipts, payments and patient accounts
- **Pharmacy**: medications, stock levels and prescriptions
- **Rooms and beds**: availability and patient admissions
- **Notifications and chat** between users (Livewire, Pusher)
- **Bilingual interface** (Arabic / English) with translatable content
- **Secure login** with a separate guard for each role

## Tech stack

- PHP, Laravel 8, MySQL
- Blade, Livewire, JavaScript, Bootstrap
- astrotomic/laravel-translatable, mcamara/laravel-localization
- simple-qrcode, Pusher

## Run locally

```bash
git clone https://github.com/yousefrajab/Hospital_Sys.git
cd Hospital_Sys
composer install
npm install && npm run dev
cp .env.example .env      # then set your database name, user and password
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

## Database

The database has more than 50 tables. `DB.txt` lists the main tables and their columns.

## Author

**Yousef R. A. Al Khateeb** · [Portfolio](https://yousefrajab.github.io/portfolio/) · [LinkedIn](https://www.linkedin.com/in/yousef-al-khateeb) · yousefrajab2018@gmail.com
