<!doctype html>
<html lang="en">
  <head>
  
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap-reboot.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
		<link rel="stylesheet" href="<?php echo public_url(); ?>resources/css/style.css">
		<link rel="stylesheet" href="<?php echo public_url(); ?>resources/css/site.css">

		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	
    <title><?php echo TITLE_PREFIX . $pageTitle ?></title>
	
  </head>
  <body class="registration">
		
		<header>
			<a href="#"><img src="<?php echo public_url(); ?>resources/images/ambilis-header-red.png" /></a>
		</header>

		<div class="container">

			<div class="row justify-content-md-center mt-5">
			  <div class="col-md-6">
			    <h3 class="text-center">Registration</h3>
			    <form id="registrationForm" action="<?php echo site_url('account/register') ?>" autocomplete="false" >
			      <input type="hidden" id="RegistrationID" name="RegistrationID" class="form-control" value="<?php echo $RegistrationID; ?>">
			      <div id="error_message_box" class="hide">
			        <div class="error_messages alert alert-danger text-danger" role="alert"></div>
			      </div>
			      <div class="form-group">
			        <input type="text" name="Firstname" id="Firstname" class="form-control" placeholder="First name">
			      </div>
			      <div class="form-group">
			        <input type="text" name="Lastname" id="Lastname" class="form-control" placeholder="Last name">
			      </div>
			      <div class="input-group mb-3">
			        <div class="input-group-prepend">
			          <span class="input-group-text" id="basic-addon1">+63</span>
			        </div>
			        <input type="text" name="Mobile" id="Mobile" class="form-control" placeholder="10 digits number. eg:9171234567" maxlength="10" aria-label="Mobile" aria-describedby="basic-addon1">
			      </div>
			      <div class="form-group">
			        <input type="email" name="EmailAddress" id="EmailAddress" class="form-control" placeholder="Email address" readonly onfocus="this.removeAttribute('readonly');">
			        <small id="emailHelp" class="form-text">This is required so you can retrieve your password in case you forget it.</small>
			      </div>
			      <div class="form-group">
			        <input type="password" name="Password" id="Password" class="form-control mb-1" placeholder="Password" readonly onfocus="this.removeAttribute('readonly');">
			        <input type="password" name="ConfirmPassword" id="ConfirmPassword" class="form-control" placeholder="Confirm Password" readonly onfocus="this.removeAttribute('readonly');">
			        <small id="passwordHelp" class="form-text">
			          Your password must have: 
			          <div class="pl-1 py-1">
			            <!-- <span class="d-block">At lease one uppercase character</span>
			            <span class="d-block">At lease one number</span>
			            <span class="d-block">8 or more characters</span>
			            <span class="d-block">No spaces</span> -->
			            <ul>
			              <li>One uppercase character</li>
			              <li>At least one number</li>
			              <li>8 or more characters</li>
			              <li>No Spaces</li>
			            </ul>
			          </div>
			        </small>
			      </div>
			      <div class="form-group">
			        <button type="submit" class="btn btn-sm btn-danger btn-block">
			          <i class="fa fa-sign-in"></i> <strong>Continue</strong>
			        </button>
			        <small id="buttonHelp" class="form-text text-muted">By continuing, you accept the <span class="text-danger">Terms and Conditions</span> of ambilis.com</small>
			      </div>
			      
			    </form>
			  </div>
			</div>

		</div>
	
	<?php view('templates/js_constants'); ?>
		
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-loading-overlay/2.1.6/loadingoverlay.min.js"></script>
	<script src="<?php echo public_url(); ?>resources/js/modules/utils.js?<?php echo time()?>"></script>

	<?php
    if (isset($jsModules)) {
      foreach ($jsModules as $jsModule) {
        echo '<script src="'. public_url() .'resources/js/modules/'. $jsModule .'.js?'. time() .'"></script>';
      }
    }
  ?>
	
  </body>
</html>`