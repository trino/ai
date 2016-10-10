<?php
if (!isset($style)) {
    $style = 0;
}
switch ($style) {
    case 0:
        echo '<DIV CLASS="row"><DIV CLASS="col-md-2">Address:</DIV><DIV CLASS="col-md-10"><INPUT TYPE="text" ID="formatted_address"></div></DIV>';
        break;
    case 1:
        echo '<INPUT TYPE="text" ID="formatted_address" PLACEHOLDER="Address" class="form-control">';
        echo '<STYLE>.address.form-control:focus{z-index: 999;}</STYLE>';
        break;
}
?>


<FORM ID="googleaddress">
    <div class="input-group-vertical">
        <?php

        if (!isset($user_id)) {
            $user_id = read("id");
        }

        $fields = array(
                "Unit / Apt / Buzz Code / Address Notes" => array("type" => "text", "name" => "unit"),
                "Street Number" => array("type" => "text", "name" => "number", "class" => "street_number", "readonly" => true),
                "Street" => array("type" => "text", "name" => "street", "class" => "route", "readonly" => true),
                "City" => array("type" => "text", "name" => "city", "class" => "locality", "readonly" => true, "half" => "start"),
                "Province" => array("type" => "text", "name" => "province", "class" => "administrative_area_level_1", "readonly" => true, "half" => "end"),
                "Postal Code" => array("type" => "hidden", "name" => "postalcode", "class" => "postal_code", "readonly" => true, "half" => "end"),
                "Latitude" => array("type" => "hidden", "name" => "latitude", "class" => "latitude", "readonly" => true, "half" => "start"),
                "Longitude" => array("type" => "hidden", "name" => "longitude", "class" => "longitude", "readonly" => true, "half" => "end"),
                "user_id" => array("type" => "hidden", "name" => "user_id", "value" => $user_id, "class" => "session_id_val")
        );

        foreach ($fields as $Name => $field) {
            if ($style == 0 && $field["type"] != "hidden") {
                echo '<DIV CLASS="row"><DIV CLASS="col-md-2 data_' . $field["name"] . '">' . $Name . ':</DIV><DIV CLASS="col-md-10">';
            }
            if ($style == 1 && isset($field["half"])) {
                if ($field["half"] == "start") {
                    echo '<div class="input-group">';
                }
                echo '<span class="input-group-btn" style="width: 50% !important;">';
            }
            echo '<INPUT TYPE="' . $field["type"] . '" NAME="' . $field["name"] . '" ID="add_' . $field["name"] . '"';
            if ($style == 1) {
                echo ' PLACEHOLDER="' . $Name . '"';
                if (!isset($field["class"])) {
                    $field["class"] = "";
                }
                $field["class"] .= " address form-control";
            }
            if (isset($field["class"])) {
                echo ' CLASS="' . $field["class"] . '" ';
            }
            if (isset($field["value"])) {
                echo ' value="' . $field["value"] . '" ';
            }
            if (isset($field["readonly"])) {
                echo ' readonly';
            }
            echo '>';
            if ($style == 0 && $field["type"] != "hidden") {
                echo '</DIV></DIV>';
            }
            if ($style == 1 && isset($field["half"])) {
                echo '</span>';
                if ($field["half"] == "end") {
                    echo '</div>';
                }
            }
        }
        ?>
    </div>

    @if (isset($saveaddress))
        <DIV CLASS="col-md-12">
            <button class="btn btn-link btn-sm" onclick="addresses();"
                    title="Edit the addresses saved to your profile">
                EDIT ADDRESSES
            </button>
            <button ID="saveaddressbtn" class="btn btn-link btn-sm" disabled onclick="deleteaddress(-2);"
                    title="Save this address to your profile">
                SAVE ADDRESS
            </button>
        </DIV>
    @endif
</FORM>




<SCRIPT>
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
        } else if (confirm("Are you sure you want to delete '" + $("#add_" + ID).text().trim() + "'?")) {
            $.post("<?= webroot("public/list/useraddresses"); ?>", {
                _token: token,
                action: "deleteitem",
                id: ID
            }, function (result) {
                if (handleresult(result)) {
                    $("#add_" + ID).fadeOut(1000, function () {
                        $("#add_" + ID).remove();
                    });
                    $(".saveaddresses option[value=" + ID + "]").remove();
                }
            });
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
        var lat = place.geometry.location.lat();
        var lng = place.geometry.location.lng();

        var addressdata = {};

        $('.formatted_fordb').val(place.formatted_address); // this formatted_address is a google maps object
        $('.latitude').val(lat);
        $('.longitude').val(lng);

        var componentForm = {
            street_number: 'short_name',
            route: 'long_name',
            locality: 'long_name',
            administrative_area_level_1: 'long_name',
            country: 'long_name',
            postal_code: 'short_name'
        };
        var streetformat = "[street_number] [route], [locality]";
        for (var i = 0; i < place.address_components.length; i++) {
            var addressType = place.address_components[i].types[0];
            if (componentForm[addressType]) {
                var val = place.address_components[i][componentForm[addressType]];
                addressdata[addressType] = val;
                log(addressType + " = " + val);
                streetformat = streetformat.replace("[" + addressType + "]", val);
                $('.' + addressType).val(val);
            }
        }

        if (isnewaddress(addressdata["street_number"], addressdata["route"], addressdata["locality"])) {
            $("#saveaddressbtn").removeAttr("disabled");
        } else {
            $("#saveaddressbtn").attr("disabled", true);
        }

        $('.formatted_address').val(streetformat);
        return place;
    }
</SCRIPT>
<?php
if (!isset($dontincludeGoogle)) {
    echo '<script src="https://maps.googleapis.com/maps/api/js?signed_in=true&libraries=places&callback=initAutocomplete&key=AIzaSyBWSUc8EbZYVKF37jWVCb3lpBQwWqXUZw8"></script>';
}
?>