<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) {
    die("الوصول ممنوع!");
}
session_start();
include  'pdofile.php';
if(isset($_POST['email']) and !empty($_POST['email']) and filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Failed to connect to database: " . $e->getMessage());
}
$user_email = isset($_POST['email']) ? $_POST['email'] : '';
$code = rand(10000,5000);
$tableName = 'wp_registration';
$sql = "INSERT INTO $tableName (email, Code) VALUES (:email, :code)";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':email', $user_email);
$stmt->bindParam(':code', $code );
$stmt->execute();
$_SESSION['email'] = $user_email;
$_SESSION['authenticated'] = true;
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;
// $mail = new PHPMailer(true);
try {
    // $mail->isSMTP();
    // $mail->Host = 'smtp.example.com';
    // $mail->SMTPAuth = true;
    // $mail->Username = 'your_email@example.com';
    // $mail->Password = 'your_email_password';
    // $mail->SMTPSecure = 'tls';
    // $mail->Port = 587;
    // $mail->setFrom('your_email@example.com', 'Your Name');
    // $mail->addAddress($email);
    // $mail->isHTML(true);
    // $mail->Subject = 'Subject of the email';
    // $mail->Body    = 'تم إضافة الكود بنجاح. الكود الخاص بك هو: ' . $code;
    // $mail->send();
    // إعادة توجيه المستخدم إلى صفحة أخرى بعد ثواني معينة
    header("Refresh: 0; URL= $url_step2");
} catch (Exception $e) {
    echo "فشل في إرسال البريد الإلكتروني: {$mail->ErrorInfo}";
}
}else{
$_SESSION['step1']="من فضلك تأكد من البريد الألكتروني ";
   header('Location: ' . $_SERVER['HTTP_REFERER']);
 exit(); 
}
?>
