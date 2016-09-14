<?php
    if(!isset($style)){$style = 0;}
    switch($style){
        case 0:
            echo '<DIV CLASS="row"><DIV CLASS="col-md-2">Address:</DIV><DIV CLASS="col-md-10"><INPUT TYPE="text" ID="formatted_address"></div></DIV>';
            break;
        case 1:
            echo '<INPUT TYPE="text" ID="formatted_address" PLACEHOLDER="Address" class="form-control">';
            break;
    }
?>
<STYLE>
    .address.form-control:focus{
        z-index: 999;
    }
</STYLE>
<FORM ID="googleaddress">
    <?php
        $fields = array(
            "Number" => array("type" => "text", "name" => "number", "class" => "street_number", "readonly" => true),
            "Street" => array("type" => "text", "name" => "street", "class" => "route", "readonly" => true),
            "Unit/Apt" => array("type" => "text", "name" => "unit"),
            "Buzz code" => array("type" => "text", "name" => "buzzcode"),
            "City" => array("type" => "text", "name" => "city", "class" => "locality", "readonly" => true),
            "Province" => array("type" => "text", "name" => "province", "class" => "administrative_area_level_1", "readonly" => true),
            "Postal Code" => array("type" => "text", "name" => "postalcode", "class" => "postal_code", "readonly" => true),
            "Latitude" => array("type" => "text", "name" => "latitude", "class" => "latitude", "readonly" => true),
            "Longitude" => array("type" => "text", "name" => "longitude", "class" => "longitude", "readonly" => true),
        );
        if(isset($user_id)){
            $fields["user_id"] = array("type" => "hidden", "name" => "user_id", "value" => $user_id);
        }
        foreach($fields as $Name => $field){
            if($style == 0 && $field["type"] != "hidden"){echo '<DIV CLASS="row"><DIV CLASS="col-md-2">' . $Name . ':</DIV><DIV CLASS="col-md-10">';}
            echo '<INPUT TYPE="' . $field["type"] . '" NAME="' . $field["name"] . '" ID="add_' . $field["name"] . '"';
            if($style == 1){
                echo ' PLACEHOLDER="' . $Name . '"';
                if(!isset($field["class"])){$field["class"] = "";}
                $field["class"] .= " address form-control";
            }
            if(isset($field["class"])){echo ' CLASS="' . $field["class"] . '" ';}
            if(isset($field["value"])){echo ' value="' . $field["value"] . '" ';}
            if(isset($field["readonly"])){echo ' readonly';}
            echo '>';
            if($style == 0 && $field["type"] != "hidden"){echo '</DIV></DIV>';}
        }
    ?>
</FORM>
<SCRIPT>
    function initAutocomplete(){
        formatted_address = new google.maps.places.Autocomplete(
                /** @type {!HTMLInputElement} */(document.getElementById('formatted_address')),
                {
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
        $('#city').val('');
        $('#formatted_address').val('');
        $('#postal_code').val('');
        $("#province").val('');
        var streetformat = "[street_number] [route], [locality]";

        for (var i = 0; i < place.address_components.length; i++) {
            var addressType = place.address_components[i].types[0];
            if (componentForm[addressType]) {
                var val = place.address_components[i][componentForm[addressType]];
                log(addressType + " = " + val);
                streetformat = streetformat.replace("[" + addressType + "]", val);
                $('.' + addressType).val(val);
            }
        }

        $('.formatted_address').val(streetformat);
        return place;
    }
</SCRIPT>
<?php
    if(!isset($dontincludeGoogle)){
        echo '<script src="https://maps.googleapis.com/maps/api/js?signed_in=true&libraries=places&callback=initAutocomplete&key=AIzaSyBWSUc8EbZYVKF37jWVCb3lpBQwWqXUZw8"></script>';
    }
    if(!isset($dontincludeAPI)){
        echo '<script src="' . webroot("resources/assets/scripts/api2.js") . '"></script>';
    }
?>