<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header">
        <h3 class="box-title">Orders</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body table-responsive no-padding">
        <table id="tableData" class="table table-hover">
          <thead>
            <tr>
              <th>Order No</th>
              <th>Buyer</th>
              <th>Payment</th>
              <th>ItemCount</th>
              <th>Amount</th>
              <th>Company</th>
              <th>Inverstor</th>
              <th>Status</th>
              <th>Date</th>
              <th class="c"></th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($records as $c) {
              $d = json_decode($c['Distribution']);
              echo "<tr class='text-left' id='deposit_{$c['Code']}'>";
                echo '<td>' . $c['Code'] . '</td>';
                echo '<td>' . $c['user']->Firstname . ' ' . $c['user']->Lastname . '</td>';
                echo '<td>' . lookup('payment_method', $c['PaymentMethod']) . '</td>';
                echo '<td>' . $c['ItemCount'] . '</td>';
                echo '<td>' . peso($c['TotalAmount']) . '</td>';
                echo '<td>' . peso($d->company) . '</td>';
                echo '<td>' . peso($d->investor) . '</td>';
                echo '<td>' . lookup('order_status', $c['Status']) . '</td>';
                echo '<td>' . date('y/m/d', strtotime($c['DateOrdered'])) . '</td>';
                echo '<td>';
                  echo   '<div class="box-tools">
                          <div class="input-group pull-right" style="width: 10px;">
                            <div class="input-group-btn">
                              <button type="button" class="btn btn-xs btn-info" onClick="General.viewOrderInvoice('.$c['Code'].')"><i class="fa fa-file-text-o"></i> Invoice</button>
                            </div>
                          </div>
                        </div>';
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