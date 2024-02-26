//------------------------------------------------------------------------------------
//						CONTACT FORM VALIDATION'S SETTINGS
//------------------------------------------------------------------------------------
$('.signin_form').validate({
    onfocusout: false,
    onkeyup: false,
    rules: {
        username: "required",
        password: {
            required: true,
            password: true
        },
        type: "required",
        check: "required",
        radio: "required"
    },
    errorPlacement: function (error, element) {

        if ((element.attr("type") == "radio") || (element.attr("type") == "checkbox")) {
            error.appendTo($(element).parents("div").eq(0));
        } else if (element.is("select")) {
            error.appendTo($(element).parents("div").eq(1));
        } else {
            error.insertAfter(element);
        }
    },
    messages: {
        username: "What's your name?",
        password: {
            required: "What's your password?",
            password: "Please, enter a valid password"
        },
        type: "Please enter car type",
        check: "Please check box",
        radio: "Please choose radio button"
    }
});
