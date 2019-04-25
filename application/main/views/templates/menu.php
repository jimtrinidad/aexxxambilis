<div id="mobile-menu">
		
	<div class="menu-icon">
		<div></div>
		<div></div>
		<div></div>
	</div>

	<?php echo ($pageSubTitle ?? 'Ambilis Mobile Platform'); ?>
	
	<div class="dropmenu">
		<ul>
			<li><a href="<?php echo site_url('account')?>">My Account</a></li>
			<li><a href="<?php echo site_url('ewallet')?>">eWallet</a></li>
			<li><a href="<?php echo site_url('rewards')?>">Rewards</a></li>
			<li><a href="<?php echo site_url('connections')?>">Connections</a></li>
			<li><a href="<?php echo site_url('marketplace')?>">Marketplace</a></li>
			<li><a href="<?php echo site_url('bills')?>">Bills Payment</a></li>
			<li><a href="javascript:;" onclick="Wallet.moneyPadalaRequest()">Money Padala</a></li>
			<li><a href="javascript:;" onclick="Wallet.eloadRequest()">eLoad</a></li>
			<li><a href="<?php echo site_url('store')?>">My Business</a></li>
			<li><a href="<?php echo site_url('support')?>">Support</a></li>
			<li><a href="<?php echo site_url('howitworks')?>">How it Works</a></li>
			<li><a href="<?php echo site_url('terms')?>">Terms & Conditions</a></li>
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