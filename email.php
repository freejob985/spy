
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <div class="container">
        <h2>تسجيل عضوية جديدة</h2>
        <form action="<?php echo  plugins_url( '/step1.php', __FILE__ ); ?>" method="post">
            <input type="hidden" name="action" value="add_step1">
<?php echo wp_nonce_field( 'my_action', 'my_nonce_field' ) ?>
            <!-- إضافة حقول النموذج هنا -->
            <div class="form-group">
                <label for="email">البريد الإلكتروني:</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <button type="submit" class="btn btn-primary">إرسال</button>
        </form>
    </div>
<?php
if (isset($_SESSION['step1']) && !empty($_SESSION['step1'])) {
?>
<div class="alert alert-danger">
  <strong>خطأ!</strong> <?php echo $_SESSION['step1'] ?>
</div>
<?php
}
?>
