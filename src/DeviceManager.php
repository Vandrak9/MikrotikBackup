<?php

class DeviceManager {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addDevice($name, $ip_address, $ssh_key) {
        $stmt = $this->conn->prepare("INSERT INTO devices (name, ip_address, ssh_key) VALUES (:name, :ip_address, :ssh_key)");
        $stmt->execute([':name' => $name, ':ip_address' => $ip_address, ':ssh_key' => $ssh_key]);
    }

    public function removeDevice($id) {
        $stmt = $this->conn->prepare("DELETE FROM devices WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    public function getAllDevices() {
        $stmt = $this->conn->query("SELECT * FROM devices");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
