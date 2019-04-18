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

        $('#billerLogoForm').submit(function(e) {
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

    this.updateBillerLogo = function(biller_code)
    {   

        var form  = '#billerLogoForm';
        var modal = '#billerLogoModal';
        Utils.show_form_modal(modal, form, 'Update Biller Logo', function(){
            $(form).find('.image-preview').prop('src', $('#biller_' + biller_code).find('.logo-small').prop('src'));
            $(form).find('#Code').val(biller_code);
        });

    }

}


var General = new General();
$(document).ready(function(){
    General._init();
});