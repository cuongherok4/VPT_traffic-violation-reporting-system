<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "qlybcvpgt"; // Tên CSDL của bạn

// Kết nối đến MySQL
$conn = new mysqli($servername, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
mysqli_set_charset($conn, "utf8");
