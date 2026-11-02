<?php
// Khởi động session và kiểm tra đăng nhập
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: Login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Upload Tập Tin CSV</title>
</head>
<body>

<div>
    <h2>Nhập dữ liệu khách hàng từ CSV</h2>
    
    <form action="upload-csv-processing.php" method="POST" enctype="multipart/form-data">
        <div>
            <label for="csv_file">Chọn tập tin CSV:</label>
            <input type="file" name="csv_file" id="csv_file" accept=".csv" required>
        </div>
        <div>
            <button type="submit" name="btn_upload" class="btn-upload">Submit</button>
        </div>
        <div>
            <a href="Homepage.php">Quay lại trang chủ</a>
        </div>
    </form>
</div>

</body>
</html>