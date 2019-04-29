<div id="mobile-menu">
		
	<div class="menu-icon">
		<div></div>
		<div></div>
		<div></div>
	</div>

	<?php echo ($pageSubTitle ?? 'Ambilis Mobile Platform'); ?>
	
	<div class="dropmenu">
		<ul class="m-0 p-0">
			<li><a href="<?php echo site_url()?>"><img src="<?php echo public_url(); ?>resources/images/menu/cat.png"/>Categories</a></li>
			<li><a href="<?php echo site_url('account')?>"><img src="<?php echo public_url(); ?>resources/images/menu/my-account.png"/>My Account</a></li>
			<li><a href="<?php echo site_url('ewallet')?>"><img src="<?php echo public_url(); ?>resources/images/menu/ewallet.png"/>eWallet</a></li>
			<li><a href="<?php echo site_url('rewards')?>"><img src="<?php echo public_url(); ?>resources/images/menu/rewards.png"/>Rewards</a></li>
			<li><a href="<?php echo site_url('connections')?>"><img src="<?php echo public_url(); ?>resources/images/menu/conn.png"/>Connections</a></li>
			<li><a href="<?php echo site_url('marketplace')?>"><img src="<?php echo public_url(); ?>resources/images/menu/marketplace.png"/>Marketplace</a></li>
			<li><a href="<?php echo site_url('bills')?>"><img src="<?php echo public_url(); ?>resources/images/menu/bills.png"/>Bills Payment</a></li>
			<li><a href="javascript:;" onclick="Wallet.moneyPadalaRequest()"><img src="<?php echo public_url(); ?>resources/images/menu/money-transfer.png"/>Money Padala</a></li>
			<li><a href="javascript:;" onclick="Wallet.eloadRequest()"><img src="<?php echo public_url(); ?>resources/images/menu/eload.png"/>eLoad</a></li>
			<li><a href="<?php echo site_url('store')?>"><img src="<?php echo public_url(); ?>resources/images/menu/mybusiness.png"/>My Business</a></li>
			<li><a href="<?php echo site_url('support')?>"><img src="<?php echo public_url(); ?>resources/images/menu/support.png"/>Support</a></li>
			<li><a href="<?php echo site_url('howitworks')?>"><img src="<?php echo public_url(); ?>resources/images/menu/how.png"/>How it Works</a></li>
			<li><a href="<?php echo site_url('terms')?>"><img src="<?php echo public_url(); ?>resources/images/menu/terms.png"/>Terms & Conditions</a></li>
		</ul>
	</div>

	<div class="menu_cart_button">
		<a href="<?php echo site_url('marketplace/' . ($this->cart->total_items() > 0 ? 'cart' : '')) ?>" class="btn btn-sm bg-red text-white rounded" type="button"><span class="badge badge-warning cart_items_count"><?php echo $this->cart->total_items() ? $this->cart->total_items() : ''; ?></span><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>

		<a href="<?php echo site_url() ?>" class="btn btn-sm bg-red text-white rounded" type="button">
			<i class="fa fa-home" aria-hidden="true"></i>
		</a>

	</div>

</div>

<script type="text/javascript">
		$(document).ready(function(){
			$('.menu-icon').click(function(){
				$('.dropmenu').toggle();
			});
		});
	</script>