<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\TruckModel;
use App\Models\InventoryModel;
use App\Models\ApprovalModel;
use App\Models\ActivityModel;

class TruckLoadingController extends Controller {
    private $truckModel;
    private $inventoryModel;
    private $approvalModel;
    private $activityModel;

    public function __construct() {
        $this->truckModel = new TruckModel();
        $this->inventoryModel = new InventoryModel();
        $this->approvalModel = new ApprovalModel();
        $this->activityModel = new ActivityModel();
    }

    public function index() {
        $trucks = $this->truckModel->getAllTrucks();
        $manifests = $this->truckModel->getAllManifests();
        $inventoryItems = $this->inventoryModel->getAll();
        $stats = $this->truckModel->getTruckStats();

        $this->render('truck_loading/index', [
            'title' => 'Truck Loading & Visual Bay - Logistics Pro',
            'activePage' => 'truck_loading',
            'trucks' => $trucks,
            'manifests' => $manifests,
            'inventoryItems' => $inventoryItems,
            'stats' => $stats
        ]);
    }

    public function storeTruck() {
        $truckNo = trim($_POST['truck_no'] ?? '');
        $model = trim($_POST['model'] ?? '');
        $driver = trim($_POST['driver_name'] ?? '');
        $phone = trim($_POST['driver_phone'] ?? '');
        $maxWeight = (float)($_POST['max_weight_capacity_kg'] ?? 10000);
        $maxVol = (float)($_POST['max_volume_capacity_cbm'] ?? 25);
        $route = trim($_POST['assigned_route'] ?? 'Local Dispatch');

        if (empty($truckNo)) {
            return $this->redirect('/truck-loading', 'error', 'Truck Number is required.');
        }

        $this->truckModel->createTruck([
            'truck_no' => strtoupper($truckNo),
            'model' => $model,
            'driver_name' => $driver,
            'driver_phone' => $phone,
            'max_weight_capacity_kg' => $maxWeight,
            'max_volume_capacity_cbm' => $maxVol,
            'status' => 'Available',
            'assigned_route' => $route,
            'current_loaded_weight_kg' => 0,
            'current_loaded_volume_cbm' => 0
        ]);

        $this->activityModel->log('truck', "New truck added to fleet: {$truckNo} ({$model})", "Capacity: {$maxWeight} kg", 'ph-truck', 'color-orange');

        return $this->redirect('/truck-loading', 'success', 'Truck registered successfully to fleet!');
    }

    public function createManifest() {
        $truckId = (int)($_POST['truck_id'] ?? 0);
        $truck = $this->truckModel->getTruckById($truckId);
        if (!$truck) {
            return $this->redirect('/truck-loading', 'error', 'Invalid truck selected.');
        }

        $source = trim($_POST['source'] ?? 'Central Hub Bay-1');
        $destination = trim($_POST['destination'] ?? 'Regional Distribution Center');
        
        $itemIds = $_POST['items'] ?? [];
        $itemQtys = $_POST['quantities'] ?? [];

        $loadedItems = [];
        $totalWeight = 0;
        $totalVolume = 0;

        foreach ($itemIds as $index => $itemId) {
            $qty = (int)($itemQtys[$index] ?? 0);
            if ($qty > 0) {
                $inv = $this->inventoryModel->getById($itemId);
                if ($inv) {
                    $w = (float)($inv['weight_per_unit_kg'] ?? 1) * $qty;
                    $v = (float)($inv['volume_per_unit_cbm'] ?? 0.01) * $qty;
                    $totalWeight += $w;
                    $totalVolume += $v;
                    $loadedItems[] = [
                        'item_id' => $inv['id'],
                        'item_name' => $inv['name'],
                        'qty' => $qty,
                        'unit' => $inv['unit'],
                        'weight_kg' => $w,
                        'volume_cbm' => round($v, 2)
                    ];

                    // Deduct stock from inventory
                    $this->inventoryModel->adjustStock($inv['id'], $qty, 'out');
                }
            }
        }

        $weightCap = (float)$truck['max_weight_capacity_kg'];
        $volCap = (float)$truck['max_volume_capacity_cbm'];
        $weightPct = $weightCap > 0 ? round(($totalWeight / $weightCap) * 100, 1) : 0;
        $volPct = $volCap > 0 ? round(($totalVolume / $volCap) * 100, 1) : 0;

        $manifestNo = 'GP-' . rand(100000, 999999);

        // Save manifest
        $manifest = $this->truckModel->createManifest([
            'manifest_no' => $manifestNo,
            'truck_id' => $truck['id'],
            'truck_no' => $truck['truck_no'],
            'driver_name' => $truck['driver_name'],
            'source' => $source,
            'destination' => $destination,
            'items' => $loadedItems,
            'total_weight_kg' => $totalWeight,
            'weight_capacity_kg' => $weightCap,
            'weight_utilization_pct' => $weightPct,
            'total_volume_cbm' => round($totalVolume, 2),
            'volume_capacity_cbm' => $volCap,
            'volume_utilization_pct' => $volPct,
            'status' => 'Loaded & Ready',
            'approval_status' => 'Pending Approval'
        ]);

        // Update truck status
        $this->truckModel->updateTruck($truck['id'], [
            'status' => 'Loaded',
            'current_loaded_weight_kg' => $totalWeight,
            'current_loaded_volume_cbm' => round($totalVolume, 2)
        ]);

        // Submit automatic 1-Time Gate Pass Approval request
        $this->approvalModel->create([
            'request_type' => 'Truck Gate Pass',
            'reference_no' => $manifestNo,
            'requested_by' => $truck['driver_name'] . ' (Bay Lead)',
            'department' => 'Fleet Logistics',
            'amount' => $totalWeight,
            'date' => date('d M Y'),
            'description' => "Gate clearance manifest {$manifestNo} for {$truck['truck_no']} carrying {$totalWeight} kg cargo to {$destination}.",
            'status' => 'Pending'
        ]);

        $this->activityModel->log('truck', "Truck {$truck['truck_no']} loaded successfully", "Manifest: {$manifestNo} | {$totalWeight} kg loaded", 'ph-truck', 'color-orange');

        return $this->redirect('/truck-loading', 'success', "Loading manifest & Gate Pass {$manifestNo} generated and submitted for approval!");
    }

    public function updateStatus() {
        $truckId = (int)($_POST['truck_id'] ?? 0);
        $status = $_POST['status'] ?? 'Available';
        
        $this->truckModel->updateTruck($truckId, ['status' => $status]);
        if ($status === 'Available') {
            $this->truckModel->updateTruck($truckId, [
                'current_loaded_weight_kg' => 0,
                'current_loaded_volume_cbm' => 0
            ]);
        }
        
        return $this->redirect('/truck-loading', 'success', 'Truck status updated!');
    }
}
