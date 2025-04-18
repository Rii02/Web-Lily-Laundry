<?php
include '../config/config.php';
$id = $_GET['id'];
$conn->query("DELETE FROM orders WHERE id = $id");
header ('Location: home.php');
?>