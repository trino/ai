<script>
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

    function inputbox2(Text, Title, Default, retfnc){
        Text += '<INPUT TYPE="TEXT" ID="modal_inputbox" CLASS="form-control" VALUE="' + Default + '" STYLE="margin-top: 15px;">';
        confirm2(Text, Title, function(){
            retfnc( $("#modal_inputbox").val() );
        });
    }
    function confirm2(){
        var Title = "Confirm";
        var action = function(){};
        $('#alert-confirm').unbind('click');
        if (arguments.length > 1) {
            for(var index = 0; index<arguments.length; index++){
                if(isFunction(arguments[index])){
                    action = arguments[index];
                } else {
                    Title = arguments[index];
                }
            }
        }
        alert(arguments[0], Title);
        $("#alert-cancel").show();

        $("#alert-confirm").click(action);
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
        var HTML = $(selector);
        if(HTML.length) {return $(selector)[0].outerHTML;}
        return "";
    }

    function visible(selector, status){
        if(isUndefined(status)){status = false;}
        if(status){
            $(selector).show();
        } else {
            $(selector).hide();
        }
    }

    $.fn.hasAttr = function(name) {
        return this.attr(name) !== undefined;
    };

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
        $(formElement).removeData('validator');
        $(formElement).removeData('validate');
    }

    $(document).on('touchend', function () {
        $(".select2-search, .select2-focusser").remove();
    })

    //handles the search text box
    function search(element) {
        var searchtext = element.value.toLowerCase();
        $(".menuitem").each(function (index) {
            var ismatch = false;
            if (searchtext) {
                var itemtext = $(this).attr("itemname").toLowerCase();
                if (itemtext.indexOf(searchtext) > -1) {
                    ismatch = true;
                }
            } else {
                ismatch = true;
            }
            if (ismatch) {
                $(this).removeClass("disabled");
            } else {
                $(this).addClass("disabled");
            }
        });
        for (var i = 0; i < classlist.length; i++) {
            var classname = classlist[i];
            var visible = $(".item_" + classname + ":visible").length;
            if (visible) {
                $(".head_" + classname).show();
            } else {
                $(".head_" + classname).hide();
            }
        }
    }

    //generates the order menu item modal
    function loadmodal(element) {
        element = $(element).parent().parent();
        var items = ["name", "price", "id", "size", "cat"];
        for (var i = 0; i < items.length; i++) {
            $("#modal-item" + items[i]).text($(element).attr("item" + items[i]));
        }
        var size = $(element).attr("itemsize");
        var toppingcost = 0.00;
        if (size) {
            toppingcost = Number(freetoppings[size]).toFixed(2);
            $(".toppings").attr("data-placeholder", "Add Toppings: $" + toppingcost);
            $(".toppings_price").text(toppingcost);
        }
        $("#modal-toppingcost").text(toppingcost);

//clones the addon dropdowns
        initSelect2(".select2", true);
        sendintheclones("#modal-wings-clones", "#modal-wings-original", $(element).attr("wings_sauce"), wingsauceouterhtml);
        sendintheclones("#modal-toppings-clones", "#modal-toppings-original", $(element).attr("toppings"), toppingsouterhtml.replace('[price]', toppingcost));
        initSelect2(".select2clones");
    }

    function initSelect2(selector, reset) {
        if (!isUndefined(reset)) {
            $('select').select2("val", null);
        }
        if (!isUndefined(selector)) {
            $('select' + selector).select2({
                maximumSelectionSize: 4,
                minimumResultsForSearch: -1,
                placeholder: function () {
                    $(this).data('placeholder');
                },
                allowClear: true
            }).change();
        }
    }

    //makes HTML clones of a dropdown
    function sendintheclones(destinationID, sourceID, count, sourceHTML) {
        var HTML = "";
        visible(sourceID, count > 0);
        if (count) {
            if (isUndefined(sourceHTML)) {
                var sourceHTML = outerHTML(sourceID).replace('select2', 'form-control select2 select2clones');
            }
            if (count == 1) {
                $(sourceID + "-ordinal").hide();
            } else {
                $(sourceID + "-ordinal").show();
            }
            for (var index = 2; index <= count; index++) {
                HTML += sourceHTML.replace('First', ordinals[index - 1]);
            }
        }
        $(destinationID).html(HTML);
    }

    //get the data from the modal and add it to the order
    function additemtoorder(element) {
        var itemid = 0, itemname = "", itemprice = 0.00, itemaddons = new Array, itemsize = "", toppingcost = 0.00, toppingscount = 0, itemcat = "";
        if (isUndefined(element)) {//modal with addons
            itemid = $("#modal-itemid").text();
            itemname = $("#modal-itemname").text();
            itemprice = $("#modal-itemprice").text();
            itemsize = $("#modal-itemsize").text();
            itemcat = $("#modal-itemcat").text();
            itemaddons = getaddons();
            if (itemsize) {
                toppingcost = Number(freetoppings[itemsize]).toFixed(2);
            }
            for (var i = 0; i < itemaddons.length; i++) {
                toppingscount += itemaddons[i]["count"];
            }
        } else {//direct link, no addons
            element = $(element).parent().parent();
            itemid = $(element).attr("itemid");
            itemname = $(element).attr("itemname");
            itemprice = $(element).attr("itemprice");
            itemcat = $(element).attr("itemcat");
        }

        data = {
            quantity: 1,
            itemid: itemid,
            itemname: itemname,
            itemprice: itemprice,
            itemsize: itemsize,
            category: itemcat,
            toppingcost: toppingcost,
            toppingcount: toppingscount,
            itemaddons: itemaddons
        };
        theorder.push(data);
        generatereceipt();
    }

    //convert the order to an HTML receipt
    function generatereceipt() {
        if($("#myorder").length == 0){return false;}
        var HTML = '', tempHTML = "", subtotal = 0;
        var itemnames = {toppings: "Pizza", wings_sauce: "Pound"};
        var nonames = {toppings: "toppings", wings_sauce: "sauces"};
        for (var itemid = 0; itemid < theorder.length; itemid++) {
            var item = theorder[itemid];
            var totalcost = (Number(item["itemprice"]) + (Number(item["toppingcost"]) * Number(item["toppingcount"]))).toFixed(2);
            var category = "pizza";
            if (item.hasOwnProperty("category")) {
                category = item["category"].toLowerCase().replaceAll(" ", "_");
                if (category.endswith("pizza")) {
                    category = "pizza";
                }
            }

            subtotal += Number(totalcost);

            tempHTML = '<span class="pull-left"> <DIV CLASS="sprite sprite-' + category + ' sprite-medium"></DIV> ' + item["itemname"] + '</span>';
            tempHTML += '<span class="pull-right" title="Base cost: ' + item["itemprice"] + ' Non-free Toppings: ' + item["toppingcount"] + ' Topping cost: $' + item["toppingcost"] + '"> $' + totalcost + ' <i class="fa fa-close" onclick="removeorderitem(' + itemid + ');"></i></span><div class="clearfix"></div>';

            var itemname = "";
            if (item.hasOwnProperty("itemaddons") && item["itemaddons"].length > 0) {
                var tablename = item["itemaddons"][0]["tablename"];
                if (item["itemaddons"].length > 1) {
                    itemname = itemnames[tablename];
                }
                for (var currentitem = 0; currentitem < item["itemaddons"].length; currentitem++) {
                    var addons = item["itemaddons"][currentitem];
                    if (itemname) {
                        tempHTML += ordinals[currentitem] + " " + itemname + ": ";
                    }
                    if (addons["addons"].length == 0) {
                        tempHTML += '[No ' + nonames[tablename] + ']';
                    } else {
                        for (var addonid = 0; addonid < addons["addons"].length; addonid++) {
                            if (addonid > 0) {
                                tempHTML += ", ";
                            }
                            var addonname = addons["addons"][addonid]["text"];
                            var isfree = isaddon_free(tablename, addonname);
                            if (isfree) {
                                tempHTML += '<I TITLE="Free addon">' + addonname + '</I>';
                            } else {
                                tempHTML += addonname;
                            }
                        }
                    }
                    tempHTML += '<BR>';
                }
            }
            HTML += tempHTML;
        }
        var taxes = (subtotal + deliveryfee) * 0.13;//ontario only
        totalcost = subtotal + deliveryfee + taxes;

        $("#checkoutbutton").show();
        visible("#checkout", userdetails);

        createCookieValue("theorder", JSON.stringify(theorder));
        if (theorder.length == 0) {
            HTML = '<span class="pull-left">Order is empty</SPAN><BR>';
            $("#checkout").hide();
            $("#checkoutbutton").hide();
            removeCookie("theorder");
            collapsecheckout();
            $("#checkout-btn").hide();
        } else {
            tempHTML = '<span class="pull-right"> Sub-total: $' + subtotal.toFixed(2) + '</span><br>';
            tempHTML += '<span class="pull-right"> Delivery: $' + deliveryfee.toFixed(2) + '</span><br>';
            tempHTML += '<span class="pull-right"> Tax: $' + taxes.toFixed(2) + '</span><br>';
            tempHTML += '<span class="pull-right"> Total: $' + totalcost.toFixed(2) + '</span>';
            $("#checkout-total").text('$' + totalcost.toFixed(2));
            $("#checkout-btn").show();
        }
        $("#myorder").html(HTML + tempHTML);
    }

    //hides the checkout form
    function collapsecheckout() {
        if ($("#collapseCheckout").attr("aria-expanded") == "true") {
            $("#checkout").trigger("click");
        }
    }

    function clearorder() {
        theorder = new Array;
        generatereceipt();
    }

    //gets the addons from each dropdown
    function getaddons() {
        var itemaddons = new Array;
        for (var tableid = 0; tableid < tables.length; tableid++) {
            var table = tables[tableid];
            $('.select2.' + table + ":visible").each(function (index) {
                if (!$(this).hasClass("select2-offscreen")) {
                    var addons = $(this).select2('data');
                    var toppings = 0;
                    for (var addid = 0; addid < addons.length; addid++) {
                        delete addons[addid]["element"];
                        delete addons[addid]["locked"];
                        delete addons[addid]["disabled"];
                        if (addons[addid]["text"].endswith("(free)")) {
                            addons[addid]["text"] = addons[addid]["text"].left(addons[addid]["text"].length - 6).trim();
                        }
                        addons[addid]["isfree"] = isaddon_free(table, addons[addid]["text"]);
                        if (!addons[addid]["isfree"]) {
                            toppings++;
                        }
                    }
                    itemaddons.push({tablename: table, addons: addons, count: toppings});
                }
            });
        }
        return itemaddons;
    }

    //get the size of a pizza
    function getsize(Itemname) {
        var sizes = Object.keys(freetoppings);
        var size = "";
        for (var i = 0; i < sizes.length; i++) {
            if (!isArray(freetoppings[sizes[i]])) {
                if (Itemname.contains(sizes[i]) && sizes[i].length > size.length) {
                    size = sizes[i];
                }
            }
        }
        return size;
    }

    //checks if an addon is free
    function isaddon_free(Table, Addon) {
        switch (Addon.toLowerCase()) {
            case "lightly done":
            case "well done":
                return true;
                break;
            default:
                return freetoppings[Table].indexOf(Addon) > -1;
        }
    }

    //checks if an addon is on the whole pizza (for when we implement halves)
    function isaddon_onall(Table, Addon) {
        return freetoppings["isall"][Table].indexOf(Addon) > -1;
    }

    //remove an item from the order
    function removeorderitem(index) {
        removeindex(theorder, index);
        generatereceipt();
    }

    //checks if the result is JSON, and processes the Status and Reasons
    function handleresult(result, title) {
        try {
            var data = JSON.parse(result);
            if (data["Status"] == "false" || !data["Status"]) {
                alert(data["Reason"], title);
            } else {
                return true;
            }
        } catch (e) {
            alert(result, title);
        }
        return false;
    }

    //send an order to the server
    function placeorder(StripeResponse) {
        if (!canplaceorder) {
            log("CANT PLACE ORDER");
            return false;
        }
        if (isObject(userdetails)) {
            var addressinfo = getform("#orderinfo");//i don't know why the below 2 won't get included. this forces them to be
            addressinfo["cookingnotes"] = $("#cookingnotes").val();
            addressinfo["deliverytime"] = $("#deliverytime").val();
            $.post(webroot + "placeorder", {
                _token: token,
                info: addressinfo,
                stripe: StripeResponse,
                order: theorder,
                name: $("#reg_name").val(),
                phone: $("#reg_phone").val()
            }, function (result) {
                $("#checkoutmodal").modal("hide");
                if (result.contains("ordersuccess")) {
                    clearorder();
                }
                handleresult(result, "Order Placed Successfully!");
            });
        } else {
            $("#loginmodal").modal("show");
        }
    }

    $(window).on('shown.bs.modal', function () {
        var modalID = $(".modal:visible").attr("id");
        if (modalID == "profilemodal") {
            $("#addresslist").html(addresses());
        }
    });

    //generate a list of addresses and send it to the alert modal
    function addresses() {
        var HTML = '<h5>Addresses</h5>';
        var number = $("#add_number").val();
        var street = $("#add_street").val();
        var city = $("#add_city").val();
        var AddNew = number && street && city;
        $("#saveaddresses option").each(function () {
            var ID = $(this).val();
            if (ID > 0) {
                if (userdetails["cc_addressid"] == ID) {
                    HTML += '<A TITLE="This address is used for your credit card and cannot be deleted"><i class="fa fa-fw fa-credit-card"></i> ';
                } else {
                    HTML += '<A ID="add_' + ID + '" TITLE="Delete this address" onclick="deleteaddress(' + ID + ');" class="hyperlink"><i style="color:red" class="fa fa-fw fa-times"></i> ';
                }
                HTML += $(this).text() + '</A><BR>';
                if (number.isEqual($(this).attr("number")) && street.isEqual($(this).attr("street")) && city.isEqual($(this).attr("city"))) {
                    AddNew = false;
                }
            }
        });
        if (AddNew) {
            HTML += '<A ONCLICK="deleteaddress(-1);" CLASS="hyperlink">Add ' + "'" + number + " " + street + ", " + city + "' to the list</A>";
        } else {
//   HTML += 'Enter a new address in the checkout form if you want to add it to your profile';
        }
        return HTML;
    }

    //handles the orders list modal
    function orders(ID, getJSON) {
        if (isUndefined(ID)) {//no ID specified, get a list of order IDs from the user's profile and make buttons
            var HTML = '<ul class="list-group">';
            var First = false;
            for (var i = 0; i < userdetails["Orders"].length; i++) {
                var order = userdetails["Orders"][i];
                ID = order["id"];
                if (!First) {
                    First = ID;
                }
                HTML += '<li class="list-group-item" ONCLICK="orders(' + ID + ');"><span class="tag tag-default tag-pill pull-xs-right">ID: ' + ID + '</span>' + order["placed_at"] + '<SPAN ID="pastreceipt' + ID + '"></SPAN></li>';
            }
            HTML += '</ul><P><DIV ID="pastreceipt" CLASS="pastreceipt"></DIV><P>';
            alert(HTML, "Orders");
            if (First) {
                orders(First);
            }
        } else {
            if (isUndefined(getJSON)) {
                getJSON = false;
            }
            var Index = getIterator(userdetails["Orders"], "id", ID);
            if (!getJSON && userdetails["Orders"][Index].hasOwnProperty("Contents")) {
                $("#pastreceipt" + ID).html(userdetails["Orders"][Index]["Contents"]);
                GetNextOrder(ID);
                return;
            }
            $.post("<?= webroot('public/list/orders'); ?>", {
                _token: token,
                action: "getreceipt",
                orderid: ID,
                JSON: getJSON
            }, function (result) {
                if (getJSON) {//JSON recieved, put it in the order
                    result = JSON.parse(result);
                    theorder = result["Order"];
                    $("#cookingnotes").val(result["cookingnotes"]);
                    generatereceipt();
                    $("#alertmodal").modal('hide');
                } else {//HTML recieved, put it in the pastreceipt element
                    $("#pastreceipt" + ID).html(result);
                    if (Index > -1) {
                        userdetails["Orders"][Index]["Contents"] = result;
                    }
                    GetNextOrder(ID);
                }
            });
        }
    }

    function getIterator(arr, key, value) {
        for (var i = 0; i < arr.length; i++) {
            if (arr[i][key] == value) {
                return i;
            }
        }
        return -1;
    }

    function GetNextOrder(CurrentID) {
        var CurrentIndex = getIterator(userdetails["Orders"], "id", CurrentID);
        if (CurrentIndex > -1 && CurrentIndex < userdetails["Orders"].length - 1) {
            orders(userdetails["Orders"][CurrentIndex + 1]["id"]);
        }
    }

    $(document).ready(function () {
        toppingsouterhtml = outerHTML("#modal-toppings-original").replace('form-control select2', 'form-control select2 select2clones');
        wingsauceouterhtml = outerHTML("#modal-wings-original").replace('form-control select2', 'form-control select2 select2clones');
        if (getCookie("theorder")) {
            theorder = JSON.parse(getCookie("theorder"));
        }
        generatereceipt();
        @if(!read("id"))
        $("#loginmodal").modal("show");
        @endif
    });

    function enterkey(e, action){
        var keycode = event.which || event.keyCode;
        if(keycode == 13){
            if(action.left(1) == "#"){
                $(action).focus();
            } else {
                handlelogin(action);
            }
        }
    }

    function handlelogin(action){
        if(isUndefined(action)){action="verify";}
        $.post(webroot + "auth/login", {
            action: action,
            _token: token,
            email: $("#login_email").val(),
            password: $("#login_password").val()
        }, function (result) {
            try {
                var data = JSON.parse(result);
                if(data["Status"] == "false" || !data["Status"]) {
                    data["Reason"] = data["Reason"].replace('[verify]', '<A onclick="handlelogin();" CLASS="hyperlink" TITLE="Click here to resend the email">verify</A>');
                    alert(data["Reason"], "Error logging in");
                } else {
                    switch (action) {
                        case "login":
                            token = data["Token"];
                            login(data["User"], true);
                            $("#loginmodal").modal("hide");
                            if(redirectonlogin){
                                log("Login reload");
                                location.reload();
                            }
                            break;
                        case "forgotpassword": case "verify":
                        alert(data["Reason"], "Login");
                        break;
                        case "logout":
                            removeCookie();
                            $('[class^="session_"]').text("");
                            $(".loggedin").hide();
                            $(".loggedout").show();
                            $(".clear_loggedout").html("");
                            $(".profiletype").hide();
                            userdetails=false;
                            if(redirectonlogout){
                                log("Logout reload");
                                window.location = "<?= webroot("public/index"); ?>";
                            } else {
                                switch(currentRoute){
                                    case "index"://resave order as it's deleted in removeCookie();
                                        if(!isUndefined(theorder)) {
                                            if (theorder.length > 0) {
                                                createCookieValue("theorder", JSON.stringify(theorder));
                                            }
                                        }
                                        break;
                                }
                            }
                            if(!isUndefined(collapsecheckout)) {
                                collapsecheckout();
                            }
                            break;
                    }
                }
            } catch (err){
                alert(err.message + "<BR>" + result, "Login Error");
            }
        });
    }

    var skiploadingscreen = false;
    //overwrites javascript's alert and use the modal popup
    (function () {
        var proxied = window.alert;
        window.alert = function () {
            var title = "Alert";
            if (arguments.length > 1) {
                title = arguments[1];
            }
            $("#alert-cancel").hide();
            $("#alert-ok").click(function () {
            });

            $("#alertmodalbody").html(arguments[0]);
            $("#alertmodallabel").text(title);
            $("#alertmodal").modal('show');
        };
    })();

    var generalhours = <?= json_encode(gethours()) ?>;

    $(document).ready(function () {
//make every AJAX request show the loading animation
        $body = $("body");
        $(document).on({
            ajaxStart: function () {
                if (skiploadingscreen) {
                    skiploadingscreen = false;
                } else {
                    $body.addClass("loading");
                }
            },
            ajaxStop: function () {
                $body.removeClass("loading");
            }
        });

        @if(isset($user))
            login(<?= json_encode($user); ?>, false); //user is already logged in, use the data
        @endif

        var HTML = '';
        var todaysdate = isopen(generalhours);
        if (todaysdate == -1) {
            HTML = 'Currently closed';
            todaysdate = getToday();
            if (generalhours[todaysdate].open > now()) {
                HTML = 'Opens at: ' + GenerateTime(generalhours[todaysdate].open);
            }
        } else {
            HTML = 'Open until: ' + GenerateTime(generalhours[todaysdate].close);
        }
        GenerateHours(generalhours);
        $("#openingtime").html(HTML);
    });

    //handle a user login
    function login(user, isJSON) {
        userdetails = user;
        var keys = Object.keys(user);
        for (var i = 0; i < keys.length; i++) {
            var key = keys[i];
            var val = user[key];
            createCookieValue("session_" + key, val);//save data to cookie
            $(".session_" + key).text(val);//set elements text to data
            $(".session_" + key + "_val").val(val);//set elements value to data
        }
        $(".loggedin").show();//show loggedin class
        $(".loggedout").hide();//hide loggedout class
        $(".profiletype").hide();//hide all profile type clasdses
        $(".profiletype" + user["profiletype"]).show();//show classes for this profile type
        var HTML = '';
        var FirstAddress = false;
        if (user["Addresses"].length > 0) {//generate address dropdown
            HTML += '<SELECT class="form-control saveaddresses" id="saveaddresses" onchange="addresschanged();"><OPTION>Select a saved address</OPTION>';
            addresskeys = Object.keys(user["Addresses"][0]);
            for (i = 0; i < user["Addresses"].length; i++) {
                if (!FirstAddress) {
                    FirstAddress = user["Addresses"][i]["id"];
                }
                HTML += AddressToOption(user["Addresses"][i], addresskeys);
            }
            HTML += '</SELECT>';
        }
        $(".addressdropdown").html(HTML);
        if (user["profiletype"] == 2) {
            user["restaurant_id"] = FirstAddress;
            var URL = '<?= webroot("public/list/orders"); ?>';
            if (window.location.href != URL && isJSON) {
                window.location.href = URL;
                die();
            }
        }
    }

    //convert an address to a dropdown option
    function AddressToOption(address, addresskeys) {
        if (isUndefined(addresskeys)) {
            addresskeys = ["id", "value", "user_id", "number", "unit", "buzzcode", "street", "postalcode", "city", "province", "latitude", "longitude", "phone"];
        }
        var tempHTML = '<OPTION';
        var streetformat = "[number] [street], [city]";
        if (address["unit"]) {
            streetformat += " - Apt/Unit: [unit]";
            if (address["buzzcode"]) {
                streetformat += ", Buzz code: [buzzcode]";
            }
        }
        for (var keyID = 0; keyID < addresskeys.length; keyID++) {
            var keyname = addresskeys[keyID];
            if (address.hasOwnProperty(keyname)) {
                var value = address[keyname];
                streetformat = streetformat.replace("[" + keyname + "]", value);
                if (keyname == "id") {
                    keyname = "value";
                }
                tempHTML += ' ' + keyname + '="' + value + '"'
            }
        }
        return tempHTML + '>' + streetformat + '</OPTION>';
    }

    //address dropdown changed
    function addresschanged() {
        var Selected = $("#saveaddresses option:selected");
        var Text = '[number] [street], [city]';
        for (var keyID = 0; keyID < addresskeys.length; keyID++) {
            var keyname = addresskeys[keyID];
            var keyvalue = $(Selected).attr(keyname);
            Text = Text.replace("[" + keyname + "]", keyvalue);
            $("#add_" + keyname).val(keyvalue);
        }
        if ($(Selected).val() == 0) {
            Text = '';
        }
        $("#formatted_address").val(Text);
        addresshaschanged();
    }

    //universal AJAX error handling
    $(document).ajaxComplete(function (event, request, settings) {
        if (request.status != 200 && request.status > 0) {//not OK, or aborted
//H2 class="block_exception", get span class="exception_title" and class="exception_message"
            alert(request.statusText + "<P>URL: " + settings.url, "AJAX error code: " + request.status);
        }
    });
</SCRIPT>

<div class="modal" id="alertmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static" style="z-index: 9999999;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-close"></i></button>
                <div class="pb-1">
                    <h4 class="modal-title" id="alertmodallabel">Title</h4>
                </div>
                <DIV ID="alertmodalbody"></DIV>
                <div class="row">
                    <DIV class="col-xs-6 ">
                        <button class="btn btn-secondary waves-effect BTN-BLOCK" id="alert-cancel" data-dismiss="modal">
                            Cancel
                        </button>
                    </DIV>
                    <DIV class="col-xs-6 ">
                        <button class="btn btn-warning btn-block" id="alert-confirm" data-dismiss="modal">
                            OK
                        </button>
                    </DIV>
                </div>
                <DIV CLASS="clearfix"></DIV>
            </div>
        </div>
    </div>
</DIV>

<div class="modal loading" ID="loadingmodal"></div>