# Task & Order Management System

A sleek, modern PHP-based web application for managing daily workflow tasks and customer orders. Built with dynamic interactive tables, smooth layouts, and a secure local database.

## 🚀 Features

### 📋 Task Management
- **Add New Tasks**: Create tasks with titles, detailed descriptions, deadlines, and file attachments (images, documents).
- **Interactive Checklist**: Toggle tasks as complete or pending with dynamic AJAX updates (no full page reload).
- **Status Highlighting**: Colors change dynamically depending on the task state:
  - 🔴 **Red**: Overdue tasks
  - 🟡 **Yellow**: Tasks due today
  - 🔵 **Blue**: Tasks due in the next 4 days
- **Filter pills**: Quickly filter tasks by all, pending, or completed states.
- **Search & Pagination**: Fully integrated search and pagination for high performance.

### 📦 Order Management
- **Dynamic Order Rows**: Add, edit, or remove multiple product rows dynamically using AJAX and JavaScript before saving.
- **Auto-Calculations**: Quantities, unit prices, and row totals calculate automatically in real-time.
- **Order Tracking**: Detailed listing of customer orders with creation/update logs and soft-deletion.

### 🔒 User Authentication
- Secure login system comparing database records to log users in.
- Sessions for tracking logged-in state.

---

## 🛠️ Tech Stack
- **Backend**: PHP (Object-Oriented & Procedural mixes)
- **Frontend**: HTML5, Vanilla CSS, Tailwind CSS (via CDN), jQuery, DataTables
- **Database**: MariaDB / MySQL
- **Local Server**: XAMPP (Apache & MySQL)

---

## 💻 Local Installation (XAMPP)

### Prerequisites
- Install [XAMPP](https://www.apachefriends.org/) on your computer.

### Step 1: Clone or Copy Files
Place the project folder inside your XAMPP's local server directory (usually `C:\xampp\htdocs\taskapp`).

### Step 2: Set Up Database in phpMyAdmin
1. Open the **XAMPP Control Panel** and start **Apache** and **MySQL**.
2. Open your browser and go to: `http://localhost/phpmyadmin/`
3. Click on the **Import** tab at the top.
4. Click **Choose File** and select `loginform.sql` located in your project root directory.
5. Click **Import** (or **Go**) at the bottom.
   *(This will automatically create the database `loginform`, set up all 4 tables, and insert a default admin user).*

### Step 3: Run the App
Go to `http://localhost/taskapp/` in your browser.

### 🔑 Default Credentials
- **Username**: `admin`
- **Password**: `admin`

---

## 📁 Project Structure
```text
├── includes/          # Header, footer, and sidebar components
├── orders/            # Order creation, listing, updating, and saving scripts
│   └── js/            # Client-side order row handling
├── pages/             # Task listing, creation, and editing pages
├── uploads/           # Storage folder for task attachments (ignored in git)
├── db.php             # Database connection configuration
├── loginform.sql      # Database structure dump and admin user seed
├── .gitignore         # Prevents uploads and OS junk from being tracked
└── README.md          # Project documentation
```
