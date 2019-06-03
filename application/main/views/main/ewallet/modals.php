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
                <label class="control-label" for="Bank">Bank</label>
                <input class="form-control" type="text" name="Bank" id="Bank" placeholder="Bank">
                <span class="help-block hidden"></span>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label class="control-label" for="Branch">Branch</label>
                <input class="form-control"  type="text" name="Branch" id="Branch" placeholder="Branch">
                <span class="help-block hidden"></span>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label class="control-label" for="ReferenceNo">Reference Number</label>
                <input class="form-control"  type="text" name="ReferenceNo" id="ReferenceNo" placeholder="Reference Number">
                <span class="help-block hidden"></span>
              </div>
            </div>
            <div class="col-12 col-sm-6">
              <div class="form-group">
                <label class="control-label" for="Date">Deposit Date</label>
                <input class="form-control"  type="date" name="Date" id="Date" placeholder="Deposit Date">
                <span class="help-block hidden"></span>
              </div>
            </div>
            <div class="col-12 col-sm-6">
              <div class="form-group">
                <label class="control-label" for="Amount">Deposit Amount</label>
                <input class="form-control"  type="number" step=".01" name="Amount" id="Amount" placeholder="Deposit Amount">
                <span class="help-block hidden"></span>
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
          <button type="submit" class="btn bg-b-red text-white">Send Deposit</button>
        </div>
      </form>
    </div>
  </div>
</div>


<div class="modal fade" id="invoiceMessageModal" role="dialog" aria-labelledby="invoiceMessageModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Invoice</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <tbody class="transaction-table"></tbody>
        </table>
        <div class="text-center"><small class="trans-message text-success"></small></div>
      </div>
    </div>
  </div>
</div>