<div id="mobile-menu">
		
	<div class="menu-icon">
		<div></div>
		<div></div>
		<div></div>
	</div>

	Ambilis Mobile Platform
	
	<div class="dropmenu">
		<ul>
			<li><a href="#">My Account</a></li>
			<li><a href="#">eWallet</a></li>
			<li><a href="#">Rewards</a></li>
			<li><a href="<?php echo site_url('connections')?>">Connections</a></li>
			<li><a href="<?php echo site_url('marketplace')?>">Shop</a></li>
			<li><a href="#">Bills Payment</a></li>
			<li><a href="#">eLoad</a></li>
			<li><a href="<?php echo site_url('store')?>">My Business</a></li>
			<li><a href="#">Support</a></li>
			<li><a href="#">How it Works</a></li>
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