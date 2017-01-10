<?php
    startfile("popups_alljs");
    $CURRENT_YEAR = date("Y");
    $STREET_FORMAT = "[number] [street], [city] [postalcode]";//["id", "value", "user_id", "number", "unit", "buzzcode", "street", "postalcode", "city", "province", "latitude", "longitude", "phone"];
?>

<script>
    var currentitemID = -1;
    var MAX_DISTANCE = 20;//km
    var debugmode = false;//'<?= !islive(); ?>' == '1';

    String.prototype.isEqual = function (str) {
        if (isUndefined(str)) {
            return false;
        }
        if (isNumeric(str) || isNumeric(this)) {
            return this == str;
        }
        return this.toUpperCase().trim() == str.toUpperCase().trim();
    };

    function isUndefined(variable) {
        return typeof variable === 'undefined';
    }

    function isArray(variable) {
        return Array.isArray(variable);
    }

    //returns true if $variable appears to be a valid number
    function isNumeric(variable) {
        return !isNaN(Number(variable));
    }

    //returns true if $variable appears to be a valid object
    //typename (optional): the $variable would also need to be of the same object type (case-sensitive)
    function isObject(variable, typename) {
        if (typeof variable == "object") {
            if (isUndefined(typename)) {
                return true;
            }
            return variable.getName().toLowerCase() == typename.toLowerCase();
        }
        return false;
    }

    String.prototype.contains = function (str) {
        return this.toLowerCase().indexOf(str.toLowerCase()) > -1;
    };

    String.prototype.endswith = function (str) {
        return this.right(str.length).isEqual(str);
    };
    //returns the left $n characters of a string

    String.prototype.left = function (n) {
        return this.substring(0, n);
    };

    String.prototype.mid = function (start, length) {
        return this.substring(start, start + length);
    };

    Number.prototype.pad = function (size) {
        var s = String(this);
        while (s.length < (size || 2)) {
            s = "0" + s;
        }
        return s;
    };

    //returns the right $n characters of a string
    String.prototype.right = function (n) {
        return this.substring(this.length - n);
    };

    function right(text, length) {
        return String(text).right(length);
    }

    //returns true if $variable appears to be a valid function
    function isFunction(variable) {
        var getType = {};
        return variable && getType.toString.call(variable) === '[object Function]';
    }

    //replaces all instances of $search within a string with $replacement
    String.prototype.replaceAll = function (search, replacement) {
        var target = this;
        if (isArray(search)) {
            for (var i = 0; i < search.length; i++) {
                if (isArray(replacement)) {
                    target = target.replaceAll(search[i], replacement[i]);
                } else {
                    target = target.replaceAll(search[i], replacement);
                }
            }
            return target;
        }
        return target.replace(new RegExp(search, 'g'), replacement);
    };

    String.prototype.between = function (leftside, rightside) {
        var target = this;
        var start = target.indexOf(leftside);
        if (start > -1) {
            var finish = target.indexOf(rightside, start);
            if (finish > -1) {
                return target.substring(start + leftside.length, finish);
            }
        }
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
        if (isUndefined(cname)) {//erase all cookies
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

    function log(text) {
        console.log(text);
    }

    function getform(ID) {
        var data = $(ID).serializeArray();
        var ret = {};
        for (var i = 0; i < data.length; i++) {
            ret[data[i].name] = data[i].value;
        }
        return ret;
    }

    function inputbox2(Text, Title, Default, retfnc) {
        Text += '<INPUT TYPE="TEXT" ID="modal_inputbox" CLASS="form-control" VALUE="' + Default + '" STYLE="margin-top: 15px;">';
        confirm2(Text, Title, function () {
            retfnc($("#modal_inputbox").val());
        });
    }

    function confirm2() {
        var Title = "Confirm";
        var action = function () {};
        $('#alert-confirm').unbind('click');
        if (arguments.length > 1) {
            for (var index = 0; index < arguments.length; index++) {
                if (isFunction(arguments[index])) {
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

    function removeindex(arr, index, count, delimiter) {
        if (!isArray(arr)) {
            if (isUndefined(delimiter)) {
                delimiter = " ";
            }
            arr = removeindex(arr.split(delimiter), index, count, delimiter).join(delimiter);
        } else {
            if (isNaN(index)) {
                index = hasword(arr, index);
            }
            if (index > -1 && index < arr.length) {
                if (isUndefined(count)) {
                    count = 1;
                }
                arr.splice(index, count);
            }
        }
        return arr;
    }

    function visible(selector, status) {
        if (isUndefined(status)) {
            status = false;
        }
        if (status) {
            $(selector).show();
        } else {
            $(selector).hide();
        }
    }

    $.fn.hasAttr = function (name) {
        return this.attr(name) !== undefined;
    };

    $.validator.addMethod('phonenumber', function (Data, element) {
        Data = Data.replace(/\D/g, "");
        if (Data.substr(0, 1) == "0") {
            return false;
        }
        return Data.length == 10;
    }, "Please enter a valid phone number");

    $.validator.addMethod('validaddress', function (Data, element) {
        log("TESTING ADDRESS");

    }, "Please enter a valid address");

    function isvalidaddress(){
        var fields = ["formatted_address", "add_postalcode", "add_latitude", "add_longitude"];
        for(i=0;i<fields.length;i++){
            log(fields[i] + ": " + $("#" + fields[i]).val().length);
            if($("#" + fields[i]).val().length == 0){return false;}
        }
        return true;
    }

    function findwhere(data, key, value) {
        for (var i = 0; i < data.length; i++) {
            if (data[i][key].isEqual(value)) {
                return i;
            }
        }
        return -1
    }

    $(document).on('touchend', function () {
        $(".select2-search, .select2-focusser").remove();
    });

    //generates the order menu item modal
    var currentitem;

    function loadmodal(element, notparent) {
        if (isUndefined(notparent)) {
            element = $(element);
        }
        var items = ["name", "price", "id", "size", "cat"];
        for (var i = 0; i < items.length; i++) {
            $("#modal-item" + items[i]).text($(element).attr("item" + items[i]));
        }
        var itemname = $(element).attr("itemname");
        var itemcost = $(element).attr("itemprice");
        $("#modal-itemtotalprice").text(itemcost);
        var size = $(element).attr("itemsize");
        var toppingcost = 0.00;
        if (size) {
            toppingcost = Number(freetoppings[size]).toFixed(2);
            $(".toppings").attr("data-placeholder", "Add Toppings: $" + toppingcost);
            $(".toppings_price").text(toppingcost);
        }
        $("#modal-toppingcost").text(toppingcost);
        currentitem = {itemname: itemname, itemcost: itemcost, size: size, toppingcost: toppingcost};

        for (var tableid = 0; tableid < tables.length; tableid++) {
            var table = tables[tableid];
            var Quantity = Number($(element).attr(table));
            if (Quantity > 0) {
                list_addons_quantity(Quantity, table, false, itemname, itemcost, toppingcost);
                tableid = tables.length;
            }
        }
        currentitemID = -1;
        var title = "<i class='fa fa-plus'></i>";
        if (!isUndefined(notparent)) {
            $("#menumodal").modal("show");
            title = "SAVE";
        }
        $("#additemtoorder").html(title);
    }

    //get the data from the modal and add it to the order
    function additemtoorder(element, Index) {
        var x = document.getElementById("snackbar")
        x.className = "show";
        setTimeout(function () {
            x.className = x.className.replace("show", "");
        }, 500);


        var itemid = 0, itemname = "", itemprice = 0.00, itemaddons = new Array, itemsize = "", toppingcost = 0.00, toppingscount = 0, itemcat = "";
        if (!isUndefined(Index)) {
            currentitemID = Index;
        }
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
            element = $(element);
            log("HERE");
            log(element);
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
        if (currentitemID == -1) {
            theorder.push(data);
            var ret = theorder.length - 1;
        } else {
            theorder[currentitemID] = data;
            var ret = currentitemID;
        }
        generatereceipt();
        $("#receipt_item_" + ret).hide();
        $("#receipt_item_" + ret).fadeIn("fast");
        return ret;
    }

    //convert the order to an HTML receipt
    function generatereceipt() {
        if ($("#myorder").length == 0) {
            return false;
        }
        var HTML = '', tempHTML = "", subtotal = 0;
        var itemnames = {toppings: "Pizza", wings_sauce: "Lb"};
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
            var hasaddons = item.hasOwnProperty("itemaddons") && item["itemaddons"].length > 0;
            subtotal += Number(totalcost);

            tempHTML = '<DIV ID="receipt_item_' + itemid + '" class="receipt_item">';
            tempHTML += '<span class=""> <DIV CLASS="sprite pull-left rounded-circle bg-warning sprite-' + category + ' sprite-medium"></DIV> ' + item["itemname"] + '</span>';
            tempHTML += '<span class="pull-right" title="Base cost: ' + item["itemprice"] + ' Non-free Toppings: ' + item["toppingcount"] + ' Topping cost: $' + item["toppingcost"] + '">';

            tempHTML += ' <i class="fa fa-close pull-right" onclick="removeorderitem(' + itemid + ');"></i>';

            if (hasaddons) {
                tempHTML += ' <i class="fa fa-pencil pull-right" onclick="edititem(this, ' + itemid + ');"></i>';
            }
            tempHTML += '$' + totalcost + '</span><div class="clearfix"></div>';

            var itemname = "";
            if (hasaddons) {
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
                        tempHTML += 'no ' + nonames[tablename] + '';
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
            HTML += tempHTML + '</DIV>';
        }
        var taxes = (subtotal + deliveryfee) * 0.13;//ontario only
        totalcost = subtotal + deliveryfee + taxes;

        $("#checkoutbutton").show();
        visible("#checkout", userdetails);

        createCookieValue("theorder", JSON.stringify(theorder));
        if (theorder.length == 0) {
            HTML = '<div class="row text-xs-center"><i class="fa fa-shopping-cart fa-5x" style="color: #c9312c !important;"></i><br> <br>  <span class="">Order is Empty</SPAN></div>';
            $("#checkout").hide();
            $("#checkoutbutton").hide();
            removeCookie("theorder");
            collapsecheckout();
            $("#checkout-btn").hide();
            $("#checkout-total").text('$0.00');
        } else {
            tempHTML = '<br><span class="pull-right category-parent"> <SPAN CLASS="category">Sub-total </SPAN>$' + subtotal.toFixed(2) + '</span><br>';
            tempHTML += '<span class="pull-right category-parent"> <SPAN CLASS="category">Delivery </SPAN>$' + deliveryfee.toFixed(2) + '</span><br>';
            tempHTML += '<span class="pull-right category-parent"> <SPAN CLASS="category">Tax </SPAN>$' + taxes.toFixed(2) + '</span><br>';
            tempHTML += '<span class="pull-right category-parent"> <SPAN CLASS="category">Total </SPAN>$' + totalcost.toFixed(2) + '</span><span class=""><br>&nbsp;</span>';

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

    function confirmclearorder() {
        if (theorder.length > 0) {
            confirm2('', 'Clear Order?', function () {
                clearorder();
            });
        }
    }

    function clearorder() {
        theorder = new Array;
        removeorderitemdisabled = true;
        $(".receipt_item").fadeOut("fast", function () {
            removeorderitemdisabled = false;
            generatereceipt();
        });
    }

    function edititem(element, Index) {
        var theitem = theorder[Index];
        if (!$(element).hasAttr("itemname")) {
            $(element).attr("itemname", theitem.itemname);
            $(element).attr("itemprice", theitem.itemprice);
            $(element).attr("itemid", theitem.itemid);
            $(element).attr("itemsize", theitem.itemsize);
            $(element).attr("itemcat", theitem.category);
            for (var i = 0; i < tables.length; i++) {
                $(element).attr(tables[i], 0);
            }
            $(element).attr(theitem.itemaddons[0].tablename, theitem.itemaddons.length);
        }
        loadmodal(element, true);
        currentitemID = Index;
        for (var i = 0; i < theitem.itemaddons.length; i++) {
            var tablename = theitem.itemaddons[i].tablename;
            for (var i2 = 0; i2 < theitem.itemaddons[i].addons.length; i2++) {
                var theaddon = theitem.itemaddons[i].addons[i2].text;
                currentaddonlist[i].push({name: theaddon, qual: 1, side: 1, type: tablename, group: getaddon_group(tablename, theaddon)});
            }
        }
        generateaddons();
    }

    //gets the addons from each dropdown
    function getaddons() {
        var itemaddons = new Array;
        for (var tableid = 0; tableid < tables.length; tableid++) {
            var table = tables[tableid];
            if (table == currentaddontype) {
                for (var itemid = 0; itemid < currentaddonlist.length; itemid++) {
                    var addonlist = currentaddonlist[itemid];
                    var addons = new Array;
                    var toppings = 0;
                    for (var addonid = 0; addonid < addonlist.length; addonid++) {
                        var name = addonlist[addonid].name;
                        var isfree = isaddon_free(table, name);
                        addons.push({
                            text: name,
                            isfree: isfree
                        });
                        if (!isfree) {
                            toppings++;
                        }
                    }
                    itemaddons.push({tablename: table, addons: addons, count: toppings});
                }
            }
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

    function getaddon_group(Table, Addon) {
        if (groups.hasOwnProperty(Table)) {
            if (groups[Table].hasOwnProperty(Addon)) {
                return Number(groups[Table][Addon]);
            }
        }
        return 0;
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
    var removeorderitemdisabled = false;

    function removeorderitem(index) {
        if (removeorderitemdisabled) {
            return;
        }
        removeindex(theorder, index);
        removeorderitemdisabled = true;
        $("#receipt_item_" + index).fadeOut("fast", function () {
            removeorderitemdisabled = false;
            generatereceipt();
        });
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

    function validaddress() {
        var savedaddress = $("#saveaddresses").val();
        if (savedaddress == 0) {return false;}
        if (savedaddress == "addaddress") {return isvalidaddress();}
        return true;
    }

    function isvalidcreditcard(CardNumber, Month, Year, CVV) {
        if (isUndefined(CardNumber)) {
            CardNumber = $("[data-stripe=number]").val();
        }
        if (isUndefined(Month)) {
            Month = $("[data-stripe=exp_month]").val();
        }
        if (isUndefined(Year)) {
            Year = $("[data-stripe=exp_year]").val();
        }
        if (isUndefined(CVV)) {
            CVV = $("[data-stripe=cvc]").val();
        }
        CardNumber = CardNumber.replace(/\D/g, '');
        var nCheck = 0, nDigit = 0, bEven = false;
        for (var n = CardNumber.length - 1; n >= 0; n--) {
            var cDigit = CardNumber.charAt(n);
            var nDigit = parseInt(cDigit, 10);
            if (bEven) {
                if ((nDigit *= 2) > 9) {
                    nDigit -= 9;
                }
            }
            nCheck += nDigit;
            bEven = !bEven;
        }
        if ((nCheck % 10) == 0) {
            var ExpiryDate = Number(Year) * 100 + Number(Month);
            var d = new Date();
            var CurrentDate = (d.getYear() % 100) * 100 + d.getMonth();
            if (ExpiryDate > CurrentDate) {
                return Number(CVV) > 99;
            } else {
                log("Failed expiry date check: " + ExpiryDate + " <= " + CurrentDate);
            }
        } else {
            log("Failed card number check: " + CardNumber);
        }
    }

    function canplaceanorder() {
        if (!$("#saved-credit-info").val()) {
            if (!isvalidcreditcard()) {
                return false;
            }
        }
        return $(".error:visible").length == 0 && $("#restaurant").val().length > 0 && $("#reg_phone").val().length > 0 && validaddress();
    }

    //send an order to the server
    function placeorder(StripeResponse) {
        if (!canplaceanorder()) {
            return cantplaceorder();
        }
        if (isUndefined(StripeResponse)) {
            StripeResponse = "";
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
                    handleresult(result, "Order Placed Successfully!");
                    if ($("#saveaddresses").val() == "addaddress") {
                        var Address = {
                            id: $(".ordersuccess").attr("addressid"),
                            buzzcode: "",
                            city: $("#add_city").val(),
                            latitude: $("#add_latitude").val(),
                            longitude: $("#add_longitude").val(),
                            number: $("#add_number").val(),
                            phone: $("#reg_phone").val(),
                            postalcode: $("#add_postalcode").val(),
                            province: $("#add_province").val(),
                            street: $("#add_street").val(),
                            unit: $("#add_unit").val(),
                            user_id: $("#add_user_id").val()
                        };
                        userdetails.Addresses.push(Address);
                        $("#addaddress").remove();
                        $("#saveaddresses").append(AddressToOption(Address) + '<OPTION VALUE="addaddress" ID="addaddress">Add Address</OPTION>');
                    }
                    userdetails["Orders"].unshift({
                        id: $("#receipt_id").text(),
                        placed_at: $("#receipt_placed_at").text(),
                    });
                    clearorder();
                } else {
                    alert("Error:".result, "Order was not placed!");
                }
            });
        } else {
            $("#loginmodal").modal("show");
        }
    }

    if (!Date.now) {
        Date.now = function () {
            return new Date().getTime();
        }
    }

    var modalID = "", skipone = 0;

    $(window).on('shown.bs.modal', function () {
        modalID = $(".modal:visible").attr("id");
        $("#" + modalID).hide();
        $("#" + modalID).fadeIn("fast");
        skipone = Date.now() + 100;//blocks delete button for 1/10 of a second
        switch (modalID) {
            case "profilemodal":
                $("#addresslist").html(addresses());
                $("#cardlist").html(creditcards());
                break;
        }
        window.location.hash = "modal";
    });

    $(window).on('hashchange', function (event) {//delete button closes modal
        if (window.location.hash != "#modal" && window.location.hash != "#loading") {
            if (skipone > Date.now()) {
                return;
            }
            $('#' + modalID).modal('hide');
            log("AUTOHIDE " + modalID);
        }
    });

    //generate a list of addresses and send it to the alert modal
    function addresses() {
        var HTML = '<DIV CLASS="section"><strong>ADDRESS</strong><br>';
        var number = $("#add_number").val();
        var street = $("#add_street").val();
        var city = $("#add_city").val();
        var AddNew = false;//number && street && city;
        $("#saveaddresses option").each(function () {
            var ID = $(this).val();
            if (ID > 0) {
                HTML += '<DIV><A ID="add_' + ID + '" TITLE="Delete this address" onclick="deleteaddress(' + ID + ');" class="cursor-pointer"><i style="color:red" class="fa fa-fw fa-times"></i></A> ';
                HTML += $(this).text() + '</DIV>';
                //if (number.isEqual($(this).attr("number")) && street.isEqual($(this).attr("street")) && city.isEqual($(this).attr("city"))) {AddNew = false;}
                AddNew = true;
            }
        });
        if (!AddNew) {
            //HTML += '<A ONCLICK="deleteaddress(-1);" CLASS="cursor-pointer">Add ' + "'" + number + " " + street + ", " + city + "' to the list</A>";} else {
            HTML += 'No addresses';
        }
        return HTML + "</DIV>";
    }

    function creditcards() {
        var HTML = '<DIV CLASS="section"><strong>CREDIT CARD</strong>';
        if (userdetails.Stripe.length == 0) {
            return HTML + "<br>No Credit Cards";
        }
        for (var i = 0; i < userdetails.Stripe.length; i++) {
            var card = userdetails.Stripe[i];
            HTML += '<DIV><A ONCLICK="deletecard(' + "'" + card.id + "', " + card.last4 + ", '" + card.exp_month.pad(2) + "', " + right(card.exp_year, 2) + ');" CLASS="cursor-pointer">';
            HTML += '<i style="color:red" class="fa fa-fw fa-times"></i></A> **** **** **** ' + card.last4 + ' Expires: ' + card.exp_month.pad(2) + '/' + right(card.exp_year, 2) + '</DIV>';
        }
        return HTML + '</DIV>';
    }

    function deletecard(ID, last4, month, year) {
        confirm2("Are you sure you want to delete the credit card **** **** **** " + last4 + " Expiring on " + month + "/" + year + "?", 'Delete Credit Card', function () {
            $.post(webroot + "placeorder", {
                _token: token,
                action: "deletecard",
                cardid: ID
            }, function (result) {
                alert(result);
            });
        });
    }

    //handles the orders list modal
    function orders(ID, getJSON) {
        if (isUndefined(ID)) {//no ID specified, get a list of order IDs from the user's profile and make buttons
            $("#profilemodal").modal("hide");
            var HTML = '<ul class="list-group">';
            var First = false;
            for (var i = 0; i < userdetails["Orders"].length; i++) {
                var order = userdetails["Orders"][i];
                ID = order["id"];
                if (!First) {
                    First = ID;
                }
                HTML += '<li class="list-group-item" ONCLICK="orders(' + ID + ');">' + '<SPAN ID="pastreceipt' + ID + '"></SPAN>' + '<span class="tag tag-default tag-pill pull-xs-right">ID: ' + ID + '</span>' + order["placed_at"] + '</li>';
            }
            HTML += '</ul>';
            if (!First) {
                HTML = "No orders placed yet";
            }
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
                    setTimeout(function () {
                        loading(true, "SHOWRESULT");
                    }, 10);
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
            return true;
        }
        setTimeout(function () {
            loading(false, "GetNextOrder");
        }, 10);
    }

    function loading(state, where) {
        if (isUndefined(where)) {
            where = "UNKNOWN";
        }
        if (state) {
            log("Loading: " + where);
            $body.addClass("loading");
        } else {
            log("Done Loading: " + where);
            $body.removeClass("loading");
        }
    }

    $(document).ready(function () {
        if (getCookie("theorder")) {
            theorder = JSON.parse(getCookie("theorder"));
        }
        generatereceipt();
        @if(!read("id"))
        $("#loginmodal").modal("show");
        @endif

        /*
             //----- OPEN (manual fade-in)
             $('[data-popup-open]').on('click', function (e) {
                 var targeted_popup_class = jQuery(this).attr('data-popup-open');
                 $('#' + targeted_popup_class).fadeIn("fast");
                 e.preventDefault();
             });
        */

        //----- CLOSE
        $('[data-popup-close]').on('click', function (e) {
            var targeted_popup_class = jQuery(this).attr('data-popup-close');
            /*
                $('#' + targeted_popup_class).fadeOut("fast", function () {
                    $(".modal-backdrop").fadeOut("fast", function () {
                        $('#' + targeted_popup_class).modal("hide");
                    });
                });
                e.preventDefault();
            */
            $('#' + targeted_popup_class).modal("hide");
        });

    });

    function enterkey(e, action) {
        var keycode = event.which || event.keyCode;
        if (keycode == 13) {
            if (action.left(1) == "#") {
                $(action).focus();
            } else {
                handlelogin(action);
            }
        }
    }

    function handlelogin(action) {
        if (isUndefined(action)) {
            action = "verify";
        }
        if (!$("#login_email").val() && action !== "logout") {
            alert("Please enter an email address");
            return;
        }
        $.post(webroot + "auth/login", {
            action: action,
            _token: token,
            email: $("#login_email").val(),
            password: $("#login_password").val()
        }, function (result) {
            try {
                var data = JSON.parse(result);
                if (data["Status"] == "false" || !data["Status"]) {
                    data["Reason"] = data["Reason"].replace('[verify]', '<A onclick="handlelogin();" CLASS="hyperlink" TITLE="Click here to resend the email">verify</A>');
                    alert(data["Reason"], "Error logging in");
                } else {
                    switch (action) {
                        case "login":
                            token = data["Token"];
                            login(data["User"], true);
                            $("#loginmodal").modal("hide");
                            if (redirectonlogin) {
                                log("Login reload");
                                location.reload();
                            }
                            break;
                        case "forgotpassword":
                        case "verify":
                            alert(data["Reason"], "Login");
                            break;
                        case "logout":
                            removeCookie();
                            $('[class^="session_"]').text("");
                            $(".loggedin").hide();
                            $(".loggedout").show();
                            $(".clear_loggedout").html("");
                            $(".profiletype").hide();
                            userdetails = false;
                            if (redirectonlogout) {
                                log("Logout reload");
                                window.location = "<?= webroot("public/index"); ?>";
                            } else {
                                switch (currentRoute) {
                                    case "index"://resave order as it's deleted in removeCookie();
                                        if (!isUndefined(theorder)) {
                                            if (theorder.length > 0) {
                                                createCookieValue("theorder", JSON.stringify(theorder));
                                            }
                                        }
                                        break;
                                }
                            }
                            if (!isUndefined(collapsecheckout)) {
                                collapsecheckout();
                            }
                            break;
                    }
                }
            } catch (err) {
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
            /*
             $("#alert-ok").click(function () {
             });

             $("#alert-confirm").click(function () {
             });   */
            $("#alertmodalbody").html(arguments[0]);
            $("#alertmodallabel").text(title);
            $("#alertmodal").modal('show');
        };
    })();

    var generalhours = <?= json_encode(gethours()) ?>;

    var lockloading = false, previoushash = "";

    $(document).ready(function () {
        //make every AJAX request show the loading animation
        $body = $("body");
        $(document).on({
            ajaxStart: function () {
            //ajaxSend: function ( event, jqxhr, settings ) {log("settings.url: " + settings.url);//use this event if you need the URL
                if (skiploadingscreen) {
                    if (!lockloading) {
                        skiploadingscreen = false;
                    }
                } else {
                    loading(true, "ajaxStart");
                    previoushash = window.location.hash;
                    window.location.hash = "loading";
                }
            },
            ajaxStop: function () {
                loading(false, "ajaxStop");
                skipone = Date.now() + 100;//
                window.location.hash = previoushash;
            }
        });

        @if(isset($user) && $user)
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

        $(".profiletype_not").show();
        $(".profiletype_not" + user["profiletype"]).hide();

        var HTML = '';
        var FirstAddress = false;
        HTML += '<SELECT class="form-control saveaddresses" id="saveaddresses" onchange="addresschanged();"><OPTION value="0">Delivery Address</OPTION>';
        
        if (user["Addresses"].length > 0) {
            addresskeys = Object.keys(user["Addresses"][0]);
            for (i = 0; i < user["Addresses"].length; i++) {
                if (!FirstAddress) {
                    FirstAddress = user["Addresses"][i]["id"];
                }
                HTML += AddressToOption(user["Addresses"][i], addresskeys);
            }
        }
        HTML += '</SELECT>';
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
        var streetformat = "<?= $STREET_FORMAT; ?>";
        //if (address["unit"].trim()) {streetformat = "[unit] - " + streetformat;}
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
        $("#saveaddresses").removeClass("red");
        $('#reg_phone').attr("style", "");
        $(".payment-errors").text("");
        var Selected = $("#saveaddresses option:selected");
        var SelectedVal = $(Selected).val();
//log("Selected: " + SelectedVal);
        var Text = '<?= $STREET_FORMAT; ?>';
        visible_address(false);
        if (addresskeys.length == 0) {
            addresskeys = ["id", "value", "user_id", "number", "unit", "buzzcode", "street", "postalcode", "city", "province", "latitude", "longitude", "phone"];
        }
        for (var keyID = 0; keyID < addresskeys.length; keyID++) {
            var keyname = addresskeys[keyID];
            if (SelectedVal == 0) {
                var keyvalue = "";
            } else {
                var keyvalue = $(Selected).attr(keyname);
            }
            Text = Text.replace("[" + keyname + "]", keyvalue);
            $("#add_" + keyname).val(keyvalue);
        }
        if (SelectedVal == 0) {
            Text = '';
        } else {
            $("#formatted_address").hide();
            if (SelectedVal == "addaddress") {
                visible_address(true);
                Text = "";
            }
            $("#add_unit").show();
        }
        $("#formatted_address").val(Text);
        addresshaschanged();
    }

    //universal AJAX error handling
    $(document).ajaxComplete(function (event, request, settings) {
        loading(false, "ajaxComplete");
        if (request.status != 200 && request.status > 0) {//not OK, or aborted
            var text = request.responseText;
            if (text.indexOf('Whoops, looks like something went wrong.') > -1) {
                text = text.between('<span class="exception_title">', '</h2>');
                text = text.replace(/<(?:.|\n)*?>/gm, '');
                if (text.indexOf('TokenMismatchException') > -1) {
                    text = "Your session has expired. Starting a new one.";
                    $.get(webroot + "auth/gettoken", function (data) {
                        token = data;
                    });
                }
            } else {
                text = request.statusText;
            }
            alert(text + "<BR><BR>URL: " + settings.url, "AJAX error code: " + request.status);
        }
    });

    function rnd(min, max) {
        return Math.round(Math.random() * (max - min) + min);
    }

    function cantplaceorder() {
        if (!validaddress()) {
            $("#saveaddresses").addClass("red");
            $(".payment-errors").text("Please enter an address");
        } else if (!$("#saved-credit-info").val()) {
            if (!isvalidcreditcard()) {
                $("#saved-credit-info").addClass("red");
                $("[data-stripe=number]").addClass("red");
                $(".payment-errors").text("Please select or enter a valid credit card");
                return false;
            }
        }
        if ($("#reg_phone").val().length == 0) {
            $('#reg_phone').attr('style', 'border: 2px solid red !important;');
            $(".payment-errors").text("Please enter a cell phone number");
        }
    }

    function testcard() {
        $('input[data-stripe=number]').val('4242424242424242');
        $('input[data-stripe=address_zip]').val('L8L6V6');
        $('input[data-stripe=cvc]').val(rnd(100, 999));
        $('select[data-stripe=exp_year]').val({{ right($CURRENT_YEAR,2) }} +1);
        @if(islive())
            log("Changing stripe key");
        $("#istest").val("true");
        setPublishableKey('pk_rlgl8pX7nDG2JA8O3jwrtqKpaDIVf', "test");
        log("Stripe key changed");
        @endif
    }

    function payfororder() {
        if (!canplaceanorder()) {
            return cantplaceorder();
        }
        if ($("#orderinfo").find(".error:visible[for]").length > 0) {
            return false;
        }
        var $form = $('#orderinfo');
        $(".payment-errors").html("");

        log("Attempt to pay: " + changecredit());
        if (!changecredit()) {//new card
            log("Stripe data");
            Stripe.card.createToken($form, stripeResponseHandler);
            log("Stripe data - complete");
        } else {//saved card
            log("Use saved data");
            placeorder("");//no stripe token, use customer ID on the server side
        }
        //canplaceorder=false;
    }

    function stripeResponseHandler(status, response) {
        var errormessage = "";
        log("Stripe response");
        switch (status) {
            case 400: errormessage = "Bad Request:<BR>The request was unacceptable, often due to missing a required parameter."; break;
            case 401: errormessage = "Unauthorized:<BR>No valid API key provided."; break;
            case 402: errormessage = "Request Failed:<BR>The parameters were valid but the request failed."; break;
            case 404: errormessage = "Not Found:<BR>The requested resource doesn't exist."; break;
            case 409: errormessage = "Conflict:<BR>The request conflicts with another request (perhaps due to using the same idempotent key)."; break;
            case 429: errormessage = "Too Many Requests:<BR>Too many requests hit the API too quickly. We recommend an exponential backoff of your requests."; break;
            case 500: case 502: case 503: case 504:errormessage = "Server Errors:<BR>Something went wrong on Stripe's end.";break;
            case 200:// - OK	Everything worked as expected.
                if (response.error) {
                    $('.payment-errors').html(response.error.message);
                } else {
                    log("Stripe successful");
                    placeorder(response.id);
                }
                break;
        }
        if (errormessage) {
            //$(".payment-errors").html(errormessage + "<BR><BR>" + response["error"]["type"] + ":<BR>" + response["error"]["message"]);
            $(".payment-errors").html(response["error"]["message"]);
        }
    }

    function addresshaschanged() {
        if (!getcloseststore) {
            return;
        }
        var formdata = getform("#orderinfo");
        if (!formdata.latitude || !formdata.longitude) {
            return;
        }
        if (!debugmode) {
            formdata.radius = MAX_DISTANCE;
        }
        skiploadingscreen = true;
        //canplaceorder = false;

        $.post(webroot + "placeorder", {
            _token: token,
            info: formdata,
            action: "closestrestaurant"
        }, function (result) {
            if (handleresult(result)) {
                var closest = JSON.parse(result)["closest"];
                var restaurant = "No restaurant is within range";
                //canplaceorder = false;
                if (closest.hasOwnProperty("id")) {
                    if (parseFloat(closest.distance) <= MAX_DISTANCE || debugmode) {
                        if (parseFloat(closest.distance) >= MAX_DISTANCE) {
                            closest.restaurant.name += " [DEBUG]"
                        }
                        //canplaceorder = true;
                        restaurant = closest.restaurant.name;
                        GenerateHours(closest["hours"]);
                    }
                }
                $("#restaurant").val(restaurant);
            }
        });
    }

    function testclosest() {
        var formdata = getform("#orderinfo");
        formdata.limit = 10;
        if (!formdata.latitude || !formdata.longitude) {
            alert("longitude/latitude missing");
            return;
        }
        $.post(webroot + "placeorder", {
            _token: token,
            info: formdata,
            action: "closestrestaurant"
        }, function (result) {
            if (handleresult(result)) {
                alert(result, "10 closest restaurants");
            }
        });
    }

    function loadsavedcreditinfo() {
        if (userdetails.stripecustid.length > 0) {
            return userdetails.Stripe.length > 0;
        }
        return false;
    }

    function changecredit() {
        $("#saved-credit-info").removeClass("red");
        $("[data-stripe=number]").removeClass("red");
        var val = $("#saved-credit-info").val();
        if (!val) {
            $(".credit-info").show();//let cust edit the card
        } else {
            $(".credit-info").hide();//use saved card info
        }
        return val;
    }

    function showcheckout() {
        //canplaceorder=false;
        if (userdetails["Addresses"].length == 0) {
            setTimeout(function () {
                $("#saveaddresses").val("addaddress");
                addresschanged();
            }, 100);
        }
        addresschanged();
        $("#restaurant").val("");
        var HTML = $("#checkoutaddress").html();
        HTML = HTML.replace('class="', 'class="corner-top ');
        if (loadsavedcreditinfo()) {
            $(".credit-info").hide();
            var creditHTML = '<SELECT ID="saved-credit-info" name="creditcard" onchange="changecredit();" class="form-control proper-height">';
            for (var i = 0; i < userdetails.Stripe.length; i++) {
                var card = userdetails.Stripe[i];
                creditHTML += '<OPTION value="' + card.id + '"';
                if (i == userdetails.Stripe.length - 1) {
                    creditHTML += ' SELECTED';
                }
                creditHTML += '>**** **** **** ' + card.last4 + ' Expires: ' + card.exp_month.pad(2) + '/' + right(card.exp_year, 2) + '</OPTION><OPTION value="">Add Card</OPTION>';
            }
            $("#credit-info").html(creditHTML + '</SELECT>');
        } else {
            $("#credit-info").html('<INPUT TYPE="hidden" VALUE="" ID="saved-credit-info">');
        }
        $("#checkoutaddress").html(HTML);
        $("#checkoutmodal").modal("show");
        $(function () {
            $("#orderinfo").validate({
                submitHandler: function (form) {
                    //handled by placeorder
                }
            });
        });
    }

    var daysofweek = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    var monthnames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

    function now() {
        var now = new Date();
        return now.getHours() * 100 + now.getMinutes();
    }

    function getToday() {
        var now = new Date();//doesn't take into account <= because it takes more than 1 minute to place an order
        return now.getDay();
    }

    function GenerateTime(time) {
        var minutes = time % 100;
        var thehours = Math.floor(time / 100);
        var hoursAMPM = thehours % 12;
        if (hoursAMPM == 0) {
            hoursAMPM = 12;
        }
        var tempstr = hoursAMPM + ":";
        if (minutes < 10) {
            tempstr += "0" + minutes;
        } else {
            tempstr += minutes;
        }
        var extra = "";
        if (time == 0) {
            extra = " (Midnight)";
        } else if (time == 1200) {
            extra = " (Noon)";
        }
        if (time < 1200) {
            return tempstr + " AM" + extra;
        } else {
            return tempstr + " PM" + extra;
        }
    }

    function GenerateHours(hours, increments) {
        var now = new Date();//doesn't take into account <= because it takes more than 1 minute to place an order
        if (isUndefined(increments)) {
            increments = 15;
        }
        var dayofweek = now.getDay();
        var minutesinaday = 1440;
        var totaldays = 2;
        var dayselapsed = 0;
        var today = dayofweek;
        var tomorrow = (today + 1) % 7;
        var time = now.getHours() * 100 + now.getMinutes();
        time = time + (increments - (time % increments));
        var oldValue = $("#deliverytime").val();
        var HTML = '<option>Deliver Now</option>';
        var totalInc = (minutesinaday * totaldays) / increments;
        for (var i = 0; i < totalInc; i++) {
            if (isopen(hours, dayofweek, time) > -1) {
                var minutes = time % 100;
                if (minutes < 60) {
                    var thetime = GenerateTime(time);
                    var thedayname = daysofweek[dayofweek];
                    var thedate = monthnames[now.getMonth()] + " " + now.getDate();
                    if (dayofweek == today) {
                        thedayname = "Today";
                    } else if (dayofweek == tomorrow) {
                        thedayname = "Tomorrow";
                    } else {
                        thedayname += " " + thedate;
                    }
                    var tempstr = '<OPTION VALUE="' + thedate + " at " + time.pad(4) + '">' + thedayname + " at " + thetime;
                    HTML += tempstr + '</OPTION>';
                }
            }

            time = time + increments;
            if (time % 100 >= 60) {
                time = (Math.floor(time / 100) + 1) * 100;
            }
            if (time >= 2400) {
                time = time % 2400;
                dayselapsed += 1;
                dayofweek = (dayofweek + 1) % 7;
                now = new Date(now.getTime() + 24 * 60 * 60 * 1000);
                if (dayofweek == today || dayselapsed == totaldays) {
                    i = totalInc;
                }
            }
        }

        $("#deliverytimealias").html(HTML);
        $("#deliverytime").html(HTML);
        $("#deliverytime").val(oldValue);
    }

    function isopen(hours, dayofweek, time) {
        var now = new Date();//doesn't take into account <= because it takes more than 1 minute to place an order
        if (isUndefined(dayofweek)) {
            dayofweek = now.getDay();
        }
        if (isUndefined(time)) {
            time = now.getHours() * 100 + now.getMinutes();
        }
        var today = hours[dayofweek];
        var yesterday = dayofweek - 1;
        if (yesterday < 0) {
            yesterday = 6;
        }
        var yesterdaysdate = yesterday;
        yesterday = hours[yesterday];
        today.open = Number(today.open);
        today.close = Number(today.close);
        yesterday.open = Number(yesterday.open);
        yesterday.close = Number(yesterday.close);
        if (yesterday.open > -1 && yesterday.close > -1 && yesterday.close < yesterday.open) {
            if (yesterday.close > time) {
                return yesterdaysdate;
            }
        }
        if (today.open > -1 && today.close > -1) {
            if (today.close < today.open) {
//log("Day: " + dayofweek + " time: " + time + " open: " + today.open + " close: " + today.close );
                if (time >= today.open || time < today.close) {
                    return dayofweek;
                }
            } else {
                if (time >= today.open && time < today.close) {
                    return dayofweek;
                }
            }
        }
        return -1;//closed
    }

    function visiblemodals() {
        return $('.modal:visible').map(function () {
            return this.id;
        }).get();
    }

</SCRIPT>

<STYLE>
    /* STOP MOVING THIS TO THE CSS, IT WON'T WORK! */
    #loadingmodal {
        display: none;
        position: fixed;
        z-index: 1000;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        background: rgba(255, 255, 255, .8) url('<?= webroot("resources/assets/images/slice.gif"); ?>') 50% 50% no-repeat;
    }
</STYLE>

<div class="modal" id="alertmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static" style="z-index: 9999;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-popup-close="alertmodal" old-data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-close"></i>
                </button>

                <strong class="modal-title" id="alertmodallabel">Title</strong>
            </div>

            <div class="modal-body">
                <DIV ID="alertmodalbody"></DIV>

                <div CLASS="pull-right">
                    <button class="btn btn-secondary" id="alert-cancel" data-dismiss="modal">
                        Cancel
                    </button>
                    <button class="btn btn-secondary" id="alert-confirm" data-dismiss="modal">
                        OK
                    </button>
                </div>
                <DIV CLASS="clearfix"></DIV>
            </div>
        </div>
    </div>
</DIV>

<SCRIPT>
    var oneclick = true, currentstyle = 1, currentbasecost = 0, currentaddoncost = 0;
    var currentaddontype = "", currentside = "", currentqualifier = "", addonname = "", hashalves = true;
    var currentaddonlist = new Array, currentitemindex = 0, currentitemname = "";

    function toclassname(text) {
        return text.toLowerCase().replaceAll(" ", "_");
    }

    function generateaddons() {
        var HTML = '';
        var totaltoppings = 0;

        switch (currentaddontype) {
            case "toppings":
                addonname = "Toppings";
                break;
            case "wings_sauce":
                addonname = "Sauces";
                break;
            default:
                addonname = "Error: " + currentaddontype;
                break;
        }

        var thisside = ' CLASS="thisside" ><I CLASS="fa fa-check text-danger"></I>';

        for (var itemindex = 0; itemindex < currentaddonlist.length; itemindex++) {
            var freetoppings = 0;
            var paidtoppings = 0;

            var tempstr = '';
            var classname = 'itemcontents itemcontents' + itemindex;

            HTML += '<DIV style="border:2px solid transparent;border-radius:.2rem;padding:.25rem;" ONCLICK="selectitem(event, ' + itemindex + ');" CLASS="col-xs-12 currentitem currentitem' + itemindex;
            if (currentitemindex == itemindex) {
                HTML += ' thisside';
            }
            HTML += '">' +         ucfirst(addonname) +            ' #' + (itemindex + 1) + ' ';

            if (currentaddonlist[itemindex].length == 0) {
                tempstr += '<div class="">No ' + addonname + ' Yet</div>';
            }

            for (var i = 0; i < currentaddonlist[itemindex].length; i++) {
                /*
                 if (qualifiers[currentaddontype].hasOwnProperty(addonname)) {
                 qualifier = qualifiers[currentaddontype][addonname][currentaddon.qual];
                 } else {
                 qualifier = qualifiers["DEFAULT"][currentaddon.qual];
                 }
                 */

                var currentaddon = currentaddonlist[itemindex][i];
                var qualifier = "";
                tempstr += '<DIV CLASS="pill btn btn-sm btn-secondary ' + classname + '">' + currentaddon.name + '<span CLASS="pull-right" ONCLICK="removelistitem(' + itemindex + ', ' + i + ');">&nbsp; <i CLASS="fa fa-times"></i> </span></div>&nbsp;';

                qualifier = currentaddon.qual;
                if (qualifier == 0) {
                    qualifier = 0.5;
                } else if (currentaddon.side != 1) {
                    qualifier = qualifier * 0.5;
                }
                if (isaddon_free(currentaddontype, currentaddon.name)) {
                    freetoppings += qualifier;
                } else {
                    paidtoppings += qualifier;
                }
            }
            totaltoppings += Math.ceil(paidtoppings);
            if (debugmode) {//if(paidtoppings > 0 || freetoppings > 0){
                 HTML += " (Paid: " + paidtoppings + " Free: " + freetoppings + ')';
            }
            HTML += '<br>' + tempstr + '</DIV>';
        }

        var totalcost = getcost(totaltoppings);
        $("#modal-itemtotalprice").text(totalcost);
        $("#theaddons").html(HTML);
        $(".currentitem.thisside").trigger("click");
    }

    function getcost(Toppings) {
        //itemcost, itemname, size, toppingcost
        if (currentitem.toppingcost) {
            var itemcost = parseFloat(currentitem.itemcost.replace("$", ""));
            itemcost += parseFloat(currentitem.toppingcost) * Number(Toppings);
            return itemcost.toFixed(2);// + " (" + Toppings + " addons)";
        }
        return $("#modal-itemprice").text();
    }

    function list_addons_quantity(quantity, tablename, halves, name, basecost, addoncost) {
        currentaddonlist = new Array();
        currentitemindex = 0;
        for (var i = 0; i < quantity; i++) {
            currentaddonlist.push([]);
        }
        currentitemname = name;
        currentbasecost = basecost;
        currentaddoncost = addoncost;
        list_addons(tablename, halves);
    }

    function list_addons(table, halves) {
        currentaddontype = table;
        var HTML = '<DIV style="height:130px;overflow-y: auto; overflow-x: auto; padding:5px;"><DIV id="theaddons"></DIV></DIV>';
        if (currentstyle == 0) {
            HTML += '<DIV CLASS=" addonlist" ID="addontypes">';
        } else {
        }

        var types = Object.keys(alladdons[table]);

        if (currentstyle == 0) {
            $("#addonlist").html(HTML + '</DIV>');
        } else {
            //var colors = ["secondary", "secondary", "secondary", "secondary", "secondary"];//' + colors[i] + '


            for (var i = 0; i < types.length; i++) {
                //HTML += '<strong class="col-xs-12 btn-sm" id="' + toclassname(types[i]) + '">' + types[i] + '</strong>';
                HTML += '<div style="background:#dadada; border-radius:0 !important;font-weight: bold;" class="col-xs-12 btn-sm" id="' + toclassname(types[i]) + '">' + types[i] + '</div>';
                for (var i2 = 0; i2 < alladdons[currentaddontype][types[i]].length; i2++) {
                    var addon = alladdons[currentaddontype][types[i]][i2];
                    var title = "";
                    HTML += '<div class="col-xs-4 col-sm-3 btn-sm  toppings_btn btn addon-addon';
                    if (isaddon_free(String(currentaddontype), String(addon))) {
                        HTML += ' btn-secondary';
                        title = "Free addon";
                    } else {
                        HTML += ' btn-secondary'
                    }
                    HTML += '" TITLE="' + title + '">' + addon + '</div>';
                }
            }

            $("#addonlist").html(HTML);
            $(".addon-addon").click(
                function (event) {
                    list_addon_addon(event);
                }
            );
        }
        $(".addon-type").click(
            function (event) {
                list_addon_type(event);
            }
        );
        hashalves = halves;
        generateaddons();
    }

    function list_addon_type(e) {
        $(".addon-type").removeClass("addon-selected");
        $(e.target).addClass("addon-selected");
        $("#addonall").remove();
        $("#addonedit").remove();
        var HTML = '<DIV ID="addonall">';
        var addontype = $(e.target).text();
        for (var i = 0; i < alladdons[currentaddontype][addontype].length; i++) {
            var addon = alladdons[currentaddontype][addontype][i];
            HTML += '<DIV class=" addon-addon">' + addon + '</DIV>';
        }
        $(e.target).after(HTML + '</DIV>');
        $(".addon-addon").click(
            function () {
                list_addon_addon(event);
            }
        );
    }

    function list_addon_addon(e) {
        addonname = $(e.target).text();
        if (oneclick) {
            currentqualifier = 1;
            return addtoitem();
        }
        $(".addon-addon").removeClass("addon-selected");
        $(e.target).addClass("addon-selected");
        $("#addonedit").remove();
        var HTML = '<DIV ID="addonedit">';
        if (isaddon_free(currentaddontype, addonname)) {
            HTML += '<DIV>This is a free addon</DIV>';
        }

        if (hashalves) {
            if (isaddon_onall(currentaddontype, addonname)) {
                HTML += '<DIV>This addon goes on the whole item</DIV>';
                currentside = 1;
            } else {
                HTML += makelist("Side", "addon-side", ["Left", "Whole", "Right"], 1);
            }
        }

        if (qualifiers[currentaddontype].hasOwnProperty(addonname)) {
            HTML += makelist("Qualifier", "addon-qualifier", qualifiers[currentaddontype][addonname], 1);
        } else {
            HTML += makelist("Qualifier", "addon-qualifier", qualifiers["DEFAULT"], 1);
        }

        HTML += '<BUTTON ONCLICK="addtoitem();"">Add to item</BUTTON>';
        $(e.target).after(HTML + '</DIV>');
    }

    function makelist(Title, classname, data, defaultindex) {
        var HTML = '<DIV><DIV CLASS="">' + Title + ':</DIV>';
        var selected;
        for (var i = 0; i < data.length; i++) {
            selected = "";
            if (i == defaultindex) {
                selected = " addon-selected";
            }
            HTML += '<DIV CLASS=" addon-list  ' + classname + selected + '" ONCLICK="list_addon_list(event, ' + "'" + classname + "', " + i + ');">' + data[i] + '</DIV>';
        }
        switch (classname) {
            case "addon-qualifier":
                currentqualifier = defaultindex;
                break;
            case "addon-side":
                currentside = defaultindex;
                break;
        }
        return HTML + '</DIV>';
    }

    function list_addon_list(e, classname, index) {
        var listitemname = $(e.target).text();
        //if(classname == "addon-qualifier" && index == 0){index = "0.5";}
        $("." + classname).removeClass("addon-selected");
        $(e.target).addClass("addon-selected");
        switch (classname) {
            case "addon-qualifier":
                currentqualifier = index;
                break;
            case "addon-side":
                currentside = index;
                break;
        }
        log(classname + "." + listitemname + "=" + index);
    }

    function addtoitem() {
        if (!hashalves) {
            currentside = 1;
        }
        var removed = "";
        var group = getaddon_group(currentaddontype, addonname);
        currentaddonlist[currentitemindex].push({
            name: addonname,
            side: currentside,
            qual: currentqualifier,
            type: currentaddontype,
            group: group
        });
        if (group > 0) {
            for (var i = currentaddonlist[currentitemindex].length - 2; i > -1; i--) {
                if (currentaddonlist[currentitemindex][i]["group"] == group) {
                    removed = currentaddonlist[currentitemindex][i]["name"];
                    if (removed == addonname) {
                        removed = "";
                    }
                    removelistitem(currentitemindex, i);
                }
            }
        }
        if (!oneclick) {
            $(".addon-selected").removeClass("addon-selected");
            $("#addonall").remove();
            $("#addonedit").remove();
        }
        if (removed) {
            removed += " was removed";
        }
        $("#removelist").text(removed);
        generateaddons();
    }

    function selectitem(e, index) {
        $(".currentitem").removeClass("thisside");
        $(".currentitem" + index).addClass("thisside");
        /*
         $(".itemcontents").hide();
         $(".itemcontents" + index).show();
         */
        currentitemindex = index;
    }

    function removelistitem(index, subindex) {
        if (isUndefined(subindex)) {
            removeindex(currentaddonlist, index);
        } else {
            removeindex(currentaddonlist[index], subindex);
        }
        generateaddons();
    }

    function ucfirst(text) {
        return text.left(1).toUpperCase() + text.right(text.length - 1);
    }

    function visible_address(state) {
        if (state) {
            $("#formatted_address").show();
            $("#add_unit").show();
        } else {
            $("#formatted_address").hide();
            $("#add_unit").hide();
        }
    }


    @if(read("id"))
        $( document ).ready(function() {
            <?php if (islive() || $GLOBALS["testlive"]) {
                echo "setPublishableKey('pk_vnR0dLVmyF34VAqSegbpBvhfhaLNi', 'live')";
            } else {
                echo "setPublishableKey('pk_rlgl8pX7nDG2JA8O3jwrtqKpaDIVf', 'test');";
            }?>
        });

        function setPublishableKey(Key, mode){
            Stripe.setPublishableKey(Key);
            @if(!islive())
                log(mode + " stripe mode");
            @endif
        }
    @endif
</SCRIPT>

<script type="text/javascript">
    function checkblock(e) {
        var checked = $(e.target).is(':checked');
        BeforeUnload(checked);
    }
    function BeforeUnload(enable) {
        if (enable) {
            window.onbeforeunload = function (e) {
                return "Discard changes?";
            };
            log("Page transitions blocked");
        } else {
            window.onbeforeunload = null;
            log("Page transitions allowed");
        }
    }
</script>

<?php endfile("popups_alljs"); ?>