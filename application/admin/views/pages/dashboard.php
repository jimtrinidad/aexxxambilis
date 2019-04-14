<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class="fa fa-shopping-cart"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Uploaded Products</span>
          <span class="info-box-number"><?php echo number_format($product_count) ?></span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-red"><i class="fa fa-tags"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Manufacturers/Origin</span>
          <span class="info-box-number"><?php echo number_format($origin_count) ?></span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <!-- fix for small devices only -->
    <div class="clearfix visible-sm-block"></div>

    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-green"><i class="fa fa-shopping-basket"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Stores</span>
          <span class="info-box-number"><?php echo number_format($store_count) ?></span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-yellow"><i class="fa fa-users"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Registered Users</span>
          <span class="info-box-number"><?php echo number_format($user_count) ?></span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
  </div>