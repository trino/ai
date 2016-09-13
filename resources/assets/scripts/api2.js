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

//deletes a cookie valie
function removeCookie(cname) {
    $.removeCookie(cname);
}

//creates a cookie value that expires in 1 year
function createCookieValue(cname, cvalue) {
    log("Creating cookie value: '" + cname + "' with: " + cvalue);
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