<div class="my-account container mb-3">
    
  <div class="row">
    <div class="col-3">
      <img src="<?php echo public_url('assets/profile/') . photo_filename($accountInfo->Photo); ?>" width="100%"/>
    </div>
    <div class="col-6">
      <div class="balance-info">
        <p>Balance: <?php echo peso($summary['balance']) ?></p>
        <p>Total Transactions: <?php echo number_format($summary['transactions']) ?></p>
        <p>Total Debits: <?php echo peso($summary['debit']) ?></p>
        <p>Total Credits: <?php echo peso($summary['credit']) ?></p>
      </div>
    </div>
    <div class="col-3 text-right">
      <img src="<?php echo public_url('assets/qr/') . get_qr_file($accountInfo->RegistrationID); ?>" width="100%" />
    </div>
  </div>

  <div class="account-menu mt-4">
    <div class="header clearfix">
      <span class="text float-left">Balance</span>
      <span class="text float-right"><span class="text-gray">P</span> <?php echo number_format(get_latest_wallet_balance()) ?></span>
    </div>
    
    <div class="content">
      <div class="row">
        <div class="col-3 text-center icon-container">
          <a href="javascript:;" onclick="Wallet.addDeposit()">
            <img src="<?php echo public_url(); ?>resources/images/icons/wallet.png" />
            <span>Fund my Wallet</span>
          </a>
        </div>
        <div class="col-3 text-center icon-container">
          <a href="javascript:;" onclick="Wallet.encashRequest()">
            <img src="<?php echo public_url(); ?>resources/images/icons/encash-money.png" />
            <span>Encash</span>
          </a>
        </div>
        <div class="col-3 text-center icon-container">
          <a href="<?php echo site_url('rewards') ?>">
            <img src="<?php echo public_url(); ?>resources/images/icons/rewards-money.png" />
            <span>Rewards</span>
          </a>
        </div>
        <div class="col-3 text-center icon-container">
          <a href="<?php echo site_url('bills') ?>">
            <img src="<?php echo public_url(); ?>resources/images/icons/pay-bills.png" />
            <span>Pay Bills</span>
          </a>
        </div>
      </div>
    </div>
    
  </div>

</div>


<div class="bg-trans-80-white p-3">
  <h5>My Retail Transactions</h5>
  <?php if (count($transactions)) { ?>
  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th scope="col" class="text-red">Connections</th>
          <th scope="col" class="text-red">Contact</th>
          <th scope="col" class="text-red">Email</th>
          <th scope="col" class="text-red">Earned</th>
          <th scope="col" class="text-red">Points</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($transactions as $i) {
          echo '<tr>
                  <th scope="row">' . $i['Firstname'] . ' ' . $i['Lastname'] . '<small class="d-xs-block d-sm-block d-md-inline"> ' . date('m/d/y h:i a', strtotime($i['DateAdded'])) . ' </small></th>
                  <td>' . $i['Mobile'] . '</td>
                  <td>' . $i['EmailAddress'] . '</td>
                  <td>' . peso(rand(0, 9999)) .  '</td>
                  <td>' . number_format(rand(0, 9999)) .  '</td>
                </tr>';
        }
        ?>
      </tbody>
    </table>
  </div>
  <?php 
    } else {
      echo '<h3>No transaction found.</h3>';
    } 
  ?>
  
</div>

<?php view('main/ewallet/modals'); ?>