<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\ApprovalModel;
use App\Models\TruckModel;
use App\Models\PayrollModel;
use App\Models\HrmsModel;
use App\Models\ActivityModel;

class ApprovalController extends Controller {
    private $approvalModel;
    private $truckModel;
    private $payrollModel;
    private $hrmsModel;
    private $activityModel;

    public function __construct() {
        $this->approvalModel = new ApprovalModel();
        $this->truckModel = new TruckModel();
        $this->payrollModel = new PayrollModel();
        $this->hrmsModel = new HrmsModel();
        $this->activityModel = new ActivityModel();
    }

    public function index() {
        $approvals = $this->approvalModel->getAll();
        $stats = $this->approvalModel->getStats();

        $this->render('approvals/index', [
            'title' => 'Pending Approvals (1-Time Approval) - Logistics Pro',
            'activePage' => 'approvals',
            'approvals' => $approvals,
            'stats' => $stats
        ]);
    }

    public function store() {
        $type = trim($_POST['request_type'] ?? 'Purchase Order');
        $ref = trim($_POST['reference_no'] ?? ('REQ-' . rand(1000, 9999)));
        $by = trim($_POST['requested_by'] ?? 'Admin User');
        $dept = trim($_POST['department'] ?? 'Operations');
        $amount = !empty($_POST['amount']) ? (float)$_POST['amount'] : null;
        $desc = trim($_POST['description'] ?? '');

        if (empty($desc)) {
            return $this->redirect('/approvals', 'error', 'Request description is required.');
        }

        $this->approvalModel->create([
            'request_type' => $type,
            'reference_no' => $ref,
            'requested_by' => $by,
            'department' => $dept,
            'amount' => $amount,
            'date' => date('d M Y'),
            'description' => $desc,
            'status' => 'Pending'
        ]);

        return $this->redirect('/approvals', 'success', 'Approval request submitted successfully!');
    }

    public function approve() {
        $id = (int)($_POST['id'] ?? 0);
        $remarks = trim($_POST['remarks'] ?? 'Approved after verification.');
        $admin = 'Admin User';

        $approval = $this->approvalModel->getById($id);
        if (!$approval || strtolower($approval['status']) !== 'pending') {
            return $this->redirect('/approvals', 'error', 'This request has already been processed or does not exist (1-Time Approval rule).');
        }

        $this->approvalModel->approve($id, $admin, $remarks);

        // Update related module if applicable
        if ($approval['request_type'] === 'Truck Gate Pass') {
            // Find manifest by reference
            $manifests = $this->truckModel->getAllManifests();
            foreach ($manifests as $m) {
                if ($m['manifest_no'] === $approval['reference_no']) {
                    $this->truckModel->updateManifest($m['id'], [
                        'approval_status' => 'Approved',
                        'status' => 'Dispatched'
                    ]);
                    break;
                }
            }
        }

        $this->activityModel->log('approval', "Approved {$approval['request_type']} ({$approval['reference_no']})", "Approved by {$admin}", 'ph-check-circle', 'color-green');

        return $this->redirect('/approvals', 'success', "Request {$approval['reference_no']} has been APPROVED successfully!");
    }

    public function reject() {
        $id = (int)($_POST['id'] ?? 0);
        $remarks = trim($_POST['remarks'] ?? 'Rejected by Administrator.');
        $admin = 'Admin User';

        $approval = $this->approvalModel->getById($id);
        if (!$approval || strtolower($approval['status']) !== 'pending') {
            return $this->redirect('/approvals', 'error', 'This request has already been processed or does not exist (1-Time Approval rule).');
        }

        $this->approvalModel->reject($id, $admin, $remarks);

        $this->activityModel->log('approval', "Rejected {$approval['request_type']} ({$approval['reference_no']})", "Reason: {$remarks}", 'ph-x-circle', 'color-red');

        return $this->redirect('/approvals', 'warning', "Request {$approval['reference_no']} has been REJECTED.");
    }
}
