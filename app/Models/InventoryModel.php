<?php
namespace App\Models;

use App\Core\Database;

class InventoryModel {
    private $db;
    private $table = 'inventory';

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
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data) {
        return $this->db->update($this->table, $id, $data);
    }

    public function delete($id) {
        return $this->db->delete($this->table, $id);
    }

    public function adjustStock($id, $qtyChange, $type = 'in') {
        $item = $this->getById($id);
        if (!$item) return false;
        
        $currentStock = (int)$item['current_stock'];
        if ($type === 'in') {
            $newStock = $currentStock + (int)$qtyChange;
        } else {
            $newStock = max(0, $currentStock - (int)$qtyChange);
        }

        $minThreshold = (int)($item['min_threshold'] ?? 10);
        $status = 'In Stock';
        if ($newStock <= 0) {
            $status = 'Out of Stock';
        } elseif ($newStock <= $minThreshold) {
            $status = 'Low Stock';
        }

        return $this->update($id, [
            'current_stock' => $newStock,
            'status' => $status
        ]);
    }

    public function getLowStockItems() {
        return $this->db->where($this->table, function($item) {
            return ($item['current_stock'] ?? 0) <= ($item['min_threshold'] ?? 10);
        });
    }

    public function getTotalStats() {
        $items = $this->getAll();
        $totalItems = count($items);
        $totalStockQty = 0;
        $lowStockCount = 0;
        $totalValuation = 0;

        foreach ($items as $item) {
            $stock = (int)($item['current_stock'] ?? 0);
            $price = (float)($item['unit_price'] ?? 0);
            $totalStockQty += $stock;
            $totalValuation += ($stock * $price);
            if ($stock <= (int)($item['min_threshold'] ?? 10)) {
                $lowStockCount++;
            }
        }

        return [
            'total_items' => $totalItems,
            'total_stock_qty' => $totalStockQty,
            'low_stock_count' => $lowStockCount,
            'total_valuation' => $totalValuation
        ];
    }
}
