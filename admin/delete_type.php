<?php
include '../config/config.php';
$id = $_GET['id'];
$conn->query("DELETE FROM type WHERE id = $id");
header ('Location: home.php');
?>