<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: Login.php');
    exit();
}

// Kiểm tra xem người dùng đã nhấn nút Upload chưa
if (isset($_POST['btn_upload']) && isset($_FILES['csv_file'])) {
    
    $file = $_FILES['csv_file'];

    // Kiểm tra lỗi upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        die("Lỗi khi tải file lên máy chủ!");
    }

    // Kiểm tra đuôi file có phải là .csv không
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if ($file_ext !== 'csv') {
        die("Vui lòng chọn đúng định dạng tập tin .csv!");
    }

    // Kết nối CSDL
    $servername = "localhost";
    $username   = "root";
    $password   = "";
    $dbname     = "qlbanhang";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Kết nối CSDL thất bại: " . $conn->connect_error);
    }
    $conn->set_charset("utf8");

    // Mở file CSV để đọc
    $file_handle = fopen($file['tmp_name'], 'r');

    if ($file_handle !== FALSE) {
        $count = 0;

        $header = fgetcsv($file_handle, 1000, ",");
        while (($row = fgetcsv($file_handle, 1000, ",")) !== FALSE) {
            // Đảm bảo dòng có đủ dữ liệu các cột
            if (count($row) >= 6) {
                $id          = $conn->real_escape_string($row[0]);
                $fullname    = $conn->real_escape_string($row[1]);
                $email       = $conn->real_escape_string($row[2]);
                $birthday    = $conn->real_escape_string($row[3]);
                $reg_date    = $conn->real_escape_string($row[4]);
                $password    = $conn->real_escape_string($row[5]);
                $img_profile = isset($row[6]) && !empty($row[6]) ? "'" . $conn->real_escape_string($row[6]) . "'" : "NULL";

                $sql = "INSERT INTO customers (id, fullname, email, Birthday, reg_date, password, img_profile) 
                        VALUES ('$id', '$fullname', '$email', '$birthday', '$reg_date', '$password', $img_profile)";

                if ($conn->query($sql)) {
                    $count++;
                }
            }
        }

        fclose($file_handle);
        $conn->close();

        echo "<h3 style='color: green;'>Đã nhập thành công $count dòng dữ liệu vào bảng customers!</h3>";
        echo "<a href='Homepage.php'>Quay về Trang chủ</a> | <a href='upload-csv.php'>Tiếp tục upload</a>";
    } else {
        echo "<h3 style='color: red;'>Không thể mở tập tin CSV!</h3>";
    }

} else {
    header('Location: upload-csv.php');
    exit();
}
?>