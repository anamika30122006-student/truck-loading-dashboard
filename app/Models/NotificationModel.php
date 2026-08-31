<?php
namespace App\Models;

use App\Core\Database;

class NotificationModel {
    private $db;
    private $table = 'notifications';

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

    public function markAsRead($id) {
        return $this->update($id, ['is_read' => true]);
    }

    public function markAllAsRead() {
        $items = $this->getAll();
        foreach ($items as &$item) {
            $item['is_read'] = true;
        }
        $this->db->save($this->table, $items);
        return true;
    }

    public function getUnreadCount() {
        $unread = $this->db->where($this->table, function($n) {
            return empty($n['is_read']);
        });
        return count($unread);
    }
}
