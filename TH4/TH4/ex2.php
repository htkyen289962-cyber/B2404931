<?php
$conn = new mysqli("localhost", "root", "", "demo_db");

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$uName = $_POST['Username'] ?? '';
$uPass = $_POST['Password'] ?? '';

if ($uName != '' || $uPass != '') {
    $sql = "SELECT * FROM Users WHERE Username = '" . $uName . "' AND Password = '" . $uPass . "'";
    
    echo "<b>Câu lệnh SQL thực thi:</b> " . htmlspecialchars($sql) . "<br><br>";
    
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<h3 style='color:green;'>Đăng nhập thành công! Xin chào: " . htmlspecialchars($row['Username']) . "</h3>";
    } else {
        echo "<h3 style='color:red;'>Đăng nhập thất bại!</h3>";
    }
}
$conn->close();
?>

<form method="POST">
    Username: <input type="text" name="Username"><br><br>
    Password: <input type="password" name="Password"><br><br>
    <button type="submit">Đăng nhập</button>
</form>