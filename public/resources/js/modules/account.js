function Account() {
    // because this is overwritten on jquery events
    var self = this;

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
        $('#loginForm').submit(function(e) {
            e.preventDefault();
            self.login(this);
        });

        /**
        * registration submit
        */
        $('#registrationForm').submit(function(e) {
            e.preventDefault();
            self.register(this);
        });

    }

    /**
    * usage of different libraries and plugins
    */
    this.set_configs = function()
    {

    },


    /**
    * login request
    */
    this.login = function(e)
    {
        
        // prenvet multiple calls
        if ($(e).data('running')) {
            return false;
        }
        $(e).data('running', true);
        $(e).LoadingOverlay("show", {
            background              : "rgba(255, 255, 255, 0.1)"
        })

        Utils.append_csrf_token(e);
        var formData = new FormData(e);

        $.ajax({
            url: $(e).prop('action'),
            type: 'POST',
            data: formData,
            success: function (response) {
                if (response.status) {
                    $('#error_message_box').text(response.message).addClass('hide');
                    window.location = window.base_url(); 
                } else {
                    $('#password').val('');
                    $('#error_message_box').text(response.message).removeClass('hide');
                }
            },
            complete: function() {
                $(e).LoadingOverlay("hide");
                $(e).data('running', false);
            },
            cache: false,
            contentType: false,
            processData: false
        });

        // $.post(e.prop('action'), e.serialize()).done(function(response) {
        //     if (response.status) {
        //         // $('html').css('background', 'white').find('.modal-overs').remove();
        //         $('#error_message_box').text(response.message).addClass('hide');
        //         window.location = window.base_url(); 
        //     } else {
        //         $('#password').val('');
        //         $('#error_message_box').text(response.message).removeClass('hide');

        //         // no need to remove loading on success, let it redirect
        //         $('.box-content').LoadingOverlay("hide");
        //         $(e).data('running', false);
        //     }
        // });

    },

    /**
    * register request
    */
    this.register = function(e)
    {
        // prenvet multiple calls
        if ($(e).data('running')) {
            return false;
        }
        $(e).data('running', true);

        $('.modal-content').LoadingOverlay("show", {
            background              : "rgba(255, 255, 255, 0.1)"
        })


        Utils.append_csrf_token(e);
        var formData = new FormData(e);
        
        // reset input erros
        Utils.reset_form_errors(e);
        
        //clean error box
        // $('#error_message_box .error_messages').html('');
        // $('#error_message_box').addClass('hide');

        $.ajax({
            url: $(e).prop('action'),
            type: 'POST',
            data: formData,
            success: function (response) {
                if (response.status) {
                    $('#error_message_box').addClass('hide');
                    bootbox.alert(response.message, function(){
                        window.location = window.base_url('account/signin'); 
                    });
                } else {
                    // bootbox.alert(response.message);
                    Utils.show_form_errors(e, response.fields);
                }
            },
            complete: function() {
                $('.modal-content').LoadingOverlay("hide");
                $(e).data('running', false);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    }

}

var Account = new Account();
$(document).ready(function(){
    Account._init();
});