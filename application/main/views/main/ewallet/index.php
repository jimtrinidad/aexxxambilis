<div class="row">
  <div class="col-12">
    <h5 class="secondary-title">EWallet</h5>
  </div>
</div>

<div class="row">
  <div class="col-8 col-sm-9 col-md-10">
    <img src="<?php echo public_url('assets/profile/') . photo_filename($accountInfo->Photo); ?>" class="profile-pic"/>
    <h3>Hi, <?php echo user_full_name($accountInfo, 0); ?></h3>
    <div>Balance: <?php echo peso($summary['balance']) ?></div>
    <div>Total Transactions: <?php echo number_format($summary['transactions']) ?></div>
    <div>Total Debits: <?php echo peso($summary['debit']) ?></div>
    <div>Total Credits: <?php echo peso($summary['credit']) ?></div>
  </div>
  <div class="col-4 col-sm-3 col-md-2">
    <div class="left-button text-right">
      <a href="javascript:;" onclick="Wallet.addDeposit()">
        <img src="<?php echo public_url(); ?>resources/images/icons/fund-cash.png" /><br />
        <span>Fund my Wallet</span>
      </a>
      <a href="#">
        <img src="<?php echo public_url(); ?>resources/images/icons/en-cash.png" /><br />
        <span>Encash</span>
      </a>
      <a href="#">
        <img src="<?php echo public_url(); ?>resources/images/icons/rewards.png" /><br />
        <span>Rewards</span>
      </a>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div>
      <h5>My Transactions</h5>
      <?php if (count($transactions)) { ?>
      <div class="table-responsive">
        <table class="table">
          <thead>
          <tr>
            <th scope="col" class="text-red">Date</th>
            <th scope="col" class="text-red">Transaction#</th>
            <th scope="col" class="text-red">Description</th>
            <th scope="col" class="text-red">Debit</th>
            <th scope="col" class="text-red">Credit</th>
            <th scope="col" class="text-red">Balance</th>
          </tr>
          </thead>
          <tbody>
            <?php
              foreach ($transactions as $i) {
                echo '<tr>';
                  echo '<td scope="row">' . date('m/d/y', strtotime($i['Date'])) . '</td>';
                  echo '<td>' . $i['Code'] . '</td>';
                  echo '<td>' . $i['Description'] . '</td>';
                  echo '<td class="text-center">' . ($i['debit'] ? number_format($i['debit']) : '') . '</td>';
                  echo '<td class="text-center">' . ($i['credit'] ? number_format($i['credit']) : '') . '</td>';
                  echo '<td class="text-center">' . number_format($i['EndingBalance']) . '</td>';
                echo '</tr>';
              }
              ?>
          </tbody>
        </table>
      </div>
      <!-- <a href="#" class="text-black">See All Transactions</a> -->
      <?php 
        } else {
          echo '<h3>No transaction found.</h3>';
        } 
      ?>
    </div>
  </div>
</div>

<?php view('main/ewallet/modals'); ?>