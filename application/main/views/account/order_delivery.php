<div class="my-account container">
	<div class="row">
		<div class="col-3 text-center">
			<img src="<?php echo public_url('assets/profile/') . photo_filename($accountInfo->Photo); ?>" width="100%" />
		</div>
		<div class="col-6">
     	<div class="balance-info" style="margin-bottom: 5px;">
	        <p>Balance: <?php echo peso($summary['balance']) ?></p>
	        <p>Total Transactions: <?php echo number_format($summary['transactions']) ?></p>
	        <p>Total Debits: <?php echo peso($summary['debit']) ?></p>
	        <p>Total Credits: <?php echo peso($summary['credit']) ?></p>
	    </div>
    </div>
		<div class="col-3">
			<img src="<?php echo public_url('assets/qr/') . get_qr_file($accountInfo->RegistrationID); ?>" width="100%" />
		</div>
	</div>

	<div class="row">
		<div class="col-12">
			<div class="bg-trans-80-white mt-3">
			  <h5>Orders</h5>
			  <?php if (count($orders)) { ?>
			  <div class="table-responsive">
			    <table class="table">
			      <thead>
			        <tr>
			          <th scope="col" class="text-red">Name</th>
			          <th scope="col" class="text-red">Contact</th>
			          <th scope="col" class="text-red">Address</th>
			          <th scope="col" class="text-red">Order ID</th>
			          <th scope="col" class="text-red">Reward</th>
			          <th scope="col" class="text-red">Status</th>
			          <th scope="col" class="text-red"></th>
			        </tr>
			      </thead>
			      <tbody>
			        <?php
			        foreach ($orders as $i) {

			        	$class = '';
			        	if ($i['Status'] == 4) {
			        		$class = 'bg-light';
			        	}
			          	echo '<tr class="' . $class . '">
			                  <td scope="row"><b>' . $i['user']['name'] . '</b><br/><small class="d-xs-block d-sm-block d-md-inline"> ' . date('m/d/y h:i a', strtotime($i['DateOrdered'])) . ' </small></td>
			                  <td>' . $i['user']['mobile'] . '<br>' . $i['user']['email']  . '</td>
			                  <td>' . $i['Address']['Street'] . ' <br>' . implode(', ', array_reverse($i['Address']['Names'])) .  '</td>
			                  <td>' . $i['Code'] .  '</td>
			                  <td>' . peso($i['Distribution']['delivery'], true, 2) .  '</td>
			                  <td>' . lookup('order_status', $i['Status']) .  '</td>
			                  <td><a class="btn btn-info btn-sm" href="'.site_url('account/order_delivery_detail/' . $i['Code']).'">Details</a></td>
			                </tr>';
			        }
			        ?>
			      </tbody>
			    </table>
			  </div>
			  <?php 
			    } else {
			      echo '<h3>No record found.</h3>';
			    } 
			  ?>
			  
			</div>
		</div>
	</div>
	
</div>