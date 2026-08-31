<?php
namespace App\Core;

class Database {
    private static $instance = null;
    private $dataDir;

    private function __construct() {
        $bundledDataDir = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'data';
        // In serverless environments like Vercel, the source tree is read-only, but /tmp is writable
        if (getenv('VERCEL') || (isset($_ENV['VERCEL']) && $_ENV['VERCEL'])) {
            $tmpDataDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'logistics_data';
            if (!is_dir($tmpDataDir)) {
                @mkdir($tmpDataDir, 0777, true);
            }
            if (is_dir($bundledDataDir)) {
                $files = glob($bundledDataDir . '/*.json');
                foreach ($files as $f) {
                    $target = $tmpDataDir . DIRECTORY_SEPARATOR . basename($f);
                    if (!file_exists($target) || (filemtime($f) > filemtime($target))) {
                        @copy($f, $target);
                    }
                }
            }
            $this->dataDir = $tmpDataDir;
        } else {
            $this->dataDir = $bundledDataDir;
            if (!is_dir($this->dataDir)) {
                @mkdir($this->dataDir, 0777, true);
            }
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function getFilePath($table) {
        return $this->dataDir . DIRECTORY_SEPARATOR . $table . '.json';
    }

    public function all($table) {
        $file = $this->getFilePath($table);
        if (!file_exists($file)) {
            return [];
        }
        $content = file_get_contents($file);
        $data = json_decode($content, true);
        return is_array($data) ? $data : [];
    }

    public function find($table, $id, $idField = 'id') {
        $rows = $this->all($table);
        foreach ($rows as $row) {
            if (isset($row[$idField]) && $row[$idField] == $id) {
                return $row;
            }
        }
        return null;
    }

    public function where($table, callable $callback) {
        $rows = $this->all($table);
        return array_values(array_filter($rows, $callback));
    }

    public function insert($table, array $data, $idField = 'id') {
        $rows = $this->all($table);
        if (!isset($data[$idField])) {
            $maxId = 0;
            foreach ($rows as $r) {
                if (isset($r[$idField]) && is_numeric($r[$idField])) {
                    if ((int)$r[$idField] > $maxId) {
                        $maxId = (int)$r[$idField];
                    }
                }
            }
            $data[$idField] = $maxId + 1;
        }
        
        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        if (!isset($data['updated_at'])) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        array_unshift($rows, $data); // prepend for newest first
        $this->save($table, $rows);
        return $data;
    }

    public function update($table, $id, array $data, $idField = 'id') {
        $rows = $this->all($table);
        $updated = null;
        foreach ($rows as $key => $row) {
            if (isset($row[$idField]) && $row[$idField] == $id) {
                $data['updated_at'] = date('Y-m-d H:i:s');
                $rows[$key] = array_merge($row, $data);
                $updated = $rows[$key];
                break;
            }
        }
        if ($updated) {
            $this->save($table, $rows);
        }
        return $updated;
    }

    public function delete($table, $id, $idField = 'id') {
        $rows = $this->all($table);
        $filtered = array_values(array_filter($rows, function($r) use ($id, $idField) {
            return !isset($r[$idField]) || $r[$idField] != $id;
        }));
        $this->save($table, $filtered);
        return true;
    }

    public function save($table, array $data) {
        $file = $this->getFilePath($table);
        file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }
}
