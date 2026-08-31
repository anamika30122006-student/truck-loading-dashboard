<?php
namespace App\Models;

use App\Core\Database;

class PayrollModel {
    private $db;
    private $table = 'payroll';

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        return $this->db->all($this->table);
    }

    public function getById($id) {
        return $this->db->find($this->table, $id);
    }

    public function getByMonth($monthYear) {
        return $this->db->where($this->table, function($row) use ($monthYear) {
            return ($row['payroll_month'] ?? '') === $monthYear;
        });
    }

    public function create($data) {
        if (!isset($data['payslip_no'])) {
            $data['payslip_no'] = 'PAY-' . strtoupper(substr(uniqid(), -6));
        }
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data) {
        return $this->db->update($this->table, $id, $data);
    }

    public function delete($id) {
        return $this->db->delete($this->table, $id);
    }

    public function processBatchPayroll($monthYear, $employees) {
        $createdRecords = [];
        foreach ($employees as $emp) {
            // Check if already generated for this month
            $exists = $this->db->where($this->table, function($row) use ($emp, $monthYear) {
                return ($row['emp_id'] ?? '') == $emp['id'] && ($row['payroll_month'] ?? '') === $monthYear;
            });

            if (empty($exists)) {
                $basic = (float)($emp['basic_salary'] ?? 25000);
                $hra = $basic * 0.20;
                $allowances = (float)($emp['allowances'] ?? 3000);
                $gross = $basic + $hra + $allowances;
                $pf = $basic * 0.12;
                $tax = $gross > 50000 ? $gross * 0.05 : 0;
                $deductions = $pf + $tax;
                $net = $gross - $deductions;

                $record = [
                    'payslip_no' => 'PAY-' . strtoupper(substr(uniqid(), -6)),
                    'emp_id' => $emp['id'],
                    'emp_name' => $emp['name'],
                    'designation' => $emp['designation'],
                    'department' => $emp['department'],
                    'payroll_month' => $monthYear,
                    'basic_salary' => $basic,
                    'hra' => $hra,
                    'allowances' => $allowances,
                    'gross_salary' => $gross,
                    'pf_deduction' => $pf,
                    'tax_deduction' => $tax,
                    'total_deductions' => $deductions,
                    'net_salary' => $net,
                    'status' => 'Pending Approval', // Follows approval workflow
                    'payment_date' => null
                ];
                $createdRecords[] = $this->create($record);
            }
        }
        return $createdRecords;
    }

    public function getPayrollStats() {
        $records = $this->getAll();
        $totalDisbursed = 0;
        $totalPending = 0;
        $processedCount = count($records);

        foreach ($records as $r) {
            $net = (float)($r['net_salary'] ?? 0);
            if (($r['status'] ?? '') === 'Paid' || ($r['status'] ?? '') === 'Approved') {
                $totalDisbursed += $net;
            } else {
                $totalPending += $net;
            }
        }

        return [
            'total_disbursed' => $totalDisbursed,
            'total_pending' => $totalPending,
            'processed_count' => $processedCount
        ];
    }
}
