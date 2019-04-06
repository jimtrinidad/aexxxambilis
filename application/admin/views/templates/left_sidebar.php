<section class="sidebar">
  <!-- Sidebar user panel (optional) -->
  <div class="user-panel">
    <div class="pull-left image">
      <img src="<?php echo public_url('assets/profile/') . photo_filename($accountInfo->Photo); ?>" class="img-circle" alt="User Image">
    </div>
    <div class="pull-left info">
      <p><?php echo user_full_name($accountInfo, false); ?></p>
      <!-- Status -->
      <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
    </div>
  </div>
  <!-- /.search form -->
  <!-- Sidebar Menu -->
  <ul class="sidebar-menu" data-widget="tree">
    <li class="header">Main Menu</li>
    <!-- Optionally, you can add icons to the links -->
    <li class="<?php echo (is_current_url('dashboard', 'index') ? 'active' : ''); ?>"><a href="<?php echo site_url() ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
    <li class="<?php echo (is_current_url('deposits', 'requests') ? 'active' : ''); ?>"><a href="<?php echo site_url('deposits/requests') ?>"><span>Deposit Requests</span></a></li>
    <li class="treeview active">
      <a href="#"><span>Products</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li class="<?php echo (is_current_url('product', 'categories') ? 'active' : ''); ?>"><a href="<?php echo site_url('product/categories') ?>">Categories</a></li>
      </ul>
    </li>
  </ul>
<!-- /.sidebar-menu -->
</section>