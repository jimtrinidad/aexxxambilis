function Store() {
    // because this is overwritten on jquery events
    var self = this;
    
    this.itemData = {};
    this.profile = false;
    this.categories = [];
    this.sub_categories = [];

    /**
     * Initialize events
     */
    this._init = function()
    {

        self.set_events();
		self.set_configs();

    },

    /**
    * events delaration
    */
    this.set_events = function()
    {

        /**
        * login submit
        */
        $('.modalForm').submit(function(e) {
            e.preventDefault();
            Utils.save_form(this);
        });

    }

    /**
    * usage of different libraries and plugins
    */
    this.set_configs = function()
    {

    },

    this.getData = function(id)
    {   
        var match = false;
        $.each(self.itemData, function(i,e){
            if (e.id == id) {
                match = e;
                return false;
            }
        });

        return match;
    }

    this.updateProfile = function()
    {
        console.log(self.profile);
        var form  = '#storeProfileForm';
        var modal = '#storeProfileModal';
        Utils.show_form_modal(modal, form, false, function(){
            if (self.profile) {
                Utils.set_form_input_value(form, {
                    'Name'    : self.profile.Name,
                    'Address' : self.profile.Address,
                    'Contact' : self.profile.Contact,
                    'Email'   : self.profile.Email,
                });
            }
        });
    }


    // product setup - prepare sub category on category change
    this.get_sub_categories = function(category, selected = false) 
    {
        console.log(self.sub_categories[category]);
        $('#itemForm').find('#SubCategory').html('<option value=""></option>');
        if (typeof(self.sub_categories[category]) !== 'undefined') {
            $.each(self.sub_categories[category], function(i, e) {
                var selected_text = '';
                if (selected && selected == e.id) {
                    selected_text = 'selected="selected"';
                }
                $('#itemForm').find('#SubCategory').append(`<option value="${e.id}" ${selected_text} >${e.Name}</option>`);
            });
        }
    }


    this.addProduct = function()
    {
        var form  = '#itemForm';
        var modal = '#itemModal';
        Utils.show_form_modal(modal, form, 'Add Store Product', function(){
            $(form).find('.image-preview').prop('src', window.public_url() + 'assets/products/default.png');
            $(form).find('#SearchKeywords').tagsinput('destroy');
            $(form).find('#SearchKeywords').tagsinput();
            $(form).find('#Description').summernote('destroy');
            $(form).find('#Description').summernote('reset');
        });
    }

    this.editProduct = function(id)
    {
        var data  = self.getData(id);

        if (data) {

            var form  = '#itemForm';
            var modal = '#itemModal';
            Utils.show_form_modal(modal, form, 'Update Product', function(){
                Utils.set_form_input_value(form, data);
                $('#itemForm .image-preview').prop('src', window.public_url() + 'assets/products/default.png');
                if (data.Image) {
                    $('#itemForm .product_image').prop('src', window.public_url() + 'assets/products/' + data.Image);
                }
                if (data.PartnerImage) {
                    $('#itemForm .partner_image').prop('src', window.public_url() + 'assets/products/' + data.PartnerImage);
                }

                self.get_sub_categories(data.Category, data.SubCategory);

                $(form).find('#SearchKeywords').tagsinput('destroy');
                $(form).find('#SearchKeywords').tagsinput();
                $(form).find('#Description').summernote('destroy');
                $(form).find('#Description').summernote();
            });
        }
    }

    this.deleteProduct = function(id)
    {
        var data = self.getData(id);
        if (data) {
            bootbox.confirm('Are you sure you want to <label class="label label-danger">delete</label> ' + data.Name, function(r){
                if (r) {
                    $.LoadingOverlay("show", {zIndex: 999});
                    $.ajax({
                        url: window.base_url('store/deleteitem/' + data.Code),
                        type: 'GET',
                        success: function (response) {
                            if (response.status) {
                                bootbox.alert(response.message, function(){
                                    location.reload(); //easy way, just reload the page
                                });
                            } else {
                                $.LoadingOverlay("hide");
                            }
                        }
                    });
                }
            });
        }
    }

}

var Store = new Store();
$(document).ready(function(){
    Store._init();
});