<?php
// Super simple MySQL fix - no Laravel dependency
set_error_reporting(0);

$db = new mysqli("127.0.0.1", "root", "", "rental_ac");
if ($db->connect_error) die("Connection Error: " . $db->connect_error);

// Update AC with status 'rented' but no active rentals -> set to 'available' with stock=10
$sql = "UPDATE air_conditioners 
        SET stock = 10, status = 'available' 
        WHERE status = 'rented' 
        AND id NOT IN (
            SELECT DISTINCT air_conditioner_id 
            FROM rentals 
            WHERE status IN ('active', 'confirmed')
        )";

$db->query($sql);

// Display results
$res = $db->query("SELECT id, model, stock, status FROM air_conditioners");
echo "AC Status Updated:\n";
while ($row = $res->fetch_assoc()) {
    echo $row['id'] . ". " . $row['model'] . " - Stock: " . $row['stock'] . ", Status: " . $row['status'] . "\n";
}

$db->close();
echo "\nDone!\n";
?>
