<?php
/**
 * Direct MySQL Fix - No Laravel Bootstrap
 */

// Connect to MySQL
$conn = new mysqli("127.0.0.1", "root", "", "rental_ac");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected to database successfully!\n\n";

// Query 1: Update AC without active rentals
$sql1 = "UPDATE air_conditioners 
         SET stock = 10, status = 'available' 
         WHERE id NOT IN (
            SELECT DISTINCT air_conditioner_id 
            FROM rentals 
            WHERE status IN ('active', 'confirmed')
         )";

if ($conn->query($sql1)) {
    echo "✓ AC without active rentals updated (stock=10, status=available)\n";
} else {
    echo "✗ Error: " . $conn->error . "\n";
}

// Query 2: Update AC with active rentals
$sql2 = "UPDATE air_conditioners 
         SET status = 'rented' 
         WHERE id IN (
            SELECT DISTINCT air_conditioner_id 
            FROM rentals 
            WHERE status IN ('active', 'confirmed')
         )";

if ($conn->query($sql2)) {
    echo "✓ AC with active rentals updated (status=rented)\n";
} else {
    echo "✗ Error: " . $conn->error . "\n";
}

// Show results
echo "\n=== Current AC Status ===\n";
$result = $conn->query("SELECT id, model, stock, status FROM air_conditioners");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "ID: {$row['id']}, Model: {$row['model']}, Stock: {$row['stock']}, Status: {$row['status']}\n";
    }
} else {
    echo "No results found\n";
}

$conn->close();
echo "\n✓ Stock reconciliation complete!\n";
?>
