<?php
include '../config/config.php';

if (isset($_GET['type_id'])) {
    $type_id = intval($_GET['type_id']);
    $query = $conn->prepare("SELECT price FROM type WHERE id = ?");
    $query->bind_param("i", $type_id);
    $query->execute();
    $result = $query->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(["price_per_kg" => $row['price']]);
    } else {
        echo json_encode(["price_per_kg" => 0]);
    }
    $query->close();
} else {
    echo json_encode(["price_per_kg" => 0]);
}

$conn->close();
?>
