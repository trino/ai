String.prototype.isEqual = function (str){
    if(isUndefined(str)){return false;}
    if(isNumeric(str) || isNumeric(this)){return this == str;}
    return this.toUpperCase().trim() == str.toUpperCase().trim();
};
function isUndefined(variable){
    return typeof variable === 'undefined';
}
function isArray(variable){
    return Array.isArray(variable);
}
//returns true if $variable appears to be a valid number
function isNumeric(variable){
    return !isNaN(Number(variable));
}
//returns true if $variable appears to be a valid object
//typename (optional): the $variable would also need to be of the same object type (case-sensitive)
function isObject(variable, typename){
    if (typeof variable == "object"){
        if(isUndefined(typename)) {return true;}
        return variable.getName().toLowerCase() == typename.toLowerCase();
    }
    return false;
}

String.prototype.contains = function (str){
    return this.toLowerCase().indexOf(str.toLowerCase()) > -1;
};
String.prototype.endswith = function(str) {
    return this.right(str.length).isEqual(str);
};
//returns the left $n characters of a string
String.prototype.left = function(n) {
    return this.substring(0, n);
};

//returns the right $n characters of a string
String.prototype.right = function(n) {
    return this.substring(this.length-n);
};

//returns true if $variable appears to be a valid function
function isFunction(variable) {
    var getType = {};
    return variable && getType.toString.call(variable) === '[object Function]';
}

//replaces all instances of $search within a string with $replacement
String.prototype.replaceAll = function (search, replacement) {
    var target = this;
    if(isArray(search)){
        for(var i=0; i<search.length; i++){
            if(isArray(replacement)){
                target = target.replaceAll( search[i], replacement[i] );
            } else {
                target = target.replaceAll( search[i], replacement );
            }
        }
        return target;
    }
    return target.replace(new RegExp(search, 'g'), replacement);
};

//make a cookie value that expires in exdays
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 86400000));//24 * 60 * 60 * 1000
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

//gets a cookie value
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
    }
    return "";
}

//deletes a cookie value
function removeCookie(cname) {
    if(isUndefined(cname)){//erase all cookies
        var cookies = document.cookie.split(";");
        for (var i = 0; i < cookies.length; i++) {
            removeCookie(cookies[i].split("=")[0]);
        }
    } else {
        setCookie(cname, '', -10);
    }
}

//creates a cookie value that expires in 1 year
function createCookieValue(cname, cvalue) {
    //log("Creating cookie value: '" + cname + "' with: " + cvalue);
    setCookie(cname, cvalue, 365);
}

function log(text){
    console.log(text);
}

function getform(ID){
    var data = $(ID).serializeArray();
    var ret = {};
    for(var i=0; i<data.length; i++){
        ret[ data[i].name ] = data[i].value;
    }
    return ret;
}

function removeindex(arr, index, count, delimiter){
    if(!isArray(arr)){
        if(isUndefined(delimiter)){delimiter=" ";}
        arr = removeindex(arr.split(delimiter), index, count, delimiter).join(delimiter);
    } else {
        if(isNaN(index)){index = hasword(arr, index);}
        if (index > -1 && index < arr.length) {
            if (isUndefined(count)) {count = 1;}
            arr.splice(index, count);
        }
    }
    return arr;
}

function outerHTML(selector){
    return $(selector)[0].outerHTML;
}

function visible(selector, status){
    if(isUndefined(status)){status = false;}
    if(status){
        $(selector).show();
    } else {
        $(selector).hide();
    }
}

$.validator.addMethod('phonenumber', function (Data, element) {
    Data = Data.replace(/\D/g, "");
    if(Data.substr(0,1)=="0"){return false;}
    return Data.length == 10;
}, "Please enter a valid phone number");

$.validator.addMethod('creditcard', function (value, element) {
    var nCheck = 0, nDigit = 0, bEven = false, desiredlength = 16;
    if(value.contains("-XXXX-XXXX-") && element.hasAttribute("allowblank")){return true;}//placeholder data
    value = value.replace(/\D/g, "");
    if (value.left(1) == 3){desiredlength=15;}//amex
    if (value.length != desiredlength){return false;}
    for (var n = value.length - 1; n >= 0; n--) {
        var cDigit = value.charAt(n), nDigit = parseInt(cDigit, 10);
        if (bEven) {if ((nDigit *= 2) > 9) nDigit -= 9;}
        nCheck += nDigit;
        bEven = !bEven;
    }
    return (nCheck % 10) == 0;
}, "Please enter a valid credit card number");

var decodeEntities = (function() {
    // this prevents any overhead from creating the object each time
    var element = document.createElement('div');
    function decodeHTMLEntities (str) {
        if(str && typeof str === 'string') {
            // strip script/html tags
            str = str.replace(/<script[^>]*>([\S\s]*?)<\/script>/gmi, '');
            str = str.replace(/<\/?\w(?:[^"'>]|"[^"]*"|'[^']*')*>/gmi, '');
            element.innerHTML = str;
            str = element.textContent;
            element.textContent = '';
        }

        return str;
    }
    return decodeHTMLEntities;
})();

function findwhere(data, key, value){
    for(var i=0; i<data.length; i++){
        if(data[i][key].isEqual(value)){
            return i;
        }
    }
    return -1
}

function clearValidation(formElement){
    /*
    //Internal $.validator is exposed through $(form).validate()
    var validator = $(formElement).validate();
    //Iterate through named elements inside of the form, and mark them as error free
    $('[name]',formElement).each(function(){
        validator.successList.push(this);//mark as error free
        validator.showErrors();//remove error messages if present
    });
    validator.resetForm();//remove error class on name elements and clear history
    validator.reset();//remove all error and success data
    */
    $(formElement).removeData('validator');
    $(formElement).removeData('validate');
}

/*
from veritas
//adds Custom validators
function add_all(pattern, phone){
    if(pattern){add_matchpattern();}
    if(phone){add_checkphone();}

    jQuery.validator.addMethod("requiresperiod", function (value, element) {
        var atsymbol = value.indexOf("@");
        return atsymbol >-1 && value.indexOf(".", atsymbol) > -1;
    });
}

//adds pattern validation. What pattern? I don't know
function add_matchpattern() {
    jQuery.validator.addMethod("matchPattern", function (value, element) {
        var patt = "/((^[0-9]+[a-z]+)|(^[a-z]+[0-9]+))+[0-9a-z]+$/i";
        return value.replace(' ', '').length == 6;
    });
}

//adds phone validation
function add_checkphone() {
    jQuery.validator.addMethod("checkPhone", function (value, element) {
        value = value.replace(/\D/g,'');
        if (value.length != 10) {return false;}//must be 10 digits
        var firstdigit = value.substring(0, 1);
        if(firstdigit == 0 || firstdigit == 1){return false;}//do not allow 0 or 1 to be the first number
        firstdigit = value.substring(0, 3);
        return AreaCodes.indexOf(Number(firstdigit)) > -1;//only allow canadian area codes from the helper
    });
}

//removes existing validation rules from a form, adds all custom validators, then calls makerules()
function validateform(formID, rules){
    //$("#" + formID + ' > input, select, textarea').rules('remove');
    $("#" + formID).removeData('validator');
    $("#" + formID).removeData('validate');
    add_all(true, true);
    $("#" + formID).validate(makerules(rules));
}

//simplifies rule creation
//ie: makerules({fieldname: "phonenumber required"});
function makerules(validation){
    var rules = new Object();
    var messages = new Object();
    var startmessage = "Please enter ";
    var temprules, ruletype;
    var defaulttext = "Please fill out this field";
    var usecurrentuser = false;
    for (var property in validation) {
        if (validation.hasOwnProperty(property)) {
            rules[property] = new Object();
            messages[property] = new Object();
            var index = validation[property].indexOf("=");
            if (index > -1) {
                defaulttext = validation[property].substr(index+1);
                validation[property] = validation[property].substr(0, index);
            }
            temprules = validation[property].split(" ");
            ruletype = temprules[0];
            for(var i=0; i<temprules.length; i++) {
                switch (temprules[i]) {
                    case "creditcard":
                        rules[property]["creditcard"] = true;
                        messages[property]["creditcard"] = "The card number is not valid";
                        break;
                    case "phone":
                        rules[property]["checkPhone"] = true;
                        messages[property]["checkPhone"] = startmessage + "a valid phone number";
                        break;
                    case "currentuser":
                        usecurrentuser = true;
                        break;
                    case "email":
                        rules[property]["email"] = true;
                        if(usecurrentuser){
                            rules[property]["remote"] = {
                                url: baseurl + "/auth/validate/email/ajax/" + currentuser,
                                type: "post"
                            };
                        } else {
                            rules[property]["remote"] = {
                                url: baseurl + "/auth/validate/email/ajax",
                                type: "post"
                            };
                        }
                        messages[property]["remote"] = "This email address is already in use";
                        rules[property]["requiresperiod"] = true;0
                        messages[property]["requiresperiod"] = startmessage + "a valid email address.";
                        break
                    case "minlength":
                        rules[property]["minlength"] = Number(temprules[i+1]);
                        i=i+1;
                    case "required":
                        rules[property]["required"] = true;
                        switch(ruletype){
                            case "email": messages[property]["required"] = startmessage + "an email address"; break;
                            case "phone": messages[property]["required"] = startmessage + "a phone number"; break;
                            case "creditcard": messages[property]["required"] = startmessage + "a credit card"; break;
                            default: messages[property]["required"] = defaulttext;
                        }
                        break;
                    default:
                        alert(temprules[i] + " is not handled");
                }
            }
        }
    }
    return {rules: rules, messages: messages, focusInvalid: false,
        invalidHandler: function(form, validator) {
            var first = -1, element;
            for (i = 0; i < validator.errorList.length; i++){
                element = validator.errorList[i].element;
                if ($(element).is(":visible") && !$(element).is(":hidden") && !$(element).is(":disabled")) {
                    first = i;
                    break;
                }
            }

            if (!validator.numberOfInvalids()) {
                return;
            }

            if(first>-1) {
                $('html, body').animate({
                    scrollTop: $(validator.errorList[first].element).offset().top - 80
                }, 2000);
            }
        }
    };
}
*/