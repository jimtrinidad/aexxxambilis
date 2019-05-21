<!doctype html>
<html lang="en">
  <head>
  
    <!-- Required meta tags -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<link rel="shortcut icon" href="<?php echo public_url(); ?>resources/images/favicon.ico" type="image/x-icon">
		<link rel="icon" href="<?php echo public_url(); ?>resources/images/favicon.ico" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?php echo TITLE_PREFIX . $pageTitle ?></title>
    
    <?php if (isset($pageMeta)) {echo $pageMeta;} ?>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap-reboot.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
		<link rel="stylesheet" href="<?php echo public_url(); ?>resources/css/style.css?<?php echo recache()?>">
		<link rel="stylesheet" href="<?php echo public_url(); ?>resources/css/site.css?<?php echo recache()?>">

		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	
  </head>
  <body>
		
			
		<!-- Header -->
		<div id="header">
			<a href="<?php echo site_url()?>"><img src="<?php echo public_url(); ?>resources/images/ambilis-bills.png?1" /></a>
		</div>
	  <!-- Header End -->

	  <?php view('templates/menu'); ?>
				
			<!-- Main Body -->
			<div id="main-content" class="<?php echo ($pageClass ?? '') ?>">
				<div class="container p-0">
					<div class="rounded p-1 pt-2">
						<?php echo $templateContent;?>
					</div>
				</div>
			</div>
			<!-- Main Body End -->

		<?php if (!isGuest()) { ?>
		<div id="footer" class="user-logged-in text-center text-white p-3">
			Hi <a href="#"><?php echo $accountInfo->Firstname?>!</a> Welcome to your Mobile Business Menu. <br>Not you? <a href="<?php echo site_url('account/logout') ?>">Logout</a>
			<br>
			<small>Referral Link: <span class="text-primary"><?php echo site_url('u/' . $accountInfo->PublicID) ?></span></small>
		</div>
		<?php } ?>

		<?php view('templates/js_constants'); ?>
		<?php view('modals/global'); ?>
		
		<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-loading-overlay/2.1.6/loadingoverlay.min.js"></script>
		<script src="<?php echo public_url(); ?>resources/js/modules/utils.js?<?php echo recache()?>"></script>
		<script src="<?php echo public_url(); ?>resources/js/modules/wallet.js?<?php echo recache()?>"></script>

		<?php
      if (isset($jsModules)) {
        foreach ($jsModules as $jsModule) {
          echo '<script src="'. public_url() .'resources/js/modules/'. $jsModule .'.js?'. recache() .'"></script>';
        }
      }
    ?>
	
  </body>
</html>