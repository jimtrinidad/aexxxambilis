function General() {

    // because this is overwritten on jquery events
    var self = this;

    this.address,

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

    this.editUserAddress = function()
    {
        var form  = '#userAddressForm';
        var modal = '#userAddressModal';
        Utils.show_form_modal(modal, form, 'Edit Address', function() {
            $(form).find('#AddressCity').prop('disabled', false);
            $(form).find('#AddressBarangay').prop('disabled', false);
            Utils.set_form_input_value(form, {
                AddressID       : self.address.id,
                AddressProvince : self.address.Province,
                AddressCity     : self.address.City,
                AddressBarangay : self.address.Barangay,
                AddressStreet   : self.address.Street,
            });

            self.loadCityOptions('#AddressCity', '#AddressProvince', '#AddressBarangay', self.address.City, function(){
                self.loadBarangayOptions('#AddressBarangay', '#AddressCity', self.address.Barangay);
            });
        });
    }


    this.loadCityOptions = function(target, e, baragay_target, selected = false, callback = false)
    {
        $(target).html(window.emptySelectOption).prop('disabled', true);
        $(baragay_target).html(window.emptySelectOption).prop('disabled', true);

        $.LoadingOverlay("show");

        $.get(window.public_url('get/city'), {'provCode' : $(e).val()}).done(function(response) {
            if (response.status) {
                var options = window.emptySelectOption;
                $.each(response.data, function(i, e){
                    options += '<option value="' + e.citymunCode + '" ' + (selected && selected == e.citymunCode ? 'selected' : '') + '>' + e.citymunDesc + '</option> \n';
                });
                $(target).html(options).prop('disabled', false);
            } else {
                $(target).html(window.emptySelectOption);
            }

            if (callback) {
                callback();
            }

            $.LoadingOverlay("hide");
        });
    }

    this.loadBarangayOptions = function(target, e, selected = false, callback = false)
    {
        console.log(selected);
        $(target).html(window.emptySelectOption).prop('disabled', true);

        $.LoadingOverlay("show");

        $.get(window.public_url('get/barangay'), {'citymunCode' : $(e).val()}).done(function(response) {
            if (response.status) {
                var options = window.emptySelectOption;
                $.each(response.data, function(i, e){
                    options += '<option value="' + e.brgyCode + '" ' + (selected && selected == e.brgyCode ? 'selected' : '') + '>' + e.brgyDesc + '</option> \n';
                });
                $(target).html(options).prop('disabled', false);
            } else {
                $(target).html(window.emptySelectOption);
            }

            if (callback) {
                callback();
            }

            $.LoadingOverlay("hide");
        });
    }

}


var General = new General();
$(document).ready(function(){
    General._init();
});