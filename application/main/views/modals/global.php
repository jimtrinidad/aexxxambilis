<div class="modal fade" id="moneyPadalaModal" tabindex="-1" role="dialog" aria-labelledby="moneyPadalaModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="moneyPadalaForm" name="moneyPadalaForm" class="modalForm" action="<?php echo site_url('ewallet/money_padala') ?>">
        <div class="modal-header">
          <strong class="modal-title text-b-red">Money Padala</strong>
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
                <label class="control-label" for="ServiceType">Service Type</label>
                <select class="form-control" name="ServiceType" id="ServiceType">
                  <option value=""></option>
                  <option>SMART MONEY</option>
                  <option>BPI BANKO</option>
                  <option>Globe GCASH</option>
                  <option>PAYMAYA</option>
                  <option>GAMETIME</option>
                  <option>COINS</option>
                  <option>AUTOSWEEP RFID</option>
                  <option>EASYTRIP</option>
                </select>
                <span class="help-block hidden"></span>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label class="control-label" for="AccountNo">Account Number</label>
                <input class="form-control"  type="text" name="AccountNo" id="AccountNo" placeholder="Account Number">
                <span class="help-block hidden"></span>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label class="control-label" for="Identifier">Identifier</label>
                <input class="form-control"  type="text" name="Identifier" id="Identifier" placeholder="Identifier">
                <span class="help-block hidden"></span>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label class="control-label" for="Amount">Amount</label>
                <input class="form-control"  type="number" step=".01" name="Amount" id="Amount" placeholder="Amount">
                <span class="help-block hidden"></span>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn bg-b-red text-white">Request</button>
        </div>
      </form>
    </div>
  </div>
</div>