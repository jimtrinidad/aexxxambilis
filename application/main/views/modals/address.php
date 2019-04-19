<div class="modal fade" id="userAddressModal" tabindex="-1" role="dialog" aria-labelledby="userAddressModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="userAddressForm" name="userAddressForm" class="modalForm" action="<?php echo site_url('account/save_address') ?>">
        <div class="modal-header">
          <strong class="modal-title text-b-red"></strong>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="error_message_box" class="hide">
            <div class="error_messages alert alert-danger text-danger" role="alert"></div>
          </div>
          <div class="row gutter-5">
            <div class="col-12">
              <div class="form-group">
                <label class="control-label" for="AddressProvince">Province</label>
                <select id="AddressProvince" name="AddressProvince" class="form-control" onChange="General.loadCityOptions('#AddressCity', this, '#AddressBarangay')">
                  <option value="">--</option>
                  <?php
                    foreach (lookup_all('UtilLocProvince', false, 'provDesc', false) as $v) {
                      echo "<option value='" . $v['provCode'] . "'>" . $v['provDesc'] . "</option>";
                    }
                  ?>
                </select>
                <span class="help-block hidden"></span>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label class="control-label" for="AddressCity">City/Municipality</label>
                <select id="AddressCity" disabled="disabled" name="AddressCity" class="form-control" onChange="General.loadBarangayOptions('#AddressBarangay', this)">
                  <option value="">--</option>
                </select>
                <span class="help-block hidden"></span>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label class="control-label" for="AddressBarangay">Barangay</label>
                <select id="AddressBarangay" disabled="disabled" name="AddressBarangay" class="form-control">
                  <option value="">--</option>
                </select>
                <span class="help-block hidden"></span>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label class="control-label" for="AddressStreet">House Number, Building and Street Name</label>
                <input class="form-control" type="text" name="AddressStreet" id="AddressStreet" placeholder="House Number, Building and Street Name">
                <span class="help-block hidden"></span>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn bg-b-red text-white">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>