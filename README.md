# 🏋️ Hermes Fitness - Gym Management System (Refactored)

[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3.0-purple.svg)](https://getbootstrap.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

A complete refactor of the Gym Management System with role-based dashboard architecture, modern UI, and comprehensive CRUD operations.

## 🎯 Live Demo

**Application URL:** [https://work-1-vgcwqtbzyqxbjtri.prod-runtime.all-hands.dev](https://work-1-vgcwqtbzyqxbjtri.prod-runtime.all-hands.dev)

### 🔐 Test Credentials

| Role | Email | Password | Access |
|------|-------|----------|---------|
| **Admin** | admin@hermesfitness.com | password | Full system access |
| **Trainer** | madhava@hermesfitness.com | password | Workout & booking management |
| **Trainer** | sarah@hermesfitness.com | password | Workout & booking management |
| **Member** | john@example.com | password | Booking & payment access |
| **Member** | emily@example.com | password | Booking & payment access |

## ✨ Features

### 🔐 Authentication & Authorization
- **Role-based access control** (Admin, Trainer, Member)
- **Secure login/logout** with session management
- **Customer self-registration** with profile creation
- **Admin-managed** trainer and member accounts

### 👨‍💼 Admin Dashboard
- **User Management**: Create, edit, delete trainers and members
- **Payment Tracking**: View all transactions and generate reports
- **System Analytics**: Dashboard with statistics and insights
- **Report Generation**: PDF/CSV export functionality

### 🏃‍♂️ Trainer Dashboard
- **Workout Management**: Full CRUD for exercises and routines
- **Booking Calendar**: Day-view calendar for client appointments
- **Client Notifications**: Real-time booking alerts
- **Progress Tracking**: Monitor client activities

### 👤 Member Dashboard
- **Trainer Booking**: Select trainers with automatic time slot assignment
- **Payment Gateway**: Dummy payment system for testing
- **Booking History**: View past and upcoming sessions
- **Profile Management**: Update personal information

## 🏗️ Architecture

### Backend Structure (Laravel)
```
app/Http/Controllers/
├── Admin/
│   ├── DashboardController.php     # Admin overview & analytics
│   ├── AccountController.php       # User management (CRUD)
│   ├── PaymentController.php       # Payment tracking & reports
│   └── ReportController.php        # System reporting
├── Trainer/
│   ├── DashboardController.php     # Trainer overview
│   ├── WorkoutController.php       # Exercise management (CRUD)
│   └── BookingController.php       # Booking management & calendar
└── Customer/
    ├── DashboardController.php     # Member overview
    ├── BookingController.php       # Trainer booking system
    └── PaymentController.php       # Payment processing
```

## 🚀 Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- SQLite (or MySQL/PostgreSQL)

### Quick Start

1. **Clone the repository**
   ```bash
   git clone https://github.com/Magicwander/WebProject_GymManagementSystem_Part02_Refactored.git
   cd WebProject_GymManagementSystem_Part02_Refactored
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Start the server**
   ```bash
   php artisan serve
   ```

6. **Access the application**
   - Open your browser to `http://localhost:8000`
   - Use the test credentials above to login

## 💳 Payment System

The system includes a **dummy payment gateway** for testing purposes:

- **Test Card Numbers**: Any 16-digit number (e.g., 4111111111111111)
- **Expiry Date**: Any future date
- **CVV**: Any 3-digit number
- **Automatic Booking Confirmation** after successful payment

## 📅 Booking System

### Automatic Time Slot Assignment
- **Business Hours**: 6:00 AM - 10:00 PM
- **Session Duration**: 1 hour per booking
- **Conflict Prevention**: System prevents double-booking
- **Calendar Integration**: Trainers can view schedules in day-view format

---

**Built with ❤️ by CodiNgRaBBit**
