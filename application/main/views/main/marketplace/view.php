<div class="row justify-content-center view-product">
	<div class="col-md-6 px-4">
		<div class="row">
			<div class="col text-center">
				<img class="img-fluid" src="<?php echo public_url('assets/products') . product_filename($productData->Image); ?>" />
				<button class="share_button btn btn-info btn-sm small" style="position: absolute;top:2px;right:2px;" 
					data-clipboard-text="<?php echo site_url('i/'. $productData->Code . '-' . slugit($productData->Name)) ?>">
					<i class="fa fa-copy"></i> <i class="fa fa-link"></i>
				</button>
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
				<button class="add-to-cart-btn" onclick="Marketplace.addToCart('<?php echo $productData->Code ?>')">add to basket - <?php echo show_price($productData->Price, $distribution['discount']) ?></button>
			</div>
		</div>

		<div class="row">
			<div class="col product-desc">
				<h6>Rewards</h6>
				<?php 
          echo '<p>' . peso($distribution['referral'], false) . ' Referrer Points </p>' . 
               '<p>' . peso($distribution['shared_rewards'], false) . ' Shared </p>' . 
               '<p>' . peso($distribution['cashback'], false) . ' Cashback</p>';
        ?>
			</div>
		</div>

		<?php if ($productData->DeliveryMethod != '' && $productData->DeliveryMethod != 3) { ?>
		<div class="row">
			<div class="col product-desc">
				<h6>Delivery Method</h6>
				<p><?php echo lookup('delivery_methods', $productData->DeliveryMethod) ?></p>
			</div>
		</div>
		<?php } ?>

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

<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.4/clipboard.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		var clipboard = new ClipboardJS('.share_button');
		clipboard.on('success', function(e) {

		    $('.share_button').tooltip({title: 'Share link has been copied!', placement: 'left'}).tooltip('show').on('shown.bs.tooltip', function () {
				  setTimeout(function(){
				  	$('.share_button').tooltip('dispose');
				  }, 1000);
				})

		    e.clearSelection();
		});
	});
</script>