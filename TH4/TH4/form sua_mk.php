<?php
// 1. Khởi động session ở dòng đầu tiên của file
session_start();

// 2. Kiểm tra người dùng đã đăng nhập chưa
if (!isset($_SESSION['email'])) {
    header('Location: Login.php');
    exit();
}

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_pass = $_POST['old_pass'] ?? '';
    $new_pass = $_POST['new_pass'] ?? '';
    $confirm_pass = $_POST['confirm_pass'] ?? '';

    // Ràng buộc 1: Hai ô mật khẩu mới phải khớp với nhau
    if ($new_pass !== $confirm_pass) {
        $error = "Mật khẩu mới và mật khẩu xác nhận không khớp nhau!";
    } 
    // Ràng buộc 2: Mật khẩu mới không được giống với mật khẩu cũ
    elseif ($old_pass === $new_pass) {
        $error = "Mật khẩu mới không được trùng với mật khẩu cũ!";
    } 
    else {
        // Kết nối CSDL
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "qlbanhang";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Ràng buộc 3: Kiểm tra mật khẩu cũ nhập vào có khớp với mật khẩu đang lưu trong CSDL không
        $old_pass_md5 = md5($old_pass);
        $user_email = $_SESSION['email'];

        $sql_check = "SELECT id FROM customers WHERE email = '$user_email' AND password = '$old_pass_md5'";
        $result_check = $conn->query($sql_check);

        if ($result_check && $result_check->num_rows > 0) {
            // Mật khẩu cũ chính xác -> Băm mật khẩu mới bằng md5 và cập nhật vào CSDL
            $new_pass_md5 = md5($new_pass);
            $sql_update = "UPDATE customers SET password = '$new_pass_md5' WHERE email = '$user_email'";

            if ($conn->query($sql_update) === TRUE) {
                $message = "Đổi mật khẩu thành công!";
            } else {
                $error = "Lỗi khi cập nhật CSDL: " . $conn->error;
            }
        } else {
            $error = "Mật khẩu cũ không chính xác!";
        }

        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa mật khẩu</title>
</head>
<body>

<div>
    <h2>Đổi Mật Khẩu</h2>
    <?php if (!empty($error)): ?>
        <div><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if (!empty($message)): ?>
        <div><?php echo $message; ?></div>
    <?php endif; ?>

    <form action="form sua_mk.php" method="POST">
        <div>
            <label>Mật khẩu cũ:</label>
            <input type="password" name="old_pass" required>
        </div>
        <div>
            <label>Mật khẩu mới:</label>
            <input type="password" name="new_pass" required>
        </div>
        <div>
            <label>Nhập lại mật khẩu mới:</label>
            <input type="password" name="confirm_pass" required>
        </div>
        <div>
            <button type="submit">Cập nhật</button>
        </div>
        <div>
            <a href="Homepage.php">Quay lại trang chủ</a>
        </div>
    </form>
</div>

</body>
</html>