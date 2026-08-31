<?php
namespace App\Models;

use App\Core\Database;

class TruckModel {
    private $db;
    private $trucksTable = 'trucks';
    private $manifestsTable = 'load_manifests';

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAllTrucks() {
        return $this->db->all($this->trucksTable);
    }

    public function getTruckById($id) {
        return $this->db->find($this->trucksTable, $id);
    }

    public function createTruck($data) {
        return $this->db->insert($this->trucksTable, $data);
    }

    public function updateTruck($id, $data) {
        return $this->db->update($this->trucksTable, $id, $data);
    }

    public function deleteTruck($id) {
        return $this->db->delete($this->trucksTable, $id);
    }

    public function getAllManifests() {
        return $this->db->all($this->manifestsTable);
    }

    public function getManifestById($id) {
        return $this->db->find($this->manifestsTable, $id);
    }

    public function createManifest($data) {
        if (!isset($data['manifest_no'])) {
            $data['manifest_no'] = 'GP-' . strtoupper(substr(uniqid(), -6));
        }
        return $this->db->insert($this->manifestsTable, $data);
    }

    public function updateManifest($id, $data) {
        return $this->db->update($this->manifestsTable, $id, $data);
    }

    public function deleteManifest($id) {
        return $this->db->delete($this->manifestsTable, $id);
    }

    public function getTruckStats() {
        $trucks = $this->getAllTrucks();
        $total = count($trucks);
        $loaded = 0;
        $loading = 0;
        $inTransit = 0;
        $available = 0;

        foreach ($trucks as $t) {
            $status = strtolower($t['status'] ?? '');
            if ($status === 'loaded' || $status === 'dispatched') $loaded++;
            elseif ($status === 'loading') $loading++;
            elseif ($status === 'in-transit') $inTransit++;
            else $available++;
        }

        return [
            'total' => $total,
            'loaded' => $loaded,
            'loading' => $loading,
            'in_transit' => $inTransit,
            'available' => $available
        ];
    }
}
