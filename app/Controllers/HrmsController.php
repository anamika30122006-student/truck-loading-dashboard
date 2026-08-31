<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\HrmsModel;
use App\Models\ApprovalModel;
use App\Models\ActivityModel;

class HrmsController extends Controller {
    private $hrmsModel;
    private $approvalModel;
    private $activityModel;

    public function __construct() {
        $this->hrmsModel = new HrmsModel();
        $this->approvalModel = new ApprovalModel();
        $this->activityModel = new ActivityModel();
    }

    public function index() {
        $employees = $this->hrmsModel->getAllEmployees();
        $attendance = $this->hrmsModel->getAllAttendance();
        $leaves = $this->hrmsModel->getAllLeaves();
        $stats = $this->hrmsModel->getHrmsStats();

        $this->render('hrms/index', [
            'title' => 'HRMS & Staff Management - Logistics Pro',
            'activePage' => 'hrms',
            'employees' => $employees,
            'attendance' => $attendance,
            'leaves' => $leaves,
            'stats' => $stats
        ]);
    }

    public function storeEmployee() {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $designation = trim($_POST['designation'] ?? '');
        $department = trim($_POST['department'] ?? 'Fleet Operations');
        $joiningDate = $_POST['joining_date'] ?? date('Y-m-d');
        $basic = (float)($_POST['basic_salary'] ?? 25000);
        $allowances = (float)($_POST['allowances'] ?? 3000);

        if (empty($name)) {
            return $this->redirect('/hrms', 'error', 'Employee name is required.');
        }

        $emp = $this->hrmsModel->createEmployee([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'designation' => $designation,
            'department' => $department,
            'joining_date' => $joiningDate,
            'basic_salary' => $basic,
            'allowances' => $allowances,
            'status' => 'Active'
        ]);

        $this->activityModel->log('hrms', "New employee {$name} added", "Position: {$designation}", 'ph-users', 'color-purple');

        return $this->redirect('/hrms', 'success', "Employee {$name} onboarded successfully!");
    }

    public function markAttendance() {
        $empId = (int)($_POST['emp_id'] ?? 0);
        $emp = $this->hrmsModel->getEmployeeById($empId);
        if (!$emp) {
            return $this->redirect('/hrms', 'error', 'Employee not found.');
        }

        $status = $_POST['status'] ?? 'Present';
        $checkIn = $_POST['check_in'] ?? date('h:i A');
        $checkOut = $_POST['check_out'] ?? '06:00 PM';
        $overtime = (float)($_POST['overtime_hrs'] ?? 0);
        $date = $_POST['date'] ?? date('Y-m-d');

        $this->hrmsModel->markAttendance([
            'emp_id' => $empId,
            'emp_name' => $emp['name'],
            'date' => $date,
            'check_in' => $status === 'Absent' || $status === 'On Leave' ? '-' : $checkIn,
            'check_out' => $status === 'Absent' || $status === 'On Leave' ? '-' : $checkOut,
            'status' => $status,
            'overtime_hrs' => $overtime
        ]);

        return $this->redirect('/hrms', 'success', "Attendance updated for {$emp['name']}!");
    }

    public function applyLeave() {
        $empId = (int)($_POST['emp_id'] ?? 0);
        $emp = $this->hrmsModel->getEmployeeById($empId);
        if (!$emp) {
            return $this->redirect('/hrms', 'error', 'Employee not found.');
        }

        $type = $_POST['leave_type'] ?? 'Casual Leave';
        $from = $_POST['from_date'] ?? date('Y-m-d');
        $to = $_POST['to_date'] ?? date('Y-m-d');
        $days = (int)($_POST['total_days'] ?? 1);
        $reason = trim($_POST['reason'] ?? '');

        $refNo = 'LEV-' . rand(100, 999);

        $this->hrmsModel->createLeave([
            'emp_id' => $empId,
            'emp_name' => $emp['name'],
            'leave_type' => $type,
            'from_date' => $from,
            'to_date' => $to,
            'total_days' => $days,
            'reason' => $reason,
            'status' => 'Pending',
            'approved_by' => null,
            'approved_at' => null
        ]);

        // Trigger 1-time approval workflow
        $this->approvalModel->create([
            'request_type' => 'Leave Request',
            'reference_no' => $refNo,
            'requested_by' => $emp['name'],
            'department' => $emp['department'],
            'amount' => null,
            'date' => date('d M Y'),
            'description' => "{$type} for {$days} days ({$from} to {$to}). Reason: {$reason}",
            'status' => 'Pending'
        ]);

        return $this->redirect('/hrms', 'success', "Leave application submitted for approval!");
    }
}
