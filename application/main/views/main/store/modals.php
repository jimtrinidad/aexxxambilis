<div class="modal fade" tabindex="-1" id="storeProfileModal" role="dialog" aria-labelledby="storeProfileModal" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form id="storeProfileForm" class="modalForm" action="<?php echo site_url('store/update') ?>">
	    	<div class="modal-header">
	        <h5 class="modal-title">Update Store Profile</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
					<div class="form-group">
						<label>Name</label>
						<input type="text" class="form-control" name="Name" id="Name" placeholder="Store name">
					</div>
					<div class="form-group">
						<label>Address</label>
						<textarea class="form-control" name="Address" id="Address" placeholder="Address"></textarea>
					</div>
					<div class="form-group">
						<label>Contact</label>
						<input type="text" class="form-control" name="Contact" id="Contact" placeholder="Contact">
					</div>
					<div class="form-group">
						<label>Email</label>
						<input type="text" class="form-control" name="Email" id="Email" placeholder="Email">
					</div>
				</div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
	        <button type="submit" class="btn btn-primary">Save</button>
	      </div>
			</form>
    </div>
  </div>
</div>


<div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="itemModal">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form id="itemForm" class="modalForm" name="itemForm" action="<?php echo site_url('store/saveitem') ?>">
        <div class="modal-header">
	        <h5 class="modal-title">Product Setup</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
        <div class="modal-body">
          <div id="error_message_box" class="hide">
		        <div class="error_messages alert alert-danger text-danger" role="alert"></div>
		      </div>
          <div class="">
	          <div class="row gutter-5">
	            <div class="col-7 padding-top-15">
	              <div class="row">
	                <div class="col-12">
	                  <div class="form-group">
	                    <label class="control-label" for="Name">Name</label>
	                    <input type="text" class="form-control" id="Name" name="Name" placeholder="Product name">
	                    <span class="help-block hidden"></span>
	                  </div>
	                </div>
	                <div class="col-12">
	                  <div class="form-group">
	                    <label class="control-label" for="Category">Category</label>
	                    <select class="form-control" id="Category" name="Category">
	                    	<option value=""></option>
	                    	<?php
	                        foreach(lookup_all('ProductCategories', false, 'Name', false) as $item) {
	                          echo '<option value="' . $item['id'] . '">' . $item['Name'] . '</option>';
	                        }
	                    	?>
	                    </select>
	                    <span class="help-block hidden"></span>
	                  </div>
	                </div>
	              </div>
	            </div>
	            <div class="col-5 logo pt-4">
	              <div class="image-upload-container">
	                <img class="image-preview" src="<?php echo public_url(); ?>assets/products/default">
	                <span class="hiddenFileInput hide">
	                  <input type="file" data-default="<?php echo public_url(); ?>assets/products/default" accept="image/*" class="image-upload-input" id="Logo" name="Logo"/>
	                </span>
	              </div>
	            </div>
	            <div class="col-12">
                <div class="form-group">
                  <label class="control-label" for="Description">Description</label>
                  <textarea class="form-control" id="Description" name="Description" placeholder="Product description"></textarea>
                  <span class="help-block hidden"></span>
                </div>
              </div>
	            <div class="col-6">
	              <div class="form-group">
	                <label class="control-label" for="Price">Unit Price</label>
	                <input type="number" class="form-control" id="Price" name="Price" placeholder="Unit price">
	                <span class="help-block hidden"></span>
	              </div>
	            </div>
	            <div class="col-6">
	              <div class="form-group">
	                <label class="control-label" for="Commission">Commission</label>
	                <input type="text" class="form-control" id="Commission" name="Commission" placeholder="Commission">
	                <span class="help-block hidden"></span>
	              </div>
	            </div>
	            <div class="col-6">
	              <div class="form-group">
	                <label class="control-label" for="Measurement">Unit of measurement</label>
	                <input type="text" class="form-control" id="Measurement" name="Measurement" placeholder="Unit of measurement">
	                <span class="help-block hidden"></span>
	              </div>
	            </div>
	            <div class="col-6">
	              <div class="form-group">
	                <label class="control-label" for="DeliveryMethod">Delivery Method</label>
	                <select class="form-control" id="DeliveryMethod" name="DeliveryMethod">
                  	<option value=""></option>
                  	<?php
                      foreach(lookup('delivery_methods') as $k => $v) {
                        echo '<option value="' . $k . '">' . $v . '</option>';
                      }
                  	?>
                  </select>
	                <span class="help-block hidden"></span>
	              </div>
	            </div>
	            <div class="col-12">
	              <div class="form-group">
	                <label class="control-label" for="Warranty">Warranty</label>
	                <input type="text" class="form-control" id="Warranty" name="Warranty" placeholder="Warranty">
	                <span class="help-block hidden"></span>
	              </div>
	            </div>
	          </div>
	        </div>
          <input type="hidden" name="Code" id="Code" value="">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>