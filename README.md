# 🏨 Icon Venue & Suites - Inventory Management System

A comprehensive Laravel-based inventory management system designed specifically for hotel operations, featuring advanced batch tracking with FIFO/FEFO logic, depreciation tracking, borrowing/return workflows, and automated expiry handling.

## 📋 Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage Guide](#usage-guide)
- [Automated Tasks](#automated-tasks)
- [System Architecture](#system-architecture)
- [Troubleshooting](#troubleshooting)

> **📊 For a detailed feature completeness assessment, see [FEATURE_COMPLETENESS.md](FEATURE_COMPLETENESS.md)**

---

## ✨ Features

### Core Inventory Management
- **Dual Item Types**: Consumables (soap, towels, toiletries) and Non-Consumables (laptops, furniture, equipment)
- **Batch Tracking**: Every stock replenishment creates a batch with unique batch number, location, and optional expiry date
- **Hierarchical Categories**: Unlimited nested categories for organized inventory structure with automatic path generation
- **Department Assignment**: Track which department owns or manages each item
- **Item Images**: Visual identification with image upload support (JPEG, PNG, GIF, WebP)
- **Soft Deletes**: Archive items and categories without losing historical data

### Advanced Inventory Logic

#### FIFO/FEFO System
- **FEFO (First Expired, First Out)**: For consumables - automatically uses items expiring soonest
- **FIFO (First In, First Out)**: For non-consumables - automatically uses oldest items first
- **Mixed Batch Handling**: Intelligently handles batches with and without expiry dates
- **Automatic Expiry Detection**: Daily scheduled task marks expired batches as spoiled
- **Batch Transparency**: Modal shows which batches will be used/returned before confirming transaction

#### Asset Depreciation Tracking
- **Depreciation Methods**: 
  - **Straight Line**: Even depreciation over useful life
  - **Declining Balance**: Accelerated depreciation (double declining balance method)
  - **None**: For items that don't depreciate
- **Automatic Calculations**: Real-time book value calculations
- **Batch-Level Tracking**: Each batch can have different depreciation settings
- **Financial Reporting**: Total asset value reflects current book values after depreciation
- **Monthly Updates**: Scheduled recalculation of all depreciation values
- **Fields Tracked**: Purchase price, purchase date, useful life (years), salvage value, depreciation rate

#### Borrowing & Returns
- **Borrow Workflow**: Staff can borrow items (typically non-consumables)
- **Automatic Batch Selection**: System picks batches using FIFO/FEFO logic
- **Return to Original Batch**: Items return to the exact batch they were borrowed from
- **Partial Returns**: Support for returning items in multiple transactions
- **Borrower Tracking**: Records borrower name, department, and notes

### Transaction Management
- **Transaction Types**: Replenish, Borrow, Return, Disposal, Expiry, Spoiled
- **Reference Numbers**: Auto-generated unique references for audit trails (e.g., BOR-001, RET-002)
- **Full History**: Complete transaction log with user, date, batch information, and notes
- **Excel Exports**: Export transactions, inventory, batches, borrowed items with full formatting
- **Transaction Clearing**: Admin can clear old transaction history while preserving audit logs

### User Management & Security
- **Role-Based Access**: Admin (full access) and Staff (limited access)
- **Two-Factor Authentication (2FA)**: Email-based verification codes
- **Trusted Devices**: Remember devices to skip 2FA on trusted machines
- **Profile Pictures**: User avatars with default color-coded initials
- **Email Notifications**: Welcome emails with credentials for new users
- **Failed Login Logging**: Security monitoring of login attempts
- **Activity Logs**: Complete audit trail of all system changes with before/after values

### Notifications System
- **Real-Time Notifications**: In-app notification bell with unread count
- **Notification Types**:
  - 🔴 Low Stock Alerts
  - 🟡 Expiring Soon (batches expiring within 30 days)
  - 🔴 Expired Batches
  - 🔵 New Borrows
  - 🔵 Items Returned
  - 🟢 New Batches Created
- **Admin Notifications**: Admins receive all notifications, staff see their own
- **Deduplication**: Smart daily deduplication to prevent notification spam
- **Mark All as Read**: Quick action to clear all notifications

### Reporting & Analytics
- **Dashboard Stats Cards**:
  - Total Items count
  - Low Stock Items count
  - Active Users count
  - Total Asset Value (with depreciation)
  - Recent Activity feed
  - Alerts for low stock and expiring items
- **Transaction Reports**:
  - Summary statistics (total items, categories, transactions)
  - Transaction breakdown by type
  - Recent transactions table
  - Export to Excel
- **Inventory Reports**:
  - Complete item listing with stock levels
  - Category-wise breakdown
  - Valuation with current book values
  - Export functionality
- **Batch Management**:
  - View all batches with expiry tracking
  - Batch details with depreciation calculations
  - Export batch data

### Automated Background Tasks
- **Daily Tasks**:
  - Check and mark expired consumable batches (daily)
  - Reconcile item quantities with batch totals (daily at 2 AM)
  - Generate expiring soon notifications (daily)
  - Generate expired batch notifications (daily)
- **Monthly Tasks**:
  - Update depreciation calculations for all assets (monthly)
- **On-Demand Commands**:
  - `php artisan consumables:check-expired` - Check for expired items
  - `php artisan depreciation:update` - Recalculate depreciation
  - `php artisan inventory:reconcile-quantities` - Fix quantity mismatches
  - `php artisan categories:update-hierarchy` - Rebuild category paths

### Additional Features
- **Search & Filtering**: Advanced search across items, categories, transactions
- **Pagination**: Efficient pagination (15 items per page)
- **Responsive Design**: Mobile-friendly interface with Tailwind CSS
- **Glass Morphism UI**: Modern gradient design with hotel branding colors
- **Alpine.js Interactivity**: Smooth modal interactions and dynamic updates
- **Unit Flexibility**: Support for custom units (pcs, sets, bottles, bags, rolls, boxes, etc.)
- **Minimum Stock Levels**: Set thresholds for low stock alerts
- **Profile Management**: Users can update profile, change password, manage 2FA