<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\InventoryModel;
use App\Models\TruckModel;
use App\Models\BillingModel;
use App\Models\HrmsModel;
use App\Models\PayrollModel;
use App\Models\NotificationModel;
use App\Models\ApprovalModel;
use App\Models\ActivityModel;

class DashboardController extends Controller {
    public function index() {
        $inventoryModel = new InventoryModel();
        $truckModel = new TruckModel();
        $billingModel = new BillingModel();
        $hrmsModel = new HrmsModel();
        $payrollModel = new PayrollModel();
        $notificationModel = new NotificationModel();
        $approvalModel = new ApprovalModel();
        $activityModel = new ActivityModel();

        $inventoryStats = $inventoryModel->getTotalStats();
        $truckStats = $truckModel->getTruckStats();
        $billingStats = $billingModel->getStats();
        $hrmsStats = $hrmsModel->getHrmsStats();
        $payrollStats = $payrollModel->getPayrollStats();

        // 1-Time Pending Approvals
        $pendingApprovals = $approvalModel->getPending();
        $notifications = $notificationModel->getAll();
        $recentActivities = $activityModel->getAll(6);

        $this->render('dashboard/index', [
            'title' => 'Dashboard Overview - Akashcoke Industries',
            'activePage' => 'dashboard',
            'inventoryStats' => $inventoryStats,
            'truckStats' => $truckStats,
            'billingStats' => $billingStats,
            'hrmsStats' => $hrmsStats,
            'payrollStats' => $payrollStats,
            'pendingApprovals' => $pendingApprovals,
            'notifications' => array_slice($notifications, 0, 5),
            'recentActivities' => $recentActivities
        ]);
    }
}
