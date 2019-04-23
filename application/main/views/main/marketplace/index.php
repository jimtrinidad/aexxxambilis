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
         <h5 class="secondary-title text-red text-center mt-4"><?php echo $category ? $category->Name : 'Marketplace' ?></h5>
            <div class="input-group">
              <input type="text" class="form-control bg-cream" name="search" placeholder="Search Items" value="<?php echo get_post('search') ?>">
              <div class="input-group-append" id="button-addon4">
              <button type="submit" class="btn bg-d-purple text-white" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>
              </div>
              <a href="<?php echo site_url('marketplace/cart') ?>" class="ml-1 btn bg-red text-white" type="button"><span class="badge badge-warning cart_items_count"><?php echo $this->cart->total_items() ? $this->cart->total_items() : '' ?></span><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
            </div>
      </div>
   </div>
</form>

<div class="product-listing mt-4">
    <?php
    if (count($products)) {
    ?>
      <div class="row gutter-5">
      <?php 
        foreach ($products as $item) { 
        $distribution = profit_distribution($item['Price'], $item['CommissionValue'], $item['CommissionType']);
      ?>
        <div class="col-6 col-sm-6 col-md-4 col-lg-3 mb-3 itemcont" style="position: relative;">
          <div class="product-cont">
            <div class="product-img" onclick="window.location='<?php echo site_url('marketplace/view/' . $item['Code']) ?>'" style="background-image: url('<?php echo public_url('assets/products/') . $item['Image']  ?>');">
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
                      echo '<span class="d-block d-md-inline">' . peso($distribution['discount'], false) . ' Points </span>' . 
                           '<span class="d-block d-md-inline">' . peso($distribution['divided_reward'], false) . ' Shared </span>' . 
                           '<span class="d-block d-md-inline">' . peso($distribution['cashback'], false) . ' Cashback</span>';
                    ?>
                  </p>
                </div>
                <div class="col-5 text-right">
                  <a href="javascript:;" class="pr-button bg-red text-white mb-2" onclick="Marketplace.addToCart('<?php echo $item['Code'] ?>')"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
                  <a href="tel:<?php echo $item['seller']['Contact']; ?>"  class="pr-button bg-yellow text-black mb-2"><i class="fa fa-phone" aria-hidden="true"></i></a>
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
    <?php
    } else {
        echo '<div class="col-sm-12"><h4 class="h4">No record found.</h4></div>';
    }
    ?>
</div>

<div class="clearfix"></div>

<div class="row">
  <div class="col-12"></div>
</div>

<?php view('main/marketplace/modals'); ?>

<script type="text/javascript">
  $(document).ready(function(){

  	// Marketplace.itemData = <?php echo json_encode($products, JSON_HEX_TAG); ?>;

    $(document).ready(function(){
      var h = $('.itemcont:last').height();
      $('.itemcont:last').mouseover(function(){
        $(this).height(h);
      }).mouseout(function(){
        $(this).height('auto');
      })
    });

  });
</script>