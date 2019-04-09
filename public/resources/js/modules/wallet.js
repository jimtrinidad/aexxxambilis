function Wallet() {

    // because this is overwritten on jquery events
    var self = this;

    this.itemData = {};

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


    /**
    * add
    */
    this.addDeposit = function()
    {   

        var form  = '#depositForm';
        var modal = '#depositModal';
        Utils.show_form_modal(modal, form, false, function(){

        });

    }


    this.addPayment = function()
    {
        // reset form data
        $('#paymentForm').trigger("reset");

        // reset input erros
        $.each($('#paymentForm').find('input, textarea, select'), function(i,e){
            $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger');
            $(e).popover('destroy');
        });
        //clean error box
        $('#paymentForm').find('#error_message_box .error_messages').html('');
        $('#paymentForm').find('#error_message_box').addClass('hide');

        $('#paymentModal .modal-title').html('<b>Payment</b>');
        $('#paymentModal').modal({
            backdrop : 'static',
            keyboard : false
        });
    }

    this.sendELoad = function()
    {
        // reset form data
        $('#eLoadForm').trigger("reset");

        // reset input erros
        $.each($('#eLoadForm').find('input, textarea, select'), function(i,e){
            $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger');
            $(e).popover('destroy');
        });
        //clean error box
        $('#eLoadForm').find('#error_message_box .error_messages').html('');
        $('#eLoadForm').find('#error_message_box').addClass('hide');

        $('#eLoadModal .modal-title').html('<b>Send eLoad</b>');
        $('#eLoadModal').modal({
            backdrop : 'static',
            keyboard : false
        });
    }

}


var Wallet = new Wallet();
$(document).ready(function(){
    Wallet._init();
});