<?php
namespace App\Models;

use App\Core\Database;

class HrmsModel {
    private $db;
    private $empTable = 'employees';
    private $attTable = 'attendance';
    private $leavesTable = 'leaves';

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAllEmployees() {
        return $this->db->all($this->empTable);
    }

    public function getEmployeeById($id) {
        return $this->db->find($this->empTable, $id);
    }

    public function createEmployee($data) {
        if (!isset($data['emp_code'])) {
            $data['emp_code'] = 'EMP-' . rand(1000, 9999);
        }
        return $this->db->insert($this->empTable, $data);
    }

    public function updateEmployee($id, $data) {
        return $this->db->update($this->empTable, $id, $data);
    }

    public function deleteEmployee($id) {
        return $this->db->delete($this->empTable, $id);
    }

    // Attendance
    public function getAttendanceByDate($date) {
        return $this->db->where($this->attTable, function($row) use ($date) {
            return ($row['date'] ?? '') === $date;
        });
    }

    public function markAttendance($data) {
        // Check if record exists for this emp on this date
        $existing = $this->db->where($this->attTable, function($row) use ($data) {
            return ($row['emp_id'] ?? '') == $data['emp_id'] && ($row['date'] ?? '') == $data['date'];
        });

        if (!empty($existing)) {
            return $this->db->update($this->attTable, $existing[0]['id'], $data);
        }
        return $this->db->insert($this->attTable, $data);
    }

    public function getAllAttendance() {
        return $this->db->all($this->attTable);
    }

    // Leaves
    public function getAllLeaves() {
        return $this->db->all($this->leavesTable);
    }

    public function createLeave($data) {
        return $this->db->insert($this->leavesTable, $data);
    }

    public function updateLeaveStatus($id, $status, $approvedBy = 'Admin') {
        return $this->db->update($this->leavesTable, $id, [
            'status' => $status,
            'approved_by' => $approvedBy,
            'approved_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function getHrmsStats() {
        $employees = $this->getAllEmployees();
        $totalEmp = count($employees);
        $activeEmp = 0;
        $onLeave = 0;
        $drivers = 0;

        foreach ($employees as $emp) {
            if (($emp['status'] ?? '') === 'Active') $activeEmp++;
            if (($emp['status'] ?? '') === 'On Leave') $onLeave++;
            if (stripos($emp['designation'] ?? '', 'Driver') !== false) $drivers++;
        }

        return [
            'total' => $totalEmp,
            'active' => $activeEmp,
            'on_leave' => $onLeave,
            'drivers' => $drivers
        ];
    }
}
