<?php
namespace App\Models;

use App\Core\Database;

class ApprovalModel {
    private $db;
    private $table = 'approvals';

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        return $this->db->all($this->table);
    }

    public function getById($id) {
        return $this->db->find($this->table, $id);
    }

    public function getPending() {
        return $this->db->where($this->table, function($row) {
            return strtolower($row['status'] ?? '') === 'pending';
        });
    }

    public function create($data) {
        if (!isset($data['status'])) {
            $data['status'] = 'Pending';
        }
        return $this->db->insert($this->table, $data);
    }

    public function approve($id, $adminUser = 'Admin User', $remarks = 'Approved after verification') {
        $item = $this->getById($id);
        if (!$item || strtolower($item['status']) !== 'pending') {
            return false; // 1-Time approval rule: Once decided, cannot be changed
        }

        return $this->db->update($this->table, $id, [
            'status' => 'Approved',
            'approved_by' => $adminUser,
            'approved_at' => date('Y-m-d H:i:s'),
            'admin_remarks' => $remarks
        ]);
    }

    public function reject($id, $adminUser = 'Admin User', $remarks = 'Rejected by Administrator') {
        $item = $this->getById($id);
        if (!$item || strtolower($item['status']) !== 'pending') {
            return false;
        }

        return $this->db->update($this->table, $id, [
            'status' => 'Rejected',
            'approved_by' => $adminUser,
            'approved_at' => date('Y-m-d H:i:s'),
            'admin_remarks' => $remarks
        ]);
    }

    public function getStats() {
        $all = $this->getAll();
        $pending = 0;
        $approved = 0;
        $rejected = 0;

        foreach ($all as $item) {
            $st = strtolower($item['status'] ?? '');
            if ($st === 'pending') $pending++;
            elseif ($st === 'approved') $approved++;
            elseif ($st === 'rejected') $rejected++;
        }

        return [
            'total' => count($all),
            'pending' => $pending,
            'approved' => $approved,
            'rejected' => $rejected
        ];
    }
}
