# Logistics Pro - Management System (PHP)

An enterprise-grade, full-stack PHP logistics management application featuring **Truck Loading**, **Inventory**, **GST Billing**, **HRMS**, **Payroll**, **Payment Notifications**, and a **1-Time Project Approvals Workflow**.

---

## 🌟 Key Features

1. **Dashboard Overview**:
   - Matches the reference design with high visual fidelity.
   - Top 4 KPI Metrics: Total Inventory Items, Trucks Today, Today's Billing (₹2,50,000), Total Employees.
   - Interactive Bezier Curve Chart for Billing vs Expenses with Net Profit calculator.
   - Quick Access cards to all 6 modules.
   - Live Recent Activities feed with real-time timestamps.
   - 1-Time Pending Approvals widget with direct Approve ($\checkmark$) and Reject ($\times$) action buttons.
   - Payment Notifications feed with status tags (`Received`, `Pending`, `Overdue`).

2. **Inventory Management**:
   - Item catalogue with SKU codes, categories, unit prices, weights, and volumes.
   - Stock-In / Stock-Out adjustments with reason logging.
   - Real-time stock valuation and automatic low-stock alerts.

3. **Truck Loading & Cargo Simulator**:
   - Real-time 2D/3D Container Bay simulation.
   - Weight & Volume capacity bars with auto-overload detection.
   - Automated Gate Pass & Loading Manifest creation linked with the Approval workflow.

4. **Billing & Invoicing**:
   - GST-compliant invoice generator (18%, 12%, 5%, 0%).
   - Itemized line items, auto subtotal, taxes, discounts, and grand totals.
   - Built-in printable / downloadable Tax Invoice preview.
   - Payment recording with instant notifications.

5. **HRMS (Human Resource Management System)**:
   - Complete employee and driver directory with contact info and roles.
   - Daily attendance tracker with overtime hours.
   - Leave application linked directly to the 1-Time approval engine.

6. **Payroll Engine**:
   - Automatic calculation of Basic, HRA (20%), Allowances, PF deduction (12%), and TDS.
   - 1-Click monthly batch payroll generation.
   - Official printable employee payslips with breakdown.

7. **Payment Notifications**:
   - Real-time notification center for received payments, pending client receivables, and overdue invoices.
   - Send payment reminders and mark as read features.

8. **1-Time Project Approvals System**:
   - Strict 1-Time decision policy across Purchase Orders, Gate Passes, Expenses, Leaves, and Payroll.
   - Once approved or rejected, the record is locked with audit timestamp and administrator remarks.

---

## 🚀 How to Run Locally

1. Open your terminal in the project folder:
   ```bash
   cd c:\Users\HP\Desktop\truck-loading
   ```

2. Start PHP's built-in development server:
   ```bash
   php -S 127.0.0.1:8000 -t public
   ```

3. Open your browser and navigate to:
   [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## 📁 Project Structure

```
truck-loading/
├── app/
│   ├── Core/
│   │   ├── App.php             # Router and front controller
│   │   ├── Database.php        # JSON database engine with atomic persistence
│   │   ├── Controller.php      # Base controller
│   │   ├── Session.php         # Session and flash alerts
│   │   └── Helper.php          # Currency (₹ INR), date, badges
│   ├── Controllers/
│   │   ├── DashboardController.php
│   │   ├── InventoryController.php
│   │   ├── TruckLoadingController.php
│   │   ├── BillingController.php
│   │   ├── HrmsController.php
│   │   ├── PayrollController.php
│   │   ├── NotificationController.php
│   │   └── ApprovalController.php
│   ├── Models/
│   │   ├── InventoryModel.php
│   │   ├── TruckModel.php
│   │   ├── BillingModel.php
│   │   ├── HrmsModel.php
│   │   ├── PayrollModel.php
│   │   ├── NotificationModel.php
│   │   ├── ApprovalModel.php
│   │   └── ActivityModel.php
│   └── Views/
│       ├── layouts/            # Header, Sidebar, Topbar, Footer, Modals
│       ├── dashboard/          # Main dashboard matching reference UI
│       ├── inventory/          # Stock catalogue & adjustments
│       ├── truck_loading/      # Visual bay & Gate pass manifests
│       ├── billing/            # Invoices & printable tax invoice
│       ├── hrms/               # Employee directory & attendance
│       ├── payroll/            # Monthly salary sheet & payslips
│       ├── notifications/      # Payment alerts & reminders
│       └── approvals/          # 1-Time project approvals hub
├── data/                       # JSON persistence datastore
└── public/
    ├── index.php               # Front controller
    └── assets/
        ├── css/style.css       # Custom design system
        └── js/
            ├── app.js          # Interactive modals & filters
            ├── charts.js       # Business Overview bezier chart
            └── truck-loader.js # Truck cargo simulator engine
```
