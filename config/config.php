<?php
$host ='localhost';
$user = 'root';
$pass = '';
$dbname = 'laundry1';

$conn = new mysqli(hostname: $host, username: $user, password: $pass, database: $dbname);
if ($conn ->connect_error) {
    die('koneksi gagal: '. $conn ->connect_error);
}
?>