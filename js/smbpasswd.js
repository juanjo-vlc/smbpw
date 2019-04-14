var strength = {
    0: "Strength: Unsafe",
    1: "Strength: Low",
    2: "Strenght: Acceptable",
    3: "Strenght: Medium",
    4: "Strenght: Good"
  }

var password = document.getElementById('newpassword');
var repeatpassword = document.getElementById('repeatpassword');
var submitbtn = document.getElementById('submitbtn');
var meter = document.getElementById('password-strength-meter');
var text = document.getElementById('password-strength-text');

password.addEventListener('input', function() {
    var val = password.value;
    var result = zxcvbn(val);

    // Update the password strength meter
    meter.value = result.score;

    // Update the text indicator
    if(val !== "") {
        text.innerHTML = "<strong>" + strength[result.score] + "</strong>"
    }
    else {
        text.innerHTML = "";
    }
    if (result.score < 3 || (repeatpassword.value == "") || (password.value != repeatpassword.value)) {
        submitbtn.disabled=true;
    }
    else {
        submitbtn.disabled=false;
    }

});

repeatpassword.addEventListener('input', function() {
    if (meter.score < 3 || (repeatpassword.value == "") || (password.value != repeatpassword.value)) {
        submitbtn.disabled=true;
    }
    else {
        submitbtn.disabled=false;
    }

});

//https://jqueryvalidation.org/equalTo-method/

jQuery("#mainform").validate({
    rules: {
        repeatpassword: {equalTo: "#newpassword"},
        username: {pattern: /^[a-z][0-9a-z\.]*[a-z0-9]+$/}
    },
    errorClass: "text-danger",
    errorElement: "span",
    success: "valid",
    highlight: function (element, errorClass, validClass) {
        $(element).parents('.form-group').addClass(errorClass);
        $(element).parents('.form-group').removeClass(validClass);
        $(element).addClass('is-invalid');
        $(element).removeClass('is-valid');
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).parents('.form-group').removeClass(errorClass);
        $(element).parents('.form-group').addClass(validClass);
        $(element).removeClass('is-invalid');
        $(element).addClass('is-valid');
    },
});
