<?php include(APP_DIR.'views/header.php'); ?>

<?php if(isset($gpwd) && $gpwd) {?>
Password regenerated, Please check your email for account confirmation.

<?php }elseif(isset($fpwd) && $fpwd){?>
Mail send successfully, Please check your email to set new password.
<?php }else { ?>
You have successfully registered to our application.<br /><br />

Please check your email for account confirmation.
<?php }?>

<a href="<?php echo HTTP_ROOT?>user/login">Click here </a> to login.