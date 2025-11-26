<?php 
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ebook');
$con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
function executeQuery($con, $sql, $params = []) {
    if (empty($params)) {
        $result = mysqli_query($con, $sql);
        if (!$result) {
            throw new Exception("Query failed: " . mysqli_error($con));
        }
        return $result;
    }
    $stmt = mysqli_prepare($con, $sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . mysqli_error($con));
    }
    $types = str_repeat('s', count($params));
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
    }
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    
    return $result;
}
function escapeHTML($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
function redirectWithError($url, $error, $errorType = 'danger') {
    $separator = strpos($url, '?') !== false ? '&' : '?';
    header("Location: {$url}{$separator}error=" . urlencode($error) . "&errorType=" . urlencode($errorType));
    exit();
}
function redirectWithSuccess($url, $message) {
    redirectWithError($url, $message, 'success');
}
?>