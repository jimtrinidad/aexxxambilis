<div class="my-account container">
			<div class="row">
				<div class="col-3">
					<img src="<?php echo public_url('assets/profile/') . photo_filename($accountInfo->Photo); ?>" width="100%" />
				</div>
				<div class="col-6">
		     	<div class="balance-info">
		        <p>Balance: <?php echo peso($summary['balance']) ?></p>
		        <p>Total Transactions: <?php echo number_format($summary['transactions']) ?></p>
		        <p>Total Debits: <?php echo peso($summary['debit']) ?></p>
		        <p>Total Credits: <?php echo peso($summary['credit']) ?></p>
		      </div>
		    </div>
				<div class="col-3">
					<img src="<?php echo public_url('assets/qr/') . get_qr_file($accountInfo->RegistrationID); ?>" width="100%" />
				</div>
			</div>	
			
			<div class="row main-info">
				<div class="col-12 content">
					<label class="label-info">Name</label>
					<div class="info-field clearfix">
						<span class="text"><?php echo $accountInfo->fullname; ?></span>
						<span class="icon"><i class="fa fa-check-circle" aria-hidden="true"></i></span>
					</div>
				</div>
				<div class="col-12 content">
					<label class="label-info">Mobile Number</label>
					<div class="info-field clearfix">
						<span class="text">+63 - <?php echo $accountInfo->Mobile ?></span>
						<span class="icon"><i class="fa fa-check-circle" aria-hidden="true"></i></span>
					</div>
				</div>
				<div class="col-12 content">
					<label class="label-info">Email Address</label>
					<div class="info-field clearfix">
						<span class="text"><?php echo $accountInfo->EmailAddress ?></span>
						<span class="icon"><i class="fa fa-check-circle" aria-hidden="true"></i></span>
					</div>
				</div>
				<div class="col-12 content">
					<label class="label-info">My Bank Name Detail</label>
					<div class="info-field clearfix">
						<span class="text">Development Bank of the Philippines</span>
						<span class="icon"><i class="fa fa-check-circle" aria-hidden="true"></i></span>
					</div>
				</div>
				<div class="col-12 content">
					<label class="label-info">My Account Number</label>
					<div class="info-field clearfix">
						<span class="text"><strong class="text-blue">*****************</strong></span>
						<span class="icon"><strong>SHOW</strong></span>
					</div>
				</div>
				<div class="col-12 content">
					<label class="label-info">Bank Account Name</label>
					<div class="info-field clearfix">
						<span class="text"><?php echo $accountInfo->fullname; ?></span>
						<span class="icon"><i class="fa fa-check-circle" aria-hidden="true"></i></span>
					</div>
				</div>
				<div class="col-12 content">
					<label class="label-info">Delivery Address</label>
					<div class="info-field clearfix">
						<span class="text">1924 Marco Polo Ortigas Pasig City</span>
						<span class="icon"><i class="fa fa-check-circle" aria-hidden="true"></i></span>
					</div>
				</div>
			</div>
			
			<!-- Buttons -->
			<div class="row mt-4 gutter-5">
				<div class="col-4">
					<a href="javascript:;" class="button-shadow secure-my-account"><span>Secure my Account</span></a>
				</div>
				<div class="col-4">
					<a href="<?php echo site_url('connections') ?>" class="button-shadow connection-rewards"><span>Connection Rewards</span></a>
				</div>
				<div class="col-4">
					<a href="<?php echo site_url('ewallet') ?>" class="button-shadow transactions"><span>My Transactions</span></a>
				</div>
			</div>
			<!-- Buttons End -->
			
<!-- 			<div class="row">
				<div class="col-12 mt-3">
					<a href="#" class="button-shadow text-center text-black">
						<img src="library/images/icons/google-icon.png" width="25" />
						Connect with Google
					</a>
				</div>
				
				<div class="col-12 mt-3">
					<a href="#" class="button-blue text-center text-white">
						<img src="library/images/icons/fb-icon.png" width="25" />
						Unlink from Facebook
					</a>
				</div>
			</div> -->
			
			
		</div>	