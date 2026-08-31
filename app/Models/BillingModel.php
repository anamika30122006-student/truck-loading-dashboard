<?php
namespace App\Models;

use App\Core\Database;

class BillingModel {
    private $db;
    private $table = 'billing';

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        return $this->db->all($this->table);
    }

    public function getById($id) {
        return $this->db->find($this->table, $id);
    }

    public function create($data) {
        if (!isset($data['invoice_no'])) {
            $data['invoice_no'] = 'INV-' . strtoupper(substr(uniqid(), -5));
        }
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data) {
        return $this->db->update($this->table, $id, $data);
    }

    public function delete($id) {
        return $this->db->delete($this->table, $id);
    }

    public function getStats() {
        $invoices = $this->getAll();
        $totalBilling = 0;
        $paidAmount = 0;
        $pendingAmount = 0;
        $overdueAmount = 0;

        foreach ($invoices as $inv) {
            $amt = (float)($inv['total_amount'] ?? 0);
            $totalBilling += $amt;
            $status = strtolower($inv['status'] ?? '');
            if ($status === 'paid' || $status === 'received') {
                $paidAmount += $amt;
            } elseif ($status === 'overdue') {
                $overdueAmount += $amt;
            } else {
                $pendingAmount += $amt;
            }
        }

        return [
            'total_billing' => $totalBilling,
            'paid_amount' => $paidAmount,
            'pending_amount' => $pendingAmount,
            'overdue_amount' => $overdueAmount,
            'total_invoices' => count($invoices)
        ];
    }
}
