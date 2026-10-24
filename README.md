# MedCare

MedCare is a clinic appointment management system built with PHP, MySQL, HTML, and CSS.

The project has two main sides: a patient area where users can register, log in, browse doctors, book appointments, and track their appointment status, and an admin area used to manage doctors, patients, and appointments.

This project was built as part of my PHP and MySQL training at Orange Coding Academy.

## Features

### Patient

- Create a new patient account
- Log in and log out using sessions
- Browse available doctors and their specializations
- View doctor images and contact information
- Book an appointment with a selected doctor
- Add the appointment date, time, and reason for the visit
- View all personal appointments
- Track appointment status: Pending, Confirmed, Completed, or Cancelled
- View dashboard statistics and the next upcoming appointment

### Admin

- Secure admin login
- View dashboard statistics
- View the latest appointments
- Add doctors
- Upload doctor images
- Edit doctor information and replace an existing image
- Delete doctors and their uploaded images
- View registered patients
- View all appointments
- Change appointment status

## Technologies Used

- PHP
- MySQL
- PDO
- HTML5
- CSS3
- XAMPP / Apache
- phpMyAdmin

No frontend or backend frameworks are used in this project.

## Project Structure

```text
clinic-appointment-system/
│
├── admin/
│   ├── appointments.php
│   ├── dashboard.php
│   ├── doctors.php
│   ├── login.php
│   └── patients.php
│
├── classes/
│   ├── admin.php
│   ├── appointment.php
│   ├── database.php
│   ├── doctor.php
│   ├── patient.php
│   └── user.php
│
├── components/
│   ├── footer.php
│   ├── header.php
│   └── navbar.php
│
├── patient/
│   ├── all-appointments.php
│   ├── book-appointments.php
│   ├── homepage.php
│   └── patients-doctors.php
│
├── styling/
│   ├── clinic-doctor.jpg
│   └── style.css
│
├── uploads/
│   └── doctors/
│
├── Database Schema.pdf
├── index.php
├── login.php
├── logout.php
├── register.php
└── README.md
```

## Database Structure

The project uses a MySQL database named:

```text
clinic_db
```

The main tables are:

### patients

Stores patient accounts and contact information.

Main fields include:

- `patient_id`
- `first_name`
- `last_name`
- `email`
- `password`
- `phone`
- `created_at`

### doctors

Stores doctor information.

Main fields include:

- `doctor_id`
- `first_name`
- `last_name`
- `specialization`
- `phone`
- `email`
- `image`

The `image` column stores the uploaded image filename. The actual image file is stored inside `uploads/doctors/`.

### appointments

Connects patients with doctors and stores appointment details.

Main fields include:

- `appointment_id`
- `patient_id`
- `doctor_id`
- `appointment_date`
- `appointment_time`
- `status`
- `reason`
- `created_at`

`patient_id` and `doctor_id` are foreign keys that connect the appointment to a patient and a doctor.

### admins

Stores administrator login information.

Main fields include:

- `admin_id`
- `name`
- `email`
- `password`

Passwords are stored as hashes and checked using PHP's `password_verify()` function.

A copy of the database design is also included in `Database Schema.pdf`.

## How the Project Works

A patient creates an account and logs in. After logging in, the patient can browse the list of doctors and select a doctor to book an appointment.

When an appointment is created, it is connected to both the logged-in patient and the selected doctor. New appointments start with a `Pending` status.

The admin can view all appointments and change their status to `Pending`, `Confirmed`, `Completed`, or `Cancelled`. The updated status is then visible to the patient.

## Running the Project Locally

### 1. Requirements

Make sure you have the following installed:

- XAMPP or another PHP/Apache environment
- MySQL
- phpMyAdmin
- PHP with PDO MySQL enabled

### 2. Place the Project in XAMPP

Copy the project folder into your XAMPP `htdocs` directory.

For example:

```text
C:\xampp\htdocs\clinic-appointment-system
```

### 3. Start Apache and MySQL

Open the XAMPP Control Panel and start:

```text
Apache
MySQL
```

### 4. Create the Database

Open phpMyAdmin and create a database named:

```text
clinic_db
```

Then create the `patients`, `doctors`, `appointments`, and `admins` tables based on the included `Database Schema.pdf`.

Make sure the foreign key relationships are created between:

```text
appointments.patient_id -> patients.patient_id
appointments.doctor_id  -> doctors.doctor_id
```

### 5. Check the Database Connection

The database connection is located in:

```text
classes/database.php
```

The current project configuration is:

```php
private $host = "localhost";
private $user = "root";
private $password = "";
private $dbname = "clinic_db";
private $port = "3307";
```

If your MySQL server is using the default port `3306`, change the port in `classes/database.php` before running the project.

If your MySQL username or password is different, update those values as well.

### 6. Add an Admin Account

The project does not include an admin registration page, so an admin account needs to exist in the `admins` table before the admin dashboard can be used.

The admin password must be hashed because the login system uses `password_verify()`.

One simple way to generate a password hash is:

```bash
php -r "echo password_hash('your_password', PASSWORD_DEFAULT);"
```

Copy the generated hash and use it as the value of the `password` column when adding the admin account in phpMyAdmin.

### 7. Open the Website

Open the following address in your browser:

```text
http://localhost/clinic-appointment-system/
```

Patient login:

```text
http://localhost/clinic-appointment-system/login.php
```

Admin login:

```text
http://localhost/clinic-appointment-system/admin/login.php
```

## Main PHP Concepts Used

This project was built to practice the main PHP and MySQL concepts covered during training, including:

- PHP forms
- `GET` and `POST`
- Sessions
- Form validation
- Object-oriented PHP
- Classes and constructors
- PDO database connections
- Prepared statements
- Password hashing and verification
- CRUD operations
- SQL joins
- Primary and foreign keys
- File uploads using `$_FILES`
- `move_uploaded_file()`
- File deletion using `unlink()`

## Doctor Image Uploads

Doctor images are uploaded from the admin doctors page.

The form uses:

```html
enctype="multipart/form-data"
```

PHP receives the uploaded image through `$_FILES`, validates the file extension, gives the image a filename, and moves it into:

```text
uploads/doctors/
```

Only the filename is stored in the `doctors` table.

When a doctor image is replaced, the old image is removed from the uploads folder. When a doctor is deleted, their image is also removed if it exists.

## Appointment Relationships

The `appointments` table is the central table connecting patients and doctors.

```text
Patient
   |
   | patient_id
   |
Appointment
   |
   | doctor_id
   |
Doctor
```

SQL joins are used when appointment pages need information such as the patient's name, doctor's name, or doctor's specialization.

## Notes

- The project is designed to run locally with XAMPP.
- Doctor images must be stored inside `uploads/doctors/`.
- Patient and admin authentication use PHP sessions.
- Patient passwords and admin passwords should always be stored as password hashes.
- Database queries use PDO prepared statements.
- The project uses plain PHP, HTML, and CSS without external frameworks.

## Author

Developed by Zaid Alqaryouti as part of the PHP and MySQL training at Orange Coding Academy.
