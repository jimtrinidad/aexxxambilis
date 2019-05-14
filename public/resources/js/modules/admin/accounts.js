function Accounts() {

    // because this is overwritten on jquery events
    var self = this;

    // initialize module variables

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

        $('.modalForm').submit(function(e) {
            e.preventDefault();
            Utils.save_form(this);
        });

        $('.accountStatusToggle').change(function(e){
            self.updateAccountStatus(this);
        });

    }

    /**
    * usage of different libraries and plugins
    */
    this.set_configs = function()
    {
        
    }

    this.updateAccountStatus = function(elem)
    {
        var checkbox    = $(elem);
        var data        = checkbox.data();
        var status      = checkbox.is(":checked");
        $.ajax({
            url: window.base_url('accounts/update_status/' + data.code),
            type: 'get',
            data: {'status' : status},
            success: function (response) {
                if (!response.status) {
                    // failed
                    bootbox.alert(response.message, function(){
                        location.reload();
                    })
                }
            }
        });
    }

}


var Accounts = new Accounts();
$(document).ready(function(){
    Accounts._init();
});