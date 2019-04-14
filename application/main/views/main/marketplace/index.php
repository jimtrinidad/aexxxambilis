<form id="searchProducts" action="<?php echo site_url('marketplace') ?>" method="get">
   <div class="row justify-content-center">
      <div class="col-md-8 text-center">
        <!-- <h3>Marketplace</h3> -->
         <!-- <div>
            <div class="row no-gutters">
              <div class="col-8">
                <input type="text" name="search" autocomplete="off" class="form-control" placeholder="Search Product or Service" value="<?php echo get_post('search') ?>">
              </div>
              <div class="col-2 pl-1">
                <button type="submit" class="btn btn-danger btn-block text-bold"><i class="fa fa-search"></i></button>
              </div>
              <div class="col-2 pl-1">
                <button type="submit" class="btn btn-danger btn-block text-bold"><i class="fa fa-shopping-cart"></i></button>
              </div>
            </div>
         </div> -->
         <h5 class="secondary-title text-red text-center mt-4"><?php echo $category ? $category->Name : 'My Market Place' ?></h5>
         <div class="row text-center">
          <div class="col-md-12 float-none center-block">
            <div class="input-group">
              <input type="text" class="form-control bg-cream" name="search" placeholder="Search Items" value="<?php echo get_post('search') ?>">
              <div class="input-group-append" id="button-addon4">
              <button type="submit" class="btn bg-d-purple text-white" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>
              <button class="btn bg-red text-white" type="button"><i class="fa fa-shopping-cart" aria-hidden="true"></i></button>
              </div>
            </div>  
          </div>
        </div>
      </div>
   </div>
</form>

<div class="product-listing mt-4">
    <?php
    if (count($products)) {
    ?>
      <div class="row gutter-5 itemcont">
      <?php 
        foreach ($products as $item) { 
        $distribution = profit_distribution($item['Price'], $item['CommissionValue'], $item['CommissionType']);
      ?>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
          <div class="product-img" onclick="window.location='<?php echo site_url('marketplace/view/' . $item['Code']) ?>'">
            <img src="<?php echo public_url('assets/products/') . $item['Image']  ?>" width="100%">
          </div>
          <div class="product-detail">
            <div class="row">
              <div class="col-7">
                <h2><a href="<?php echo site_url('marketplace/view/' . $item['Code']) ?>"><span class="text-red"><?php echo $item['Name'] ?></span></a><?php echo ($item['Measurement'] ? ' / ' . $item['Measurement'] : '') ?></h2>
                <p><?php echo $item['seller']['Name'] ?></p>
              </div>
              <div class="col-5 text-right">
                <strong class="price text-red"><?php echo peso($item['Price']); ?></strong>
              </div>
            </div>
            <div class="row">
              <div class="col-7">
                <p class="sub-details">
                  <?php 
                    echo '<span class="d-sm-block">' . number_format($distribution['referral'], 1) . ' Points </span>' . 
                         '<span class="d-sm-block">' . number_format($distribution['shared_rewards'], 1) . ' Shared </span>' . 
                         '<span class="d-sm-block">' . number_format($distribution['cashback'], 1) . ' Cashback</span>';
                  ?>
                </p>
              </div>
              <div class="col-5 text-right">
                <a href="javascript:;" class="pr-button bg-red text-white mb-2"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
                <a href="tel:<?php echo $item['seller']['Contact']; ?>"  class="pr-button bg-yellow text-black mb-2"><i class="fa fa-phone" aria-hidden="true"></i></a>
              </div>
            </div>
          </div>
        </div>
        <?php } ?>
      </div>
      <div class="row offset-top-10">
      	<div class="col-sm-12">
      		<?php echo $pagination ?>
      	</div>
      </div>
    <?php
    } else {
        echo '<div class="col-sm-12"><h4 class="h4">No record found.</h4></div>';
    }
    ?>
</div>

<div class="modal fade" id="viewItemModal" tabindex="-1" role="dialog" aria-labelledby="viewItemModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
          <div class="row">
          	<div class="col-sm-12 col-md-4 offset-bottom-5">
          		<img class="img-responsive item-image" src="">
          	</div>
          	<div class="col-sm-12 col-md-8">
          		<div class="row">
          			<div class="col-sm-12"><span class="name h4 text-bold text-blue">Name</span></div>
          			<div class="col-sm-12"><span class="description">edfsad fasd fasd fasdf adsf asdf assdf asdf asdf asdf asdf </span></div>
          			<div class="col-sm-12 offset-top-10">
          				<span class="price text-bold text-orange">P2,312</span>
          				<span class="uom">/ piece</span>
          			</div>
          			<div class="col-sm-12 offset-top-15 small">
          				<p>Warranty: <span class="warranty text-bold">one year</span></p>
          				<p class="offset-top-5">Terms of Payment: <span class="payment-term text-bold">Installment</span></p>
          				<p class="offset-top-5">Delivery Lead Time: <span class="lead-time text-bold">Installment</span></p>
          			</div>
          			<hr>
          			<div class="col-sm-12 offset-top-15">
          				<div class="small text-bold">Seller</div>
          				<div>
          					<span class="text-cyan text-bold sellerName">E Corp</span><br>
          					<span class="small accreditation">24324334</span>
          					<div class="offset-top-10">
	          					<a class="call-bot" style="text-decoration: none;" title="Call" href="">
	          						<i class="fa fa-phone"></i> <span class="contact"></span>
	          					</a>
								<a class="chat-bot" style="text-decoration: none;" href="javascript:;" title="Send a message to the seller">
									<i class="fa fa-envelope"></i> Send a message
								</a>
							</div>
          				</div>
          			</div>
          		</div>
          	</div>
          </div>
        </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){

  	Marketplace.itemData = <?php echo json_encode($products, JSON_HEX_TAG); ?>;

  });
</script>