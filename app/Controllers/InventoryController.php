<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Helper;
use App\Models\InventoryModel;
use App\Models\ActivityModel;

class InventoryController extends Controller {
    private $inventoryModel;
    private $activityModel;

    public function __construct() {
        $this->inventoryModel = new InventoryModel();
        $this->activityModel = new ActivityModel();
    }

    public function index() {
        $items = $this->inventoryModel->getAll();
        $stats = $this->inventoryModel->getTotalStats();

        $this->render('inventory/index', [
            'title' => 'Inventory Management - Akashcoke Industries',
            'activePage' => 'inventory',
            'items' => $items,
            'stats' => $stats
        ]);
    }

    public function store() {
        $sku = $_POST['sku'] ?? ('PRD-' . strtoupper(substr(uniqid(), -4)));
        $name = trim($_POST['name'] ?? '');
        $category = trim($_POST['category'] ?? 'General');
        $stock = (int)($_POST['current_stock'] ?? 0);
        $threshold = (int)($_POST['min_threshold'] ?? 10);
        $unit = trim($_POST['unit'] ?? 'Units');
        $price = (float)($_POST['unit_price'] ?? 0);
        $weight = (float)($_POST['weight_per_unit_kg'] ?? 1.0);
        $volume = (float)($_POST['volume_per_unit_cbm'] ?? 0.01);
        $bay = trim($_POST['warehouse_bay'] ?? 'Bay-A1');

        if (empty($name)) {
            return $this->redirect('/inventory', 'error', 'Item name is required.');
        }

        $status = 'In Stock';
        if ($stock <= 0) $status = 'Out of Stock';
        elseif ($stock <= $threshold) $status = 'Low Stock';

        $newItem = $this->inventoryModel->create([
            'sku' => $sku,
            'name' => $name,
            'category' => $category,
            'current_stock' => $stock,
            'min_threshold' => $threshold,
            'unit' => $unit,
            'unit_price' => $price,
            'weight_per_unit_kg' => $weight,
            'volume_per_unit_cbm' => $volume,
            'status' => $status,
            'warehouse_bay' => $bay
        ]);

        $this->activityModel->log('stock', "New inventory item registered: {$name} ({$stock} {$unit})", "Bay: {$bay}", 'ph-cube', 'color-green');

        return $this->redirect('/inventory', 'success', 'Inventory item added successfully!');
    }

    public function update() {
        $id = (int)($_POST['id'] ?? 0);
        $item = $this->inventoryModel->getById($id);
        if (!$item) {
            return $this->redirect('/inventory', 'error', 'Item not found.');
        }

        $stock = (int)($_POST['current_stock'] ?? $item['current_stock']);
        $threshold = (int)($_POST['min_threshold'] ?? $item['min_threshold']);

        $status = 'In Stock';
        if ($stock <= 0) $status = 'Out of Stock';
        elseif ($stock <= $threshold) $status = 'Low Stock';

        $this->inventoryModel->update($id, [
            'name' => trim($_POST['name'] ?? $item['name']),
            'category' => trim($_POST['category'] ?? $item['category']),
            'current_stock' => $stock,
            'min_threshold' => $threshold,
            'unit' => trim($_POST['unit'] ?? $item['unit']),
            'unit_price' => (float)($_POST['unit_price'] ?? $item['unit_price']),
            'weight_per_unit_kg' => (float)($_POST['weight_per_unit_kg'] ?? $item['weight_per_unit_kg']),
            'volume_per_unit_cbm' => (float)($_POST['volume_per_unit_cbm'] ?? $item['volume_per_unit_cbm']),
            'status' => $status,
            'warehouse_bay' => trim($_POST['warehouse_bay'] ?? $item['warehouse_bay'])
        ]);

        return $this->redirect('/inventory', 'success', 'Item updated successfully!');
    }

    public function adjust() {
        $id = (int)($_POST['id'] ?? 0);
        $qty = (int)($_POST['qty'] ?? 0);
        $type = $_POST['type'] ?? 'in'; // in or out
        $notes = trim($_POST['notes'] ?? '');

        if ($qty <= 0) {
            return $this->redirect('/inventory', 'error', 'Quantity must be greater than 0.');
        }

        $item = $this->inventoryModel->getById($id);
        if (!$item) {
            return $this->redirect('/inventory', 'error', 'Item not found.');
        }

        $this->inventoryModel->adjustStock($id, $qty, $type);
        $actionWord = ($type === 'in') ? 'Stock-In' : 'Stock-Out';
        
        $this->activityModel->log('stock', "{$actionWord}: {$qty} {$item['unit']} of {$item['name']}", $notes, 'ph-arrows-left-right', 'color-cyan');

        return $this->redirect('/inventory', 'success', "{$actionWord} performed successfully!");
    }

    public function delete() {
        $id = (int)($_POST['id'] ?? 0);
        $this->inventoryModel->delete($id);
        return $this->redirect('/inventory', 'success', 'Item deleted from catalogue.');
    }

    public function apiList() {
        $items = $this->inventoryModel->getAll();
        return $this->json($items);
    }
}
