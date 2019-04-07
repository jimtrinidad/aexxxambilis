<!doctype html>
<html lang="en">
  <head>
  
    <!-- Required meta tags -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<link rel="shortcut icon" href="<?php echo public_url(); ?>resources/images/favicon.ico" type="image/x-icon">
		<link rel="icon" href="<?php echo public_url(); ?>resources/images/favicon.ico" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap-reboot.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
		<link rel="stylesheet" href="<?php echo public_url(); ?>resources/css/style.css?<?php echo recache()?>">
		<link rel="stylesheet" href="<?php echo public_url(); ?>resources/css/site.css?<?php echo recache()?>">

		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	
    <title><?php echo TITLE_PREFIX . $pageTitle ?></title>
	
  </head>
  <body>
		
			
		<!-- Header -->
		<div id="header">
			<a href="<?php echo site_url()?>"><img src="<?php echo public_url(); ?>resources/images/ambilis-bills.png" /></a>
		</div>
	  <!-- Header End -->

	  <?php view('templates/menu'); ?>
				
			<!-- Main Body -->
			<div id="main-content">
				<div class="container p-0">
					<div class="bg-trans-white rounded p-3">
						<?php echo $templateContent;?>
					</div>
				</div>
			</div>
			<!-- Main Body End -->

		<?php if (!isGuest()) { ?>
		<div id="footer" class="user-logged-in text-center text-white p-3">
			Hi <a href="#"><?php echo $accountInfo->Firstname?>!</a> Welcome to your Mobile Business Menu. <br>Not you? <a href="<?php echo site_url('account/logout') ?>">Logout</a>
		</div>
		<?php } ?>

		<?php view('templates/js_constants'); ?>
		
		<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-loading-overlay/2.1.6/loadingoverlay.min.js"></script>
		<script src="<?php echo public_url(); ?>resources/js/modules/utils.js?<?php echo recache()?>"></script>

		<?php
      if (isset($jsModules)) {
        foreach ($jsModules as $jsModule) {
          echo '<script src="'. public_url() .'resources/js/modules/'. $jsModule .'.js?'. recache() .'"></script>';
        }
      }
    ?>
	
  </body>
</html>