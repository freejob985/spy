
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <div class="container">
        <h2>تسجيل عضوية جديدة</h2>
        <form action="<?php echo  plugins_url( '/step2.php', __FILE__ ); ?>" method="post">
            <input type="hidden" name="action" value="add_step2">
<?php echo wp_nonce_field( 'name_of_my_action','name_of_nonce_field' ) ?>
            <div class="form-group">
                <label for="text"> رمز التاكيد :</label>
                <input type="text" class="form-control" id="code" name="code">
            </div>
            <button type="submit" class="btn btn-primary">إرسال</button>
        </form>
    </div>
<?php
if (isset($_SESSION['step2']) && !empty($_SESSION['step2'])) {
?>
<div class="alert alert-danger">
  <strong>خطأ!</strong> <?php echo $_SESSION['step2'] ?>
</div>
<?php
}
?>
