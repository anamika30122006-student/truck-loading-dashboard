<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\PayrollModel;
use App\Models\HrmsModel;
use App\Models\ApprovalModel;
use App\Models\ActivityModel;

class PayrollController extends Controller {
    private $payrollModel;
    private $hrmsModel;
    private $approvalModel;
    private $activityModel;

    public function __construct() {
        $this->payrollModel = new PayrollModel();
        $this->hrmsModel = new HrmsModel();
        $this->approvalModel = new ApprovalModel();
        $this->activityModel = new ActivityModel();
    }

    public function index() {
        $month = $_GET['month'] ?? date('Y-m');
        $payrollList = $this->payrollModel->getByMonth($month);
        if (empty($payrollList)) {
            $payrollList = $this->payrollModel->getAll();
        }
        $stats = $this->payrollModel->getPayrollStats();
        $employees = $this->hrmsModel->getAllEmployees();

        $this->render('payroll/index', [
            'title' => 'Payroll & Salary Management - Akashcoke Industries',
            'activePage' => 'payroll',
            'payrollList' => $payrollList,
            'selectedMonth' => $month,
            'stats' => $stats,
            'employees' => $employees
        ]);
    }

    public function generateBatch() {
        $month = $_POST['month'] ?? date('Y-m');
        $employees = $this->hrmsModel->getAllEmployees();
        
        $created = $this->payrollModel->processBatchPayroll($month, $employees);
        $count = count($created);

        // Submit batch for 1-Time approval
        if ($count > 0) {
            $totalAmount = 0;
            foreach ($created as $c) {
                $totalAmount += (float)$c['net_salary'];
            }

            $refNo = 'PAY-' . strtoupper(date('M-Y', strtotime($month . '-01')));
            $this->approvalModel->create([
                'request_type' => 'Payroll Disbursal',
                'reference_no' => $refNo,
                'requested_by' => 'Neha Singh (Finance)',
                'department' => 'Finance & Accounts',
                'amount' => $totalAmount,
                'date' => date('d M Y'),
                'description' => "Monthly payroll batch disbursal for {$count} employees for period {$month}.",
                'status' => 'Pending'
            ]);

            $this->activityModel->log('payroll', "Salary processed for {$month}", "Total Employees: {$count} | Amount: ₹" . number_format($totalAmount, 2), 'ph-currency-inr', 'color-cyan');
        }

        return $this->redirect('/payroll', 'success', "Payroll generated for {$count} employee(s) and submitted for approval!");
    }

    public function markPaid() {
        $id = (int)($_POST['id'] ?? 0);
        $payroll = $this->payrollModel->getById($id);
        if (!$payroll) {
            return $this->redirect('/payroll', 'error', 'Payroll record not found.');
        }

        $this->payrollModel->update($id, [
            'status' => 'Paid',
            'payment_date' => date('Y-m-d')
        ]);

        return $this->redirect('/payroll', 'success', "Salary payout marked as Disbursed for {$payroll['emp_name']}!");
    }

    public function viewSlip($id) {
        $record = $this->payrollModel->getById($id);
        if (!$record) {
            return $this->redirect('/payroll', 'error', 'Payslip not found.');
        }

        return $this->json($record);
    }
}
