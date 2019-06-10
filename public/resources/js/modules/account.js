function Account() {
    // because this is overwritten on jquery events
    var self = this;

    this.info,
    this.address,

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

        $('#updateProfileForm').submit(function(e) {
            e.preventDefault();
            Utils.save_form(this, function(){
                location.reload();
            });
        });

        $('#forgotPasswordForm').submit(function(e) {
            e.preventDefault();
            Utils.save_form(this, function(response){
                bootbox.alert(response.message, function(){
                    window.location = window.base_url('account/signin'); 
                });
            }, function(response) {
                // reset captcha
                Utils.show_form_errors($('#forgotPasswordForm'), response.fields, response.message);
                grecaptcha.reset();
            });
        });

        $('#resetPasswordForm').submit(function(e) {
            e.preventDefault();
            Utils.save_form(this, function(response){
                var cont = $('#resetPasswordForm').parent();
                $('#resetPasswordForm').remove();
                cont.html(`<div class="row justify-content-center">
                    <div class="col-12 col-md-8">
                        <h4>Reset Password</h4>
                        <div class="alert alert-success" role="alert">
                          Password has been changed successfully.
                        </div>
                        <button type="button" onclick="window.location='${window.base_url('account/signin')}'" name="" class="btn btn-success">Go to Login</button>
                    </div>
                </div>`);
            });
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
                    window.location = response.redirect; 
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
                    Utils.show_form_errors(e, response.fields, response.message);
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


    this.toggle_account_no = function(elem)
    {
        if ($(elem).hasClass('show')) {
            $(elem).removeClass('show').addClass('notshow').html('<strong>SHOW</strong>');
            $(elem).parent('div').find('.account_no_holder').find('strong').toggleClass('hide');
        } else {
            $(elem).parent('div').find('.account_no_holder').find('strong').toggleClass('hide');
            $(elem).removeClass('notshow').addClass('show').html('<strong>HIDE</strong>');
        }
    }


    this.editProfile = function()
    {
        var form  = '#updateProfileForm';
        var modal = '#updateProfileModal';
        Utils.show_form_modal(modal, form, false, function(){
            if (self.info) {
                $(form).find('.image-preview').prop('src', self.info.photo);
                Utils.set_form_input_value(form, self.info);
            }
        });
    }

    this.applyAsAgent = function()
    {
        var form  = '#deliveryAgentApplicationForm';
        var modal = '#deliveryAgentApplicationModal';
        Utils.show_form_modal(modal, form, false, function(){
            Utils.set_form_input_value(form, self.info);
            $(form).find('.custom-file-label').text('Choose file');
        });
    }

}

var Account = new Account();
$(document).ready(function(){
    Account._init();
});