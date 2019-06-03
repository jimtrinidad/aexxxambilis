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
            var _this = this;
            bootbox.confirm({
                message: "CONFIRM TRANSACTION?",
                buttons: {
                    confirm: {
                        label: 'Confirm',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'Cancel',
                        className: 'btn-danger'
                    }
                },
                callback: function (r) {
                    if (r) {
                        Utils.save_form(_this, function(res) {
                            $('.modalForm').closest('div.modal').modal('hide');
                            $('#successMessageModal .trans-image-header').prop('src', res.image);
                            $('#successMessageModal .trans-message').text(res.message);
                            var table = $('#successMessageModal .transaction-table');
                            table.html('');
                            $.each(res.data, function(i,e) {
                                table.append(`<tr><td>${i}</td><td>${e}</td></tr>`);
                            });
                            $('#successMessageModal').modal({
                                backdrop : 'static',
                                keyboard : false
                            });
                        });
                    }
                }
            });
        });

        var ajaxReq = null;
        $('#encashForm #Amount').on('keyup change',function() {
            if (ajaxReq != null) ajaxReq.abort();
            ajaxReq =  $.ajax({
                url: window.public_url('get/n2w/' + $(this).val()),
                success: function(response){
                    $('#encashForm').find('.number_in_words').text(response);
                 }
            });
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

    /**
    * encash
    */
    this.encashRequest = function()
    {   

        var form  = '#encashForm';
        var modal = '#encashModal';
        Utils.show_form_modal(modal, form, false, function(){

        });

    }

    /**
    * padala
    */
    this.moneyPadalaRequest = function(id)
    {   

        var data  = self.getData(id);
        if (data) {
            var form  = '#moneyPadalaForm';
            var modal = '#moneyPadalaModal';
            Utils.show_form_modal(modal, form, data.Name, function(){
                $(form).find('#ServiceType').val(data.Code);
                $(form).find('#AccountNo').prop('placeholder', data.FirstField);
                $(form).find('#AccountNoLabel').text(data.FirstField);
                $(form).find('#Identifier').prop('placeholder', data.SecondField);
                $(form).find('#IdentifierLabel').text(data.SecondField);
            });
        }
        // var form  = '#moneyPadalaForm';
        // var modal = '#moneyPadalaModal';
        // Utils.show_form_modal(modal, form, false, function(){

        // });

    }

    /**
    * eload
    */
    this.eloadRequest = function(telco)
    {   

        var form  = '#eloadForm';
        var modal = '#eloadModal';

        if (typeof(self.itemData[telco]) != 'undefined') {
            var data  = self.itemData[telco];
            
            if ($(form).find('#LoadTag').hasClass("select2-hidden-accessible")) {
                $(form).find('#LoadTag').select2('destroy');
            }

            Utils.show_form_modal(modal, form, telco + ' Load Transaction', function(){
                var options = window.emptySelectOption;
                $.each(data, function(i, e){
                    options += `<option data-amount="${e.Denomination}" value="${e.Code}">${e.TelcoTag + ' - P' +e.Denomination}</option>`;
                });
                $(form).find('#LoadTag').html(options).prop('disabled', false).select2({
                    width: 'style',
                    theme: 'bootstrap4',
                    placeholder: $(this).attr('placeholder'),
                }).change(function(){
                    $(form).find('#Amount').val($(this).find('option:selected').data('amount'));
                });
            });
        }

    }


    this.payBills = function(id)
    {

        var data  = self.getData(id);
        if (data) {
            var form  = '#paymentForm';
            var modal = '#paymentModal';
            Utils.show_form_modal(modal, form, data.Description, function(){
                $(form).find('#Biller').val(data.Code);
                $(form).find('#AccountNo').prop('placeholder', data.FirstField).prop('maxlength', data.FirstFieldWidth);
                $(form).find('#AccountNoLabel').text(data.FirstField);
                $(form).find('#Identifier').prop('placeholder', data.SecondField).prop('maxlength', data.SecondFieldWidth);
                $(form).find('#IdentifierLabel').text(data.SecondField);

                if (data.ServiceCharge + 0 > 0) {
                    $(form).find('.con-fee-cont').show();
                    $(form).find('.con-fee').text(data.ServiceCharge);
                } else {
                    $(form).find('.con-fee-cont').hide();
                }
            });
        }
    }

    this.viewInvoice = function(id)
    {
        var data  = self.getData(id);
        if (data) {
            var table = $('#invoiceMessageModal .transaction-table');
            table.html('');
            $.each($.parseJSON(data.InvoiceData), function(i,e) {
                table.append(`<tr><td>${i}</td><td>${e}</td></tr>`);
            });
            $('#invoiceMessageModal').modal({
                backdrop : 'static',
                keyboard : false
            });
        }
    }

}


var Wallet = new Wallet();
$(document).ready(function(){
    Wallet._init();
});