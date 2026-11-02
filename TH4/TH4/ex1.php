<?php
$conn = new mysqli("localhost", "root", "", "demo_db");

// Lấy tham số UserId từ thanh địa chỉ (GET)
$txtUserId = $_GET['UserId'] ?? '';

if ($txtUserId != '') {
    // Nối chuỗi trực tiếp (LỖ HỔNG)
    $sql = "SELECT * FROM Users WHERE UserId = " . $txtUserId;
    
    // In câu lệnh SQL thực tế ra màn hình để quan sát
    echo "<b>Câu lệnh SQL thực thi:</b> " . "SELECT * FROM Users WHERE UserId = " . $txtUserId . "<br><br>";
    
    $result = $conn->query("SELECT * FROM Users WHERE UserId = " . $txtUserId);
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "ID: " . $row["UserId"]. " - Name: " . $row["Username"]. "<br>";
        }
    } else {
        echo "Không tìm thấy dữ liệu.";
    }
}
?>
<form method="GET">
    <label>Nhập UserId:</label>
    <input type="text" name="UserId">
    <button type="submit">Gửi</button>
</form>