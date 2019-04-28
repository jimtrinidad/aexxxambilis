Hi <?php echo $firstname; ?>,

Click the link below to reset your password. <br>
<a href="<?php echo site_url('account/reset/' . $code) ?>"><?php echo site_url('account/reset/' . $code) ?></a>
<small><i>This reset link will expire after 12 hours.</i></small>

<br><br>

If you did not request to reset your password. Please secure your account.