<?php
// Logistics Pro - Modern Enterprise Web Application

// Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = dirname(__DIR__) . '/app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

use App\Core\App;
use App\Controllers\DashboardController;
use App\Controllers\InventoryController;
use App\Controllers\TruckLoadingController;
use App\Controllers\BillingController;
use App\Controllers\HrmsController;
use App\Controllers\PayrollController;
use App\Controllers\NotificationController;
use App\Controllers\ApprovalController;

$app = new App();

// 1. Dashboard Routes
$app->get('/', [DashboardController::class, 'index']);

// 2. Inventory Routes
$app->get('/inventory', [InventoryController::class, 'index']);
$app->post('/inventory/store', [InventoryController::class, 'store']);
$app->post('/inventory/update', [InventoryController::class, 'update']);
$app->post('/inventory/adjust', [InventoryController::class, 'adjust']);
$app->post('/inventory/delete', [InventoryController::class, 'delete']);
$app->get('/api/inventory', [InventoryController::class, 'apiList']);

// 3. Truck Loading Routes
$app->get('/truck-loading', [TruckLoadingController::class, 'index']);
$app->post('/truck-loading/store-truck', [TruckLoadingController::class, 'storeTruck']);
$app->post('/truck-loading/create-manifest', [TruckLoadingController::class, 'createManifest']);
$app->post('/truck-loading/update-status', [TruckLoadingController::class, 'updateStatus']);

// 4. Billing Routes
$app->get('/billing', [BillingController::class, 'index']);
$app->post('/billing/store', [BillingController::class, 'store']);
$app->post('/billing/mark-paid', [BillingController::class, 'markPaid']);
$app->get('/api/billing/{id}', [BillingController::class, 'viewInvoice']);

// 5. HRMS Routes
$app->get('/hrms', [HrmsController::class, 'index']);
$app->post('/hrms/store-employee', [HrmsController::class, 'storeEmployee']);
$app->post('/hrms/mark-attendance', [HrmsController::class, 'markAttendance']);
$app->post('/hrms/apply-leave', [HrmsController::class, 'applyLeave']);

// 6. Payroll Routes
$app->get('/payroll', [PayrollController::class, 'index']);
$app->post('/payroll/generate-batch', [PayrollController::class, 'generateBatch']);
$app->post('/payroll/mark-paid', [PayrollController::class, 'markPaid']);
$app->get('/api/payroll/{id}', [PayrollController::class, 'viewSlip']);

// 7. Payment Notifications Routes
$app->get('/notifications', [NotificationController::class, 'index']);
$app->post('/notifications/mark-read', [NotificationController::class, 'markRead']);
$app->post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
$app->post('/notifications/send-reminder', [NotificationController::class, 'sendReminder']);

// 8. Approvals Routes (1-Time Approval)
$app->get('/approvals', [ApprovalController::class, 'index']);
$app->post('/approvals/store', [ApprovalController::class, 'store']);
$app->post('/approvals/approve', [ApprovalController::class, 'approve']);
$app->post('/approvals/reject', [ApprovalController::class, 'reject']);

// Run Application
$app->run();
