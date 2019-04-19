function General() {

    // because this is overwritten on jquery events
    var self = this;

    /**
     * Initialize events
     */
    this._init = function() 
    {

        self.set_events();
        self.set_configs();

    }

    /**
    * events delaration
    */
    this.set_events = function()
    {

        $('#userAddressForm').submit(function(e) {
            e.preventDefault();
            Utils.save_form(this, function() {
                location.reload();
            });
        });

    }

    /**
    * usage of different libraries and plugins
    */
    this.set_configs = function()
    {
        
    }

    this.addUserAddress = function()
    {   

        var form  = '#userAddressForm';
        var modal = '#userAddressModal';
        Utils.show_form_modal(modal, form, 'Add Address', function(){
            
        });

    }


    this.loadCityOptions = function(target, e, baragay_target)
    {
        $(target).html(window.emptySelectOption).prop('disabled', true);
        $(baragay_target).html(window.emptySelectOption).prop('disabled', true);

        $.get(window.public_url('get/city'), {'provCode' : $(e).val()}).done(function(response) {
            if (response.status) {
                var options = window.emptySelectOption;
                $.each(response.data, function(i, e){
                    options += '<option value="' + e.citymunCode + '">' + e.citymunDesc + '</option> \n';
                });
                $(target).html(options).prop('disabled', false);
            } else {
                $(target).html(window.emptySelectOption);
            }
        });
    }

    this.loadBarangayOptions = function(target, e)
    {
        $(target).html(window.emptySelectOption).prop('disabled', true);
        $.get(window.public_url('get/barangay'), {'citymunCode' : $(e).val()}).done(function(response) {
            if (response.status) {
                var options = window.emptySelectOption;
                $.each(response.data, function(i, e){
                    options += '<option value="' + e.brgyCode + '">' + e.brgyDesc + '</option> \n';
                });
                $(target).html(options).prop('disabled', false);
            } else {
                $(target).html(window.emptySelectOption);
            }
        });
    }

}


var General = new General();
$(document).ready(function(){
    General._init();
});