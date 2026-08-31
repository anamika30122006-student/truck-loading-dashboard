<?php
namespace App\Models;

use App\Core\Database;

class ActivityModel {
    private $db;
    private $table = 'activities';

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll($limit = 10) {
        $all = $this->db->all($this->table);
        return array_slice($all, 0, $limit);
    }

    public function log($type, $title, $description = '', $icon = 'ph-check-circle', $color = 'text-primary') {
        return $this->db->insert($this->table, [
            'type' => $type,
            'title' => $title,
            'description' => $description,
            'icon' => $icon,
            'color' => $color,
            'time_label' => date('h:i A'),
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
}
