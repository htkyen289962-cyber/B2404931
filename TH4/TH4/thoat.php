<?php
session_start();

$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

$cookie_time = time() - 3600;
setcookie("user", "", $cookie_time, "/");
setcookie("fullname", "", $cookie_time, "/");
setcookie("id", "", $cookie_time, "/");

header('Location: login.php');
exit();
?>