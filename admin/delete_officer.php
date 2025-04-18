<?php
include '../config/config.php';
$id = $_GET['id'];
$conn->query("DELETE FROM user WHERE id = $id");
header ('Location: officer.php');
?>