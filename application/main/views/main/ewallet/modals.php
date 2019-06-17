<div class="modal fade" id="depositModal" tabindex="-1" role="dialog" aria-labelledby="depositModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="depositForm" name="depositForm" class="modalForm" action="<?php echo site_url('ewallet/add_deposit') ?>">
        <div class="modal-header">
          <strong class="modal-title text-b-red">Fund My Wallet</strong>
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
                <label class="control-label" for="Bank">Payment Partner</label>
                <input class="form-control" type="text" name="Bank" id="Bank" placeholder="Payment Partner">
                <span class="help-block hidden"></span>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label class="control-label" for="Branch">Location</label>
                <input class="form-control"  type="text" name="Branch" id="Branch" placeholder="Location">
                <span class="help-block hidden"></span>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label class="control-label" for="ReferenceNo">Control / Transaction No.</label>
                <input class="form-control"  type="text" name="ReferenceNo" id="ReferenceNo" placeholder="Control/Transaction No.">
                <span class="help-block hidden"></span>
              </div>
            </div>
            <div class="col-12 col-sm-6">
              <div class="form-group">
                <label class="control-label" for="Date">Transaction Date</label>
                <input class="form-control"  type="date" name="Date" id="Date" placeholder="Transaction Date">
                <span class="help-block hidden"></span>
              </div>
            </div>
            <div class="col-12 col-sm-6">
              <div class="form-group">
                <label class="control-label" for="Amount">Fund Amount</label>
                <input class="form-control"  type="number" step=".01" name="Amount" id="Amount" placeholder="Fund Amount">
                <span class="help-block hidden"></span>
              </div>
            </div>
            <div class="col-12">
              <label class="control-label" for="Amount">Screenshot / Deposit Slip</label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text">Image</span>
                </div>
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="Photo" name="Photo" accept="image/*">
                  <label class="custom-file-label text-truncate" for="valid_id_two">Browse</label>
                </div>
              </div>
            </div>
          </div>
          <div class="row gutter-5">
            <div class="col-12">
              <?php
              $setting = $this->appdb->getRowObject('Settings', 'fund_wallet_instruction', 'key');
              if ($setting) {
                echo $setting->value;
              }
              ?>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn bg-b-red text-white">Add Fund</button>
        </div>
      </form>
    </div>
  </div>
</div>