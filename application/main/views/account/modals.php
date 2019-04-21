<div class="modal fade" id="updateProfileModal" tabindex="-1" role="dialog" aria-labelledby="updateProfileModal">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
      <form id="updateProfileForm" name="updateProfileForm" class="modalForm" action="<?php echo site_url('account/save_profile') ?>">
        <input type="hidden" name="user_id" id="user_id">
        <div class="modal-header">
          <strong class="modal-title text-b-red">Update Profile</strong>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="error_message_box" class="hide">
            <div class="error_messages alert alert-danger text-danger" role="alert"></div>
          </div>

          <div class="row gutter-5">
            <div class="col-4 logo text-center">
              <div class="image-upload-container">
                <img class="image-preview" src="<?php echo public_url(); ?>assets/profile/default.jpg">
                <span class="hiddenFileInput hide">
                  <input type="file" data-default="<?php echo public_url(); ?>assets/profile/default.jpg" accept="image/*" class="image-upload-input" id="account_photo" name="account_photo"/>
                </span>
              </div>
            </div>
            <div class="col-8">
              <div class="row gutter-5">
                <div class="col-12">
                  <div class="form-group">
                    <label class="control-label" for="account_firstname">Firstname</label>
                    <input type="text" class="form-control" name="account_firstname" id="account_firstname" placeholder="Firstname">
                    <span class="help-block hidden"></span>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label class="control-label" for="account_lastname">Lastname</label>
                    <input type="text" class="form-control" name="account_lastname" id="account_lastname" placeholder="Lastname">
                    <span class="help-block hidden"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row gutter-5">
            <div class="col-12">
              <label class="control-label" for="account_mobile">Mobile</label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1">+63</span>
                </div>
                <input type="text" name="account_mobile" id="account_mobile" class="form-control" placeholder="10 digits number. eg:9171234567" maxlength="10" aria-label="Mobile" aria-describedby="basic-addon1">
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label class="control-label" for="account_email">Email address</label>
                <input type="email" name="account_email" id="account_email" class="form-control" placeholder="Email address" readonly onfocus="this.removeAttribute('readonly');">
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label class="control-label" for="account_bank_name">Bank name</label>
                <input type="text" class="form-control" name="account_bank_name" id="account_bank_name" placeholder="Bank name">
                <span class="help-block hidden"></span>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label class="control-label" for="account_bank_no">Account Number</label>
                <input type="text" class="form-control" name="account_bank_no" id="account_bank_no" placeholder="Account number">
                <span class="help-block hidden"></span>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label class="control-label" for="account_bank_account_name">Account Name</label>
                <input type="text" class="form-control" name="account_bank_account_name" id="account_bank_account_name" placeholder="Account name">
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