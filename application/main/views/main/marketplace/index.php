<form id="searchProducts" action="<?php echo site_url('marketplace') ?>" method="get">
   <div class="row justify-content-center">
      <div class="col-md-8 text-center">
        <h3>Marketplace</h3>
         <div>
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
         </div>
      </div>
   </div>
</form>

<div class="product-listing mt-4">
  <div class="row gutter-5">
    <?php
    if (count($products)) {
    ?>
    <div class="col-sm-12">
      <div class="row gutter-5 itemcont">
      <?php foreach ($products as $item) { ?>
        <div class="col-6 col-sm-4 col-md-3 item">
          <!-- <span class="card" data-id="<?php echo $item['id'] ?>" onclick="Marketplace.viewItem(this, event)">
      			<img class="card-img-top" title="<?php echo $item['Description'] ?>" src="<?php echo public_url('assets/products/') . $item['Image'] ?>">
      			<div title="<?php echo $item['Description'] ?>" class="info">
	      			<h4 class="text-blue"><?php echo $item['Name'] ?></h4>
	      			<span class="seller text-cyan">
	      				<?php echo $item['seller']['Name']; ?>
	      			</span>
      			</div>
      			<div class="bottom">
	      			<hr class="line">
  					   <p class="price">
    						<span class="text-orange"><?php echo peso($item['Price']) ?></span> 
    						<span class="uom"><?php echo ($item['Measurement'] ? ' / ' . $item['Measurement'] : '') ?></span>
    					</p>
      			</div>
      			<div class="button-cont">
    					<a title="Call" href="tel:<?php echo $item['seller']['Contact']; ?>"><i class="fa fa-phone"></i></a>
    					<a href="mail:<?php echo $item['seller']['Email']; ?>" title="Send a message to the seller"><i class="fa fa-envelope"></i></a>
    				</div>
    		  </span> -->
          <div class="card">
            <img class="card-img-top" src="<?php echo public_url('assets/products/') . $item['Image']  ?>" alt="Card image cap">
            <div class="card-body p-2">
              <span class="text-danger"><?php echo $item['Name'] ?></span>
              <span class="uom"><?php echo ($item['Measurement'] ? ' / ' . $item['Measurement'] : '') ?></span>
              <span class="d-block text-info"><?php echo peso($item['Price']) ?></span>
              <div class="row no-gutters">
                <div class="col-8">
                  <span class="d-block small"><?php echo $item['seller']['Name'] ?></span>
                </div>
                <div class="col-4 text-right">
                  <a title="Call" href="tel:<?php echo $item['seller']['Contact']; ?>"><i class="fa fa-phone"></i></a>
                  <a href="mail:<?php echo $item['seller']['Email']; ?>" title="Send a message to the seller"><i class="fa fa-envelope"></i></a>
                </div>
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
    </div>
    <?php
    } else {
        echo '<div class="col-sm-12"><h4 class="h4">No record found.</h4></div>';
    }
    ?>
  </div>
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