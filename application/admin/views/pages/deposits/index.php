<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header">
        <h3 class="box-title">Deposit Requests</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body table-responsive no-padding">
        <table id="tableData" class="table table-hover">
          <thead>
            <tr>
              <th>Code</th>
              <th>Depositor</th>
              <th>Reference No</th>
              <th>Transaction Date</th>
              <th>Amount</th>
              <th>Bank</th>
              <th>Branch</th>
              <th class="c">Verified Date</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($records as $c) {
              echo "<tr class='text-left' id='deposit_{$c['Code']}'>";
                echo '<td>' . $c['Code'] . '</td>';
                echo '<td>' . $c['accountData']['Firstname'] . ' ' . $c['accountData']['Lastname'] . '</td>';
                echo '<td>' . $c['ReferenceNo'] . '</td>';
                echo '<td>' . $c['TransactionDate'] . '</td>';
                echo '<td>' . peso($c['Amount']) . '</td>';
                echo '<td>' . $c['Bank'] . '</td>';
                echo '<td>' . $c['Branch'] . '</td>';
                echo '<td>';
                if ($c['Status'] == 0) {
                  echo   '<div class="box-tools">
                          <div class="input-group pull-right" style="width: 10px;">
                            <div class="input-group-btn">
                              <button type="button" class="btn btn-xs btn-success" onClick="Wallet.confirmDeposit('.$c['Code'].')"><i class="fa fa-check"></i> Verify</button>
                              <button type="button" class="btn btn-xs btn-danger" onClick="Wallet.declineDeposit('.$c['Code'].')"><i class="fa fa-ban"></i> Decline</button>
                            </div>
                          </div>
                        </div>';
                } else if ($c['Status'] == 1) {
                  echo date('m/d/y h:i a', strtotime($c['VerifiedDate']));
                } else if ($c['Status'] == 2) {
                  echo '<span class="text-danger">Declined</span>';
                }
                echo '</td>';
              echo '</tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>

<?php view('pages/product/modals.php'); ?>