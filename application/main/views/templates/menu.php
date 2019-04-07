<div id="mobile-menu">
		
	<div class="menu-icon">
		<div></div>
		<div></div>
		<div></div>
	</div>

	Ambilis Mobile Platform
	
	<div class="dropmenu">
		<ul>
			<li><a href="<?php echo site_url('account')?>">My Account</a></li>
			<li><a href="<?php echo site_url('ewallet')?>">eWallet</a></li>
			<li><a href="<?php echo site_url()?>">Rewards</a></li>
			<li><a href="<?php echo site_url('connections')?>">Connections</a></li>
			<li><a href="<?php echo site_url('marketplace')?>">Shop</a></li>
			<li><a href="<?php echo site_url('bills')?>">Bills Payment</a></li>
			<li><a href="<?php echo site_url('eload')?>">eLoad</a></li>
			<li><a href="<?php echo site_url('store')?>">My Business</a></li>
			<li><a href="<?php echo site_url()?>">Support</a></li>
			<li><a href="<?php echo site_url()?>">How it Works</a></li>
		</ul>
	</div>

</div>

<script type="text/javascript">
		$(document).ready(function(){
			$('.menu-icon').click(function(){
				$('.dropmenu').toggle();
			});
		});
	</script>