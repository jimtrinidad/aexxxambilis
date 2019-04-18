<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header">
        <h3 class="box-title">ECPAY Billers</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body table-responsive no-padding">
        <table id="tableData" class="table table-hover">
          <thead>
            <tr>
              <td style="width: 50px;"></td>
              <th>BillerTag</th>
              <th>Description</th>
              <th>FirstField</th>
              <th>SecondField</th>
              <th>ServiceCharge</th>
              <th>LastUpdate</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($billers as $c) {
              echo "<tr class='text-left' id='biller_{$c['Code']}'>";
                echo '<td><img class="logo-small" onClick="General.updateBillerLogo(\''. $c['Code'] .'\')" src="'.public_url() . 'assets/logo/' . logo_filename($c['Image']) .'"></td>';
                echo '<td>' . $c['BillerTag'] . '</td>';
                echo '<td>' . $c['Description'] . '</td>';
                echo '<td>' . $c['FirstField'] . '</td>';
                echo '<td>' . $c['SecondField'] . '</td>';
                echo '<td>' . $c['ServiceCharge'] . '</td>';
                echo '<td>' . date('m/d/y', strtotime($c['LastUpdate'])) . '</td>';
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

<?php view('pages/billers/modals.php'); ?>