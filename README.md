# Residential Complaint Management System

> A web-based platform where apartment residents submit and track maintenance and community complaints, while administrators triage, prioritize, assign, and resolve them from a central dashboard.

![PHP](https://img.shields.io/badge/PHP-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?logo=javascript&logoColor=black)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?logo=css3&logoColor=white)
![Course](https://img.shields.io/badge/course-ICT600-lightgrey)

---

## Table of Contents

- [Overview](#overview)
- [Key Features](#key-features)
- [Tech Stack](#tech-stack)
- [Project Architecture](#project-architecture)
- [Database Schema](#database-schema)
- [Getting Started](#getting-started)
- [Usage Walkthrough](#usage-walkthrough)
- [Known Limitations & Roadmap](#known-limitations--roadmap)
- [Contributors / Team](#contributors--team)

---

## Overview

In residential buildings, complaints (leaking pipes, broken lifts, noise, security concerns) often travel through informal channels like chat groups, phone calls, and paper forms. They get lost, nobody knows who is handling them, and residents never hear back.

The **Residential Complaint Management System** replaces that with one structured workflow:

- **Residents** register, submit categorized complaints, and follow each complaint's status and admin notes in real time.
- **Administrators** see every complaint in one place, filter by status, category, or priority, assign a handler, log timestamped progress notes, and manage residents and complaint categories.

Built as a group project for **ICT600**.

---

## Key Features

### Resident Portal
- **Self-registration** with live client-side validation (name, email, phone, unit number) and a real-time **password-strength checklist** (8+ characters, upper/lowercase, number, special character)
- **Secure login** with a 5-minute inactivity timeout and a clear message when a session expires
- **Submit complaints** with a title, category, and description
- **Complaint history** and **detail view** with status badges and the full admin note trail (residents can only open their own complaints)
- **Personal dashboard** with live counts of total, pending, in-progress, and resolved complaints
- **Profile management**: edit name, phone, and unit; change password (requires the current password and enforces complexity rules)
- **Forgot/reset password** via emailed one-time link

### Admin Portal
- **Dashboard** with totals for residents, complaints, and categories, plus Pending / In Progress / Resolved counts and a recent-complaints table
- **Complaint list** with combined filters for **status, category, and priority**
- **Complaint management**: update status, set priority (Low / Medium / High), assign a handler, and append **timestamped notes attributed to the staff member** who wrote them
- **Category management**: add, edit, and delete categories (deletion is blocked while complaints still use the category)
- **Resident management**: search by name, email, phone, or unit; view each resident's complaint count; edit details; and **activate/deactivate accounts**. A deactivated resident is signed out on their next request and cannot log back in.

### Cross-Cutting
- **Role-based access control** separating resident, staff, and admin sessions through reusable guard functions (`requireLogin`, `requireResident`, `requireAdmin`, ...)
- **Passwords hashed** with PHP's `password_hash()` / `password_verify()`
- **Output escaping** through a shared `e()` helper (`htmlspecialchars`) to protect against XSS
- **Email delivery** through PHPMailer over Gmail SMTP (STARTTLS)
- **Responsive design** with a custom CSS design system (CSS variables) and breakpoints at 1024, 992, 768, 480, and 360 px
- **Marketing landing page** (`index.html`) introducing the system's features

---

## Tech Stack

| Layer | Technologies |
|---|---|
| **Backend** | PHP (procedural, `mysqli`), session-based authentication |
| **Database** | MySQL / MariaDB (`utf8mb4`) |
| **Frontend** | HTML5, CSS3 (custom properties, responsive layout), vanilla JavaScript |
| **Email** | [PHPMailer](https://github.com/PHPMailer/PHPMailer) `^7.1` via Composer, Gmail SMTP |
| **Dependency Management** | Composer |
| **Local Environment** | XAMPP / WAMP / MAMP (Apache + MySQL + PHP) |

---

## Project Architecture

The app is a lightweight server-rendered PHP application. Pages are organized by role, and a single controller handles all form submissions.

```
complaint-management-system/
├── index.html                    # Public landing page
├── composer.json                 # PHPMailer dependency
├── test-mail.php                 # Quick SMTP smoke test
│
├── auth/                         # Authentication pages
│   ├── login.html
│   ├── register.html
│   ├── forgot-password.php
│   ├── reset-password.php
│   └── logout.php
│
├── component/
│   ├── navbar-admin.php          # Shared navigation (admin)
│   ├── navbar-resident.php       # Shared navigation (resident)
│   ├── admin/
│   │   ├── dashboard-admin.php
│   │   ├── complaint-list.php    # Filterable complaint table
│   │   ├── complaint-manage.php  # Status / priority / assignment / notes
│   │   ├── categories-list.php
│   │   ├── categories-add.php
│   │   ├── categories-edit.php
│   │   ├── resident-list.php     # Searchable resident directory
│   │   └── resident-edit.php
│   └── resident/
│       ├── dashboard-resident.php
│       ├── complaint-submit.php
│       ├── complaint-history.php
│       ├── complaint-details.php
│       ├── profile.php
│       └── profile-edit.php
│
├── utils/
│   ├── controller.php            # Handles all POST actions (register, login, CRUD, reset)
│   ├── db.php                    # MySQL connection
│   ├── session_check.php         # Timeout, role guards, helper functions
│   ├── mailer.php                # PHPMailer wrapper: sendMail()
│   └── script.js                 # Client-side validation & UI behavior
│
└── styles/
    └── style.css                 # Design system + responsive styles
```

**Request flow**

```
 Browser form ──POST──▶ utils/controller.php ──▶ MySQL ──▶ redirect to role-specific page
                                  │
                                  └──▶ utils/mailer.php (password reset emails)

 Protected pages ──▶ utils/session_check.php (timeout + role guard) ──▶ render PHP view
```

---

## Database Schema

The repository does not ship an SQL dump, so the schema below was **reconstructed from the application's queries**. Adjust column types as needed.

```sql
CREATE DATABASE complaint_management_system CHARACTER SET utf8mb4;
USE complaint_management_system;

CREATE TABLE resident (
  residentID       INT AUTO_INCREMENT PRIMARY KEY,
  full_name        VARCHAR(100) NOT NULL,
  email            VARCHAR(100) NOT NULL UNIQUE,
  phone_number     VARCHAR(15)  NOT NULL,
  password         VARCHAR(255) NOT NULL,
  apartment_no     VARCHAR(20)  NOT NULL,
  resident_status  ENUM('active','inactive') NOT NULL DEFAULT 'active',
  reset_token      VARCHAR(64) NULL
);

CREATE TABLE staff (
  staffID      INT AUTO_INCREMENT PRIMARY KEY,
  full_name    VARCHAR(100) NOT NULL,
  email        VARCHAR(100) NOT NULL UNIQUE,
  password     VARCHAR(255) NOT NULL,
  role         VARCHAR(20)  NOT NULL DEFAULT 'staff',   -- 'admin' grants portal access
  reset_token  VARCHAR(64) NULL
);

CREATE TABLE category (
  categoryID   INT AUTO_INCREMENT PRIMARY KEY,
  name         VARCHAR(100) NOT NULL,
  description  TEXT
);

CREATE TABLE complaint (
  complaintID   INT AUTO_INCREMENT PRIMARY KEY,
  name          VARCHAR(150) NOT NULL,            -- complaint title
  description   TEXT NOT NULL,
  created_date  DATETIME NOT NULL,
  categoryID    INT NOT NULL,
  residentID    INT NOT NULL,
  FOREIGN KEY (categoryID) REFERENCES category(categoryID),
  FOREIGN KEY (residentID) REFERENCES resident(residentID)
);

CREATE TABLE status_complaint (
  staffID       INT NOT NULL,
  complaintID   INT NOT NULL,
  status_name   ENUM('Pending','In Progress','Resolved') NOT NULL DEFAULT 'Pending',
  priority      ENUM('Low','Medium','High') NOT NULL DEFAULT 'Medium',
  assigned_to   VARCHAR(100),
  noted         TEXT,                             -- appended, timestamped notes
  updated_date  DATETIME,
  PRIMARY KEY (complaintID),
  FOREIGN KEY (complaintID) REFERENCES complaint(complaintID),
  FOREIGN KEY (staffID) REFERENCES staff(staffID)
);
```

**Entity relationships**

```
resident 1 ───< complaint >─── 1 category
                    │
                    └── 1:1 ── status_complaint >─── 1 staff
```

---

## Getting Started

### Prerequisites

- [XAMPP](https://www.apachefriends.org/) (or WAMP / MAMP) with **PHP 8.1+**, Apache, and MySQL/MariaDB
- [Composer](https://getcomposer.org/)
- A Gmail account with an [App Password](https://support.google.com/accounts/answer/185833) (only needed for password-reset emails)

### 1. Clone into your web root

The reset-password email link and a few redirects assume the project lives at `http://localhost/complaint-management-system/`, so keep the folder name.

```bash
cd /path/to/xampp/htdocs        # e.g. C:\xampp\htdocs
git clone https://github.com/haziq26166/complaint-management-system.git
cd complaint-management-system
```

### 2. Install PHP dependencies

```bash
composer install
```

This installs PHPMailer into `vendor/`, which `utils/mailer.php` loads.

### 3. Create the database

1. Start **Apache** and **MySQL** from the XAMPP control panel.
2. Open **phpMyAdmin** (`http://localhost/phpmyadmin`).
3. Run the SQL from the [Database Schema](#database-schema) section to create the `complaint_management_system` database and its tables.
4. Seed at least one **category** and one **admin account**. Staff accounts are not created through the UI, so generate a password hash and insert it directly:

   ```bash
   php -r "echo password_hash('YourStrongPassword1!', PASSWORD_DEFAULT);"
   ```

   ```sql
   INSERT INTO staff (full_name, email, password, role)
   VALUES ('System Admin', 'admin@example.com', '<paste-hash-here>', 'admin');

   INSERT INTO category (name, description) VALUES
     ('Maintenance', 'Plumbing, electrical, and repairs'),
     ('Noise', 'Noise disturbances'),
     ('Security', 'Safety and access concerns');
   ```

### 4. Configure the database connection

Edit `utils/db.php` if your MySQL credentials differ from the XAMPP defaults:

```php
$host     = "localhost";
$username = "root";
$password = "";
$database = "complaint_management_system";
```

### 5. Configure email (password reset)

This project has no `.env` file. Mail credentials are set directly in `utils/mailer.php`:

```php
$mail->Username = 'your.address@gmail.com';
$mail->Password = 'your-16-char-app-password';
```

> **Do not commit real credentials.** Use a throwaway Gmail account with an App Password, and never push your own password to a public repository. Moving these values into environment variables is on the [roadmap](#known-limitations--roadmap).

Then verify your SMTP setup by editing the recipient in `test-mail.php` and visiting `http://localhost/complaint-management-system/test-mail.php`.

If you host the project somewhere other than `localhost/complaint-management-system`, also update the `$resetLink` base URL in the `forgot_password` block of `utils/controller.php`.

### 6. Run the app

Open **http://localhost/complaint-management-system/** in your browser.

---

## Usage Walkthrough

1. **Resident:** click *Register*, create an account, log in, and choose *Submit Complaint*. Pick a category and describe the issue.
2. **Admin:** log in with the staff account you seeded. Open *Complaints*, filter by status, category, or priority, and click a complaint to manage it.
3. **Admin:** set the status to *In Progress*, choose a priority, enter a handler's name, and add a note. The note is saved with a timestamp and your name.
4. **Resident:** open *Complaint History* → *Details* to see the new status and the admin's note trail.
5. **Admin:** use *Residents* to search, edit, or deactivate accounts, and *Categories* to maintain the category list.

---

## Known Limitations & Roadmap

Candid notes on the current state of the project and where it could improve:

- [ ] **Migrate to prepared statements (PDO or `mysqli_prepare`).** Queries currently build SQL strings with `mysqli_real_escape_string`; parameterized queries are the more robust defense against SQL injection.
- [ ] **Authorize every action in the controller.** Admin actions in `controller.php` (update complaint, manage categories and residents) should re-check `requireAdmin()` server-side instead of relying on page-level guards.
- [ ] **Externalize secrets.** Move SMTP and database credentials to environment variables or an ignored config file, and rotate any credential that was ever committed.
- [ ] **Expire password-reset tokens.** Tokens are cleared after use, but there is no expiry timestamp even though the email says 24 hours. Server-side password-strength and confirmation checks on reset would also help.
- [ ] **Add CSRF protection** to all POST forms.
- [ ] **Add a database migration/seed script** (`schema.sql`) and an in-app way to create staff accounts.
- [ ] **Notifications.** Email residents when their complaint status changes (the mailer is already in place).
- [ ] **Attachments, pagination, and reporting** (photo evidence, paged lists, exportable summaries).

---

## Contributors / Team

This project was built as a **group project** for **ICT600**. Replace the placeholders below with your team's details.

| Name | Role / Contribution | GitHub |
|---|---|---|
| **[Member Name]** | [e.g. Authentication & session management] | [@username](https://github.com/username) |
| **[Member Name]** | [e.g. Admin dashboard & complaint workflow] | [@username](https://github.com/username) |
| **[Member Name]** | [e.g. Resident portal & profile features] | [@username](https://github.com/username) |
| **[Member Name]** | [e.g. UI/UX design, CSS & front-end validation] | [@username](https://github.com/username) |

Repository owner: [@haziq26166](https://github.com/haziq26166)

---

## License

[Add a license, e.g. MIT, or remove this section.]
