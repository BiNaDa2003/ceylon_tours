# Ceylon Tours - Tour Package Booking System

[![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![Architecture](https://img.shields.io/badge/Architecture-MVC--Inspired-orange?style=for-the-badge)](#-directory-structure)

A modern, responsive, and robust Tour Package Booking System developed as a full-stack university Web Application Development assignment using native PHP (OOP) and MySQL.

---

## 🚀 Features

### 👤 Customer Features
* **Browse & Search:** View featured tour packages, search by keyword or destination, and filter dynamically by maximum price.
* **Detailed Package Views:** Comprehensive details for each tour, including amenities, highlights, and real-time available slots.
* **Dynamic Booking System:** Automatic price calculation based on pax count, with strict date and availability validation.
* **Customer Dashboard:** Track upcoming and past reservations with instant cancellation options for pending bookings.
* **Secure Authentication:** User registration and login powered by native password hashing.
* **Contact & Inquiries:** Direct contact form for customer support and inquiry management.

### 🛡️ Admin Features
* **Protected Admin Panel:** Role-based access control guarding management endpoints.
* **Dashboard Analytics:** High-level overview of total packages, bookings, customer statistics, and overall revenue.
* **Package Management:** Full CRUD operations (Create, Read, Update, Delete) for tour offerings.
* **Booking Management:** Review customer reservations, update booking statuses (*Pending*, *Confirmed*, *Cancelled*), and manage records.
* **Customer Management:** View registered users and manage account statuses.

---

## 💻 Tech Stack

* **Frontend:** HTML5, CSS3, JavaScript (ES6+), Bootstrap 5, FontAwesome, Google Fonts
* **Backend:** PHP 8.1+ (Object-Oriented Programming)
* **Database:** MySQL via PDO (PHP Data Objects) with prepared statements
* **Architecture:** Custom Lightweight MVC (Models, Views, Controllers, Front Controller routing)

---

## 📁 Directory Structure

```text
project-root/
├── assets/             # CSS, JS, vendor libraries, and uploaded images
├── config/             # Database connection setup and environment configs
├── controllers/        # Business logic bridging models and views
├── database/           # SQL schema exports and seed data
├── includes/           # Reusable UI partials (header, footer, admin sidebar)
├── models/             # Database entities and CRUD operations
├── views/              # Frontend public pages and admin layout views
│   ├── admin/          # Back-office admin views
│   └── public/         # Customer-facing views
├── index.php           # Single entry point & routing handler
└── README.md           # Project documentation
