<div class="mobile-menu-top container">
		
	<div class="mobile-menu-info">
		<div class="row">
			<div class="col-8">
				<img src="<?php echo public_url('assets/profile/') . photo_filename($accountInfo->Photo); ?>" class="profile-pic"/>
				<span class="pr-id"><?php echo substr($accountInfo->Firstname, 0,1) . '. ' . $accountInfo->Lastname . ' - ID ' . account_public_id()?></span>
			</div>
			<div class="col-4 text-right">
				<img src="<?php echo public_url('assets/qr/') . get_qr_file($accountInfo->RegistrationID); ?>" width="35" class="pr-qr-code" />
			</div>
		</div>
	</div>
	
	<div class="account-menu">
		<div class="header clearfix">
			<span class="text float-left">Balance</span>
			<span class="text float-right"><span class="text-gray">P</span> <?php echo number_format(get_latest_wallet_balance()) ?></span>
		</div>
		
		<div class="content">
			<div class="row">
				<div class="col-3 text-center icon-container">
					<a href="<?php echo site_url('ewallet') ?>">
						<img src="<?php echo public_url(); ?>resources/images/icons/wallet.png" />
						<span>Fund my Wallet</span>
					</a>
				</div>
				<div class="col-3 text-center icon-container">
					<a href="<?php echo site_url() ?>">
						<img src="<?php echo public_url(); ?>resources/images/icons/encash-money.png" />
						<span>Encash</span>
					</a>
				</div>
				<div class="col-3 text-center icon-container">
					<a href="<?php echo site_url('rewards') ?>">
						<img src="<?php echo public_url(); ?>resources/images/icons/rewards-money.png" />
						<span>Encash</span>
					</a>
				</div>
				<div class="col-3 text-center icon-container">
					<a href="<?php echo site_url('bills') ?>">
						<img src="<?php echo public_url(); ?>resources/images/icons/pay-bills.png" />
						<span>Pay Bills</span>
					</a>
				</div>
			</div>
		</div>
		
	</div>

	<div class="secondary-account-menu">
		<div class="row mt-4">
			<div class="col-4 icon-container">
				<a href="<?php echo site_url('store') ?>">
					<img src="<?php echo public_url(); ?>resources/images/icons/market-stand.png" />
					<span>My Business</span>
				</a>
			</div>
			<div class="col-4 icon-container">
				<a href="<?php echo site_url('ewallet') ?>">
					<img src="<?php echo public_url(); ?>resources/images/icons/bag.png" />
					<span>My Transactions</span>
				</a>
			</div>
			<div class="col-4 icon-container">
				<a href="<?php echo site_url('connections') ?>">
					<img src="<?php echo public_url(); ?>resources/images/icons/connections.png" />
					<span>My Connections</span>
				</a>
			</div>
		</div>
	</div>
	

</div>


<div class="container mt-4">
	<div class="row">

		<?php
		$categories  = lookup_all('ProductCategories', false, 'Name', false);
		foreach ($categories as $k => $c) {

			if ($k > 0 && $k % 3 == 0) {
				echo '</div><div class="row mt-2">';
			}

			echo '<div class="col-4 menu-categories">
							<div class="product-img">
								<img src="'. public_url('assets/uploads/') . upload_filename($c['Image']) .'" width="100%" style="max-height: 180px;" />
							</div>
							<div class="bg-red ' . ($k % 2 == 0 ? 'bg-red text-white' : 'bg-gray text-black') . ' p-2 ">
								<h3>'. $c['Name'] .'</h3>
							</div>
						</div>';
		}
		?>

	</div>
	
</div>