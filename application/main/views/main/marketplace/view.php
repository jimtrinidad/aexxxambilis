<div class="row justify-content-center">
	<div class="col-md-6 px-4">
		<div class="row">
			<div class="col text-center">
				<img class="img-fluid" src="<?php echo public_url('assets/products') . product_filename($productData->Image); ?>" />
			</div>
		</div>
		<div class="row mt-3">
			<div class="col">
				<h5 class="text-blue"><?php echo $productData->Name ?><?php echo ($productData->Measurement ? ' / ' . $productData->Measurement : '') ?></h5>
			</div>
		</div>

		<!-- <div class="row mt-3">
			<div class="col add-quantity-products">
				<span class="buttons">-</span>
				<span class="num-items">1</span>
				<span class="buttons">+</span>
			</div>
		</div> -->

		<div class="row">
			<div class="col text-center">
				<button class="add-to-cart-btn" onclick="Marketplace.addToCart('<?php echo $productData->Code ?>')">add to basket - <?php echo peso($productData->Price) ?></button>
			</div>
		</div>

		<div class="row">
			<div class="col product-desc">
				<h6>Rewards</h6>
				<?php 
          echo '<p>' . number_format($distribution['discount'], 1) . ' Points </p>' . 
               '<p>' . number_format($distribution['divided_reward'], 1) . ' Shared </p>' . 
               '<p>' . number_format($distribution['cashback'], 1) . ' Cashback</p>';
        ?>
			</div>
		</div>

		<div class="row">
			<div class="col product-desc">
				<h6>Delivery Method</h6>
				<p><?php echo lookup('delivery_methods', ($productData->DeliveryMethod ? $productData->DeliveryMethod : 3)) ?></p>
			</div>
		</div>

		<div class="row">
			<div class="col-12 product-desc">
				<h6>Description</h6>
				<div class="row">
					<div class="col-12 px-3 pt-2">
						<p><?php echo $productData->Description ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php view('main/marketplace/modals'); ?>