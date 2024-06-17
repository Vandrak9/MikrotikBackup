<?php

class BackupManager {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function backupDevice($device) {
        $connection = ssh2_connect($device['ip_address'], 22);
        if (ssh2_auth_pubkey_file($connection, 'username',
            '/path/to/public/key.pub',
            '/path/to/private/key')) {

            $stream = ssh2_exec($connection, 'backup_command');
            stream_set_blocking($stream, true);
            $output = stream_get_contents($stream);
            fclose($stream);

            file_put_contents("/path/to/backups/{$device['name']}.backup", $output);
        } else {
            throw new Exception("SSH Authentication Failed");
        }
    }

    public function backupAllDevices() {
        $stmt = $this->conn->query("SELECT * FROM devices");
        $devices = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($devices as $device) {
            try {
                $this->backupDevice($device);
                echo "Backup successful for device: " . $device['name'] . "\n";
            } catch (Exception $e) {
                echo "Backup failed for device: " . $device['name'] . " - " . $e->getMessage() . "\n";
            }
        }
    }
}
