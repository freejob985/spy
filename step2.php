<?php
session_start();
include 'pdofile.php';
if (isset($_SESSION['authenticated'])) {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Failed to connect to database: " . $e->getMessage());
    }
    $email_to_retrieve = isset($_SESSION['email']) ? $_SESSION['email'] : '';
    $sql = "SELECT * FROM wp_registration WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email_to_retrieve);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        $code = isset($_POST['code']) ? $_POST['code'] : '';
        $db_code = $result['Code'];
        if ($code == $db_code) {
            header("Refresh: 0; URL= $registration");
            exit();
        } else {
            $_SESSION['step2'] = "الكود غير متطابق";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }
    } else {
        $_SESSION['step2'] = 'لم يتم العثور على معلومات باستخدام البريد الإلكتروني المقدم.';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}
?>