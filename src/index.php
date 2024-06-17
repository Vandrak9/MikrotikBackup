<?php
include '../config/Database.php';
include '../src/DeviceManager.php';

$database = new Database();
$db = $database->getConnection();

$deviceManager = new DeviceManager($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $deviceManager->addDevice($_POST['name'], $_POST['ip_address'], $_POST['ssh_key']);
    } elseif (isset($_POST['remove'])) {
        $deviceManager->removeDevice($_POST['id']);
    }
}

$devices = $deviceManager->getAllDevices();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Device Management</title>
</head>
<body>
<h1>Device Management</h1>
<form method="POST">
    <h2>Add Device</h2>
    <label>Name: <input type="text" name="name" required></label><br>
    <label>IP Address: <input type="text" name="ip_address" required></label><br>
    <label>SSH Key: <textarea name="ssh_key" required></textarea></label><br>
    <button type="submit" name="add">Add Device</button>
</form>

<h2>Existing Devices</h2>
<ul>
    <?php foreach ($devices as $device): ?>
        <li>
            <?php echo htmlspecialchars($device['name']) . ' (' . htmlspecialchars($device['ip_address']) . ')'; ?>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="id" value="<?php echo $device['id']; ?>">
                <button type="submit" name="remove">Remove</button>
            </form>
        </li>
    <?php endforeach; ?>
</ul>
</body>
</html>
