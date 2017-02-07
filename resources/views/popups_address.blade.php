<?php
    startfile("popups_address");
    if (!isset($style)) {$style = 0;}
    switch ($style) {
        case 0:
            echo '<DIV CLASS="form-control row"><DIV CLASS="form-control col-md-2">Address:</DIV><DIV CLASS="form-control col-md-10"><INPUT TYPE="text" ID="formatted_address" name="formatted_address"></div></DIV>';
            break;
        case 1:
            echo '<INPUT TYPE="text" ID="formatted_address" PLACEHOLDER="Delivery Address" CLASS="form-control formatted_address" name="formatted_address">';
            echo '<STYLE>.address.:focus{z-index: 999;}</STYLE>';
            break;
    }
    if (!isset($user_id)) {$user_id = read("id");}
    if (!isset($form)) {$form = true;}
?>
<STYLE>
    .pac-container {
        z-index: 9999 !important;
    }
</STYLE>
    @if($form) <FORM ID="googleaddress"> @endif
        <INPUT TYPE="text" NAME="unit" ID="add_unit" PLACEHOLDER="Address Notes" CLASS="form-control address">
        <INPUT TYPE="text" NAME="number" ID="add_number" PLACEHOLDER="Street Number" CLASS="form-control street_number address dont-show">
        <INPUT TYPE="text" NAME="street" ID="add_street" PLACEHOLDER="Street" CLASS="form-control route address dont-show">
        <INPUT TYPE="text" NAME="city" ID="add_city" PLACEHOLDER="City" CLASS="form-control locality address dont-show">
        <INPUT TYPE="text" NAME="province" ID="add_province" PLACEHOLDER="Province" CLASS="form-control administrative_area_level_1 address dont-show">
        <INPUT TYPE="text" NAME="postalcode" ID="add_postalcode" PLACEHOLDER="Postal Code" CLASS="form-control postal_code address dont-show">
        <INPUT TYPE="text" NAME="latitude" ID="add_latitude" PLACEHOLDER="Latitude" CLASS="form-control latitude address dont-show">
        <INPUT TYPE="text" NAME="longitude" ID="add_longitude" PLACEHOLDER="Longitude" CLASS="form-control longitude address dont-show">
        <INPUT TYPE="hidden" NAME="user_id" ID="add_user_id" PLACEHOLDER="user_id" CLASS="form-control session_id_val address" value="{{$user_id}}">
    @if($form) </FORM> @endif

@if(isset($saveaddress) && false)
    <DIV CLASS="form-control col-md-12">
        <button CLASS="form-control btn btn-link btn-sm" onclick="editaddresses();" title="Edit the addresses saved to your profile">
            EDIT ADDRESSES
        </button>
        <button ID="saveaddressbtn" CLASS="form-control btn btn-link btn-sm" disabled onclick="deleteaddress(-2);" title="Save this address to your profile">
            SAVE ADDRESS
        </button>
    </DIV>
@endif

<SCRIPT>
    function editaddresses() {
        $("#checkoutmodal").modal("hide");
        $("#profilemodal").modal("show");
    }

    function isnewaddress(number, street, city) {
        var AddNew = number && street && city;
        $("#saveaddresses option").each(function () {
            var ID = $(this).val();
            if (ID > 0) {
                if (number.isEqual($(this).attr("number")) && street.isEqual($(this).attr("street")) && city.isEqual($(this).attr("city"))) {
                    return false;
                }
            }
        });
        return AddNew;
    }

    function deleteaddress(ID) {
        if (ID < 0) {//add new address
            var address = getform("#orderinfo");
            $.post(webroot + "placeorder", {
                _token: token,
                info: address
            }, function (result) {
                address["id"] = result;
                var HTML = AddressToOption(address);
                $(".saveaddresses").append(HTML);
                if (ID == -1) {
                    addresses();
                }
            });
        } else {
            //confirm2("Are you sure you want to delete '" + $("#add_" + ID).text().trim() + "'?", 'Delete Address', function () {
                $.post("<?= webroot("public/list/useraddresses"); ?>", {
                    _token: token,
                    action: "deleteitem",
                    id: ID
                }, function (result) {
                    if (handleresult(result)) {
                        $("#add_" + ID).fadeOut(500, function () {
                            $("#add_" + ID).remove();
                        });
                        $(".saveaddresses option[value=" + ID + "]").remove();
                    }
                });
            //});
        }
    }

    function initAutocomplete() {
        formatted_address = new google.maps.places.Autocomplete(
                /** @type {!HTMLInputElement} */(document.getElementById('formatted_address')), {
                    types: ['geocode'],
                    componentRestrictions: {country: "ca"}
                });
        formatted_address.addListener('place_changed', fillInAddress);
    }

    function fillInAddress() {
        // Get the place details from the formatted_address object.
        var place = formatted_address.getPlace();
        log(JSON.stringify(place));
        var lat = place.geometry.location.lat();
        var lng = place.geometry.location.lng();

        var addressdata = {};

        $('.formatted_fordb').val(place.formatted_address); // this formatted_address is a google maps object
        $('.latitude').val(lat);
        $('.longitude').val(lng);

        var componentForm = {
            street_number: 'short_name',
            route: 'long_name',//street name
            locality: 'long_name',//ON Canada
            administrative_area_level_1: 'long_name',
            country: 'long_name',
            postal_code: 'short_name'
        };
        //2396 Kingsway, locality: Vancouver, administrative_area_level_1: British Columbia, country: Canada, postal_code: V5R 5G9
        var streetformat = "[street_number] [route], [locality], [administrative_area_level_1_s] [postal_code]";
        for (var i = 0; i < place.address_components.length; i++) {
            var addressType = place.address_components[i].types[0];
            if (componentForm[addressType]) {
                var val = place.address_components[i][componentForm[addressType]];
                addressdata[addressType] = val;
                streetformat = streetformat.replace("[" + addressType + "]", val);
                $('.' + addressType).val(val);

                val = place.address_components[i]['short_name'];
                streetformat = streetformat.replace("[" + addressType + "_s]", val);
                val = place.address_components[i]['long_name'];
                streetformat = streetformat.replace("[" + addressType + "_l]", val);
            }
        }
        if (isnewaddress(addressdata["street_number"], addressdata["route"], addressdata["locality"])) {
            $("#saveaddressbtn").removeAttr("disabled");
        } else {
            $("#saveaddressbtn").attr("disabled", true);
        }
        $('.formatted_address').val(streetformat);
        if (isFunction(addresshaschanged)) {
            addresshaschanged();
        }
        return place;
    }
</SCRIPT>
<?php
    if (!isset($dontincludeGoogle)) {
        echo '<script src="https://maps.googleapis.com/maps/api/js?signed_in=true&libraries=places&callback=initAutocomplete&key=AIzaSyBWSUc8EbZYVKF37jWVCb3lpBQwWqXUZw8"></script>';
    }
    endfile("popups_address");
?>