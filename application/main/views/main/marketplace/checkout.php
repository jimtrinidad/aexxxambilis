<div class="row">
	<div class="col-12 col-md-8 mb-2">
		<div class="card bg-light px-2 py-1 rounded-0">
			<div class="row gutter-5">
				<div class="col-6"><span><?php echo $this->cart->total_items() ?></span> Items</div>
				<div class="col-3 text-center">Price</div>
				<div class="col-3 text-right">Quantity</div>
			</div>
		</div>
		<div class="card bg-light px-2 py-1 mt-2 rounded-0 small">
			<?php 
				$x = 0;
				foreach ($items as $i) { 				
					if ($x++ > 0) {
						echo '<span class="border-top my-2"></span>';
					}
			?>

			<div class="row gutter-5 mb-1">
				<div class="col-6">
					<div class="float-left" style="width: 80px;"><img src="<?php echo public_url('assets/products/') . $i['img']?>" style="width: 70px;height: auto;" class="img-responsive" /></div>
          <div>
            <b class="nomargin cart_product_name"><?php echo $i['name']; ?></b>
            <p>By: <a href="javascript:;"><?php echo $i['seller']; ?></a></p>
          </div>
          <div class="clearfix"></div>
				</div>
				<div class="col-3 text-center">
					<?php echo peso($i['price']); ?>
					<div class="small">
					<?php 
	          echo $i['distribution']['discount'] > 0 ? '<span class="d-block">' . peso($i['distribution']['discount'], false) . ' Points </span>' : ''; 
	          echo $i['distribution']['divided_reward'] > 0 ? '<span class="d-block">' . peso($i['distribution']['divided_reward'], false) . ' Shared </span>' : ''; 
	          echo $i['distribution']['cashback'] > 0 ? '<span class="d-block">' . peso($i['distribution']['cashback'], false) . ' Cashback</span>' : '';
	        ?>	
	        </div>
				</div>
				<div class="col-3 text-right">Qty: <?php echo $i['qty']; ?></div>
			</div>
			<?php } ?>
		</div>
	</div>
	<div class="col-12 col-md-4">
		<div class="card bg-light rounded-0">
		  <div class="card-header">Summary</div>

		  <div class="card-body py-2 px-3">
		    <b class="card-title">Shipping Address</b>
		    <p class="card-text">
		    	<?php 
		    		if ($address) {
		    			echo $address->Street . ', ' . ucwords(strtolower(implode(', ', array_reverse($address->data))));
		    		} else {
		    			echo '<a href="javascript:;" onclick="General.addUserAddress()">Add Address</a>';
		    		}
		    	?>
		    </p>
		  </div>

		  <div class="card-body py-2 px-3">
		    <b class="card-title">Earnings</b>
		    <div class="small">
		    <?php
		    	if ($points > 0) {
		    		echo '<div class="row gutter-5">
					    		<div class="col-7">Points</div>
					    		<div class="col-5 text-right">' . peso($points, false) . '</div>
					    	</div>';
		    	}
		    	if ($shared > 0) {
		    		echo '<div class="row gutter-5">
					    		<div class="col-7">Shared Reward</div>
					    		<div class="col-5 text-right">' . peso($shared, false) . '</div>
					    	</div>';
		    	}
		    	if ($cashback > 0) {
		    		echo '<div class="row gutter-5">
					    		<div class="col-7">Cashback</div>
					    		<div class="col-5 text-right">' . peso($cashback, false) . '</div>
					    	</div>';
		    	}
		    ?>
		  	</div>
		  </div>

		  <div class="card-body py-2 px-3 mb-2">
		    <b class="card-title">Order Summary</b>
		    <div class="small">
		    	<div class="row gutter-5">
		    		<div class="col-7">Subtotal (<?php echo $this->cart->total_items() ?> Items)</div>
		    		<div class="col-5 text-right"><b><?php echo peso($this->cart->total()) ?></b></div>
		    	</div>
		    	<div class="row gutter-5">
		    		<div class="col-7">Delivery Charge</div>
		    		<div class="col-5 text-right"><b><?php echo peso(0) ?></b></div>
		    	</div>
		    	<div class="row gutter-5 mt-3 text-bold">
		    		<div class="col-7">Total</div>
		    		<div class="col-5 text-right"><b class="text-danger"><?php echo peso($this->cart->total()) ?></b></div>
		    	</div>
		    	<div class="row mt-2">
		    		<div class="col-12">
		    			<button onclick="Marketplace.placeOrder()" class="btn btn-warning btn-block">Order</button>
		    		</div>
		    	</div>
		    </div>
		  </div>
		</div>
	</div>
</div>

<?php view('modals/address') ?>