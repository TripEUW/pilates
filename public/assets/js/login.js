"use strict";

// Class Definition
var KTLoginGeneral = function() {

    var login = $('#kt_login');


    var displaySignInForm = function() {
        login.removeClass('kt-login--forgot');
        login.removeClass('kt-login--signup');

        login.addClass('kt-login--signin');
        KTUtil.animateClass(login.find('.kt-login__signin')[0], 'flipInX animated');
        //login.find('.kt-login__signin').animateClass('flipInX animated');
    }

    var displayForgotForm = function() {
        login.removeClass('kt-login--signin');
        login.removeClass('kt-login--signup');

        login.addClass('kt-login--forgot');
        //login.find('.kt-login--forgot').animateClass('flipInX animated');
        KTUtil.animateClass(login.find('.kt-login__forgot')[0], 'flipInX animated');

    }

    var handleFormSwitch = function() {
        $('#kt_login_forgot').click(function(e) {
            e.preventDefault();
            displayForgotForm();
        });

        $('#kt_login_forgot_cancel').click(function(e) {
            e.preventDefault();
            displaySignInForm();
        });

   
    }


    var handleSignInFormSubmit = function() {
        $('#kt_login_signin_submit').click(function(e) {
            e.preventDefault();
            var btn = $(this);
            var form = $(this).closest('form');           

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

 

    var handleForgotFormSubmit = function() {
        $('#kt_login_forgot_submit').click(function(e) {
            e.preventDefault();

            var btn = $(this);
            var form = $(this).closest('form');

            form.validate({
                rules: {
                    email: {
                        required: true,
                        email: true
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
            handleFormSwitch();
            handleSignInFormSubmit();
            handleForgotFormSubmit();

            if(resetForm){
                displayForgotForm();
            }else{
                displaySignInForm();
            }
           
        }
    };
}();

// Class Initialization
jQuery(document).ready(function() {
    KTLoginGeneral.init();
});
