"use strict";

// Class Definition
var KTLoginGeneral = function() {

    var login = $('#kt_login');

    var displayResetPassForm = function() {
        login.removeClass('kt-login--forgot');
        login.removeClass('kt-login--signin');
        login.addClass('kt-login--signup');
        KTUtil.animateClass(login.find('.kt-login__signup')[0], 'flipInX animated');
    }


    var handleResetPassFormSubmit = function() {
        $('#kt_login_reset_pass_submit').click(function(e) {
            e.preventDefault();

            var btn = $(this);
            var form = $(this).closest('form');

            form.validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true
                    },
                    password_confirmation: {
                        required: true
                    }
                  
                }
            });
         
            if (!form.valid()) {
                return;
            }else{
                btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);
                form.submit();
            }
        });
    }

    // Public Functions
    return {
        // public functions
        init: function() {
            handleResetPassFormSubmit();
            displayResetPassForm();
    
        }
    };
}();

// Class Initialization
jQuery(document).ready(function() {
    KTLoginGeneral.init();
});
