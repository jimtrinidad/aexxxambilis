<div class="modal fade" id="billerLogoModal" tabindex="-1" role="dialog" aria-labelledby="billerLogoModal">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <form id="billerLogoForm" name="billerLogoForm" class="modalForm" action="<?php echo site_url('billers/save_biller_logo') ?>" enctype="multipart/form-data">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
          <div id="error_message_box" class="hide row">
            <br>
            <div class="error_messages no-border-radius alert alert-danger" role="alert"></div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-xs-12 logo padding-top-10">
                <div class="image-upload-container">
                  <img class="image-preview" src="<?php echo public_url(); ?>assets/uploads/default.png">
                  <span class="hiddenFileInput hide">
                    <input type="file" data-default="<?php echo public_url(); ?>assets/uploads/default.png" accept="image/*" class="image-upload-input" id="Logo" name="Logo"/>
                  </span>
                </div>
              </div>
            </div>
          </div>
          <input type="hidden" id="Code" name="Code">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="encashServiceModal" tabindex="-1" role="dialog" aria-labelledby="encashServiceModal">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <form id="encashServiceForm" name="encashServiceForm" class="modalForm" action="<?php echo site_url('billers/save_encash_service') ?>" enctype="multipart/form-data">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
          <div id="error_message_box" class="hide row">
            <br>
            <div class="error_messages no-border-radius alert alert-danger" role="alert"></div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-xs-12 logo padding-top-10">
                <div class="image-upload-container">
                  <img class="image-preview" src="<?php echo public_url(); ?>assets/uploads/default.png">
                  <span class="hiddenFileInput hide">
                    <input type="file" data-default="<?php echo public_url(); ?>assets/uploads/default.png" accept="image/*" class="image-upload-input" id="Logo" name="Logo"/>
                  </span>
                </div>
              </div>
              <div class="col-xs-12 logo padding-top-10">
                <div class="form-group">
                  <label class="control-label" for="service_name">Name</label>
                  <input type="text" class="form-control" name="service_name" id="service_name" placeholder="Name to appear on Menu">
                </div>
              </div>
              <div class="col-xs-12 logo padding-top-10">
                <div class="form-group">
                  <label class="control-label" for="service_description">Description</label>
                  <!-- <input type="text" class="form-control" name="service_description" id="service_description" placeholder="Description"> -->
                  <textarea class="form-control" id="service_description" name="service_description"></textarea>
                </div>
              </div>
            </div>
          </div>
          <input type="hidden" id="Code" name="Code">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>