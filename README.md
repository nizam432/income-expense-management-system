# 💰 Income, Expense & Loan Management System

A complete financial management solution built with Laravel to manage income, expenses, and loan transactions efficiently.

---

## 📌 Overview

The **Income, Expense & Loan Management System** helps individuals and businesses track financial activities in one place.

Users can:

* Record income and expenses
* Manage loan transactions
* Monitor profit/loss
* Generate reports
* Track payment history

This software is designed to simplify financial decision-making and improve money management.

---

## 🚀 Features

### 💵 Income Management

* Add income records
* Edit & delete income
* Category-wise income tracking
* Daily / Monthly summaries

### 💸 Expense Management

* Add expense records
* Edit & delete expenses
* Expense categories
* Spending summaries

### 💳 Loan Management

* Add loan (Given / Taken)
* Borrower & lender management
* Installment tracking
* Payment history
* Due tracking

### 📊 Dashboard

* Total Income
* Total Expense
* Profit / Loss overview
* Recent transactions

### 🧾 Reports

* Date range filter
* Category-based reports
* Financial summary reports

### 🔎 Search & Filter

* Search by date
* Search by category
* Transaction filtering

### 🔐 Authentication & Access Control

* User login system
* Registration
* Role-based access control
* Middleware security

---

## 🛠️ Tech Stack

### Frontend

* HTML5
* CSS3
* JavaScript
* Bootstrap

### Backend

* PHP
* Laravel Framework

### Database

* MySQL

---

## ⚙️ Installation Guide

### 1. Clone Repository

```bash id="x7l4gk"
git clone https://github.com/nizam432/income-expense-management-system.git
cd income-expense-management-system
```

### 2. Install Dependencies

```bash id="4s3bn2"
composer install
```

### 3. Setup Environment File

```bash id="k1pq90"
cp .env.example .env
php artisan key:generate
```

### 4. Database Setup

Create a new database in phpMyAdmin:

```text id="ysb2gh"
income_expense_db
```

Import database:

* Open phpMyAdmin
* Select database
* Click **Import**
* Choose `database.sql`
* Click **Go**

Update `.env` file:

```env id="qk1m8d"
DB_DATABASE=income_expense_db
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Run Project

```bash id="2p8dqa"
php artisan serve
```

Open browser:

```text id="v9dm0r"
http://127.0.0.1:8000
```

> ⚠️ Note: Do not run `php artisan migrate` if you already imported `database.sql`

---

## 🔑 Demo Login

Email: admin@example.com
Password: 123456


## 📂 Project Modules

* Income Module
* Expense Module
* Loan Module
* Reports Module
* Authentication Module
* Dashboard Module

---

## 📸 Screenshots

Add project screenshots here.

Example:

```text id="ny7e6m"
assets/screenshots/dashboard.png
assets/screenshots/report.png
```

---

## 📌 Project Status

✅ Completed
✅ Production Ready
✅ Active Development Supported

---

## 🤝 Contribution

Contributions, issues, and feature requests are welcome.

---

## 👨‍💻 Author

**Nizam Uddin**

* GitHub: https://github.com/nizam432
* LinkedIn: https://linkedin.com/in/nizam432
* Email: nizam.fci@gmail.com

---

## 📄 License

This project is open-source and available under the MIT License.
