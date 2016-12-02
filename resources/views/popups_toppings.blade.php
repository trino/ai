<STYLE>
    .addon-selected, .thisside {
        background: #daDADA;

    }

    .addon-selected::before, .currentitem.thisside::before {
        font-family: FontAwesome;
        content: "\f0da  ";
        border: 1px solid #dadada !important;
    }

</STYLE>

<SCRIPT>
    var oneclick = true, currentstyle = 1, currentbasecost = 0, currentaddoncost = 0;
    var currentaddontype = "", currentside = "", currentqualifier = "", addonname = "", hashalves = true;
    var currentaddonlist = new Array, currentitemindex = 0, currentitemname = "";

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////

    function toclassname(text) {
        return text.toLowerCase().replaceAll(" ", "_");
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////


    function generateaddons() {
        var HTML = '';
        var free = '!';

        switch (currentaddontype) {
            case "toppings":
                addonname = "toppings";
                break;
            case "wings_sauce":
                addonname = "sauces";
                break;
            default:
                addonname = "error: " + currentaddontype;
                break;
        }

        var thisside = ' CLASS="thisside" ><I CLASS="fa fa-check text-danger"></I>';


        for (var itemindex = 0; itemindex < currentaddonlist.length; itemindex++) {

            var freetoppings = 0;
            var paidtoppings = 0;

            var tempstr = '';
            var classname = 'itemcontents itemcontents' + itemindex;

            HTML += '<DIV style=" padding: 2px ;     border-radius: 5px;border:1px solid #dadada !important;" ' +
                ' ONCLICK="selectitem(event, ' + itemindex + ');"' +
                ' CLASS=" currentitem currentitem' + itemindex;

            if (currentitemindex == itemindex) {
                HTML += ' thisside';
            }

            HTML += '">' + currentitemname + ' #' + (itemindex + 1) + ' ';


            if (currentaddonlist[itemindex].length == 0) {
                tempstr += '<div class="btn-sm">No ' + addonname + '</div>';
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
                tempstr += '<DIV CLASS="btn btn-sm btn-secondary ' + classname + '">'
                    + currentaddon.name
                    + '<span CLASS="pull-right" '
                    + 'ONCLICK="removelistitem(' + itemindex + ', ' + i + ');">'
                    + '&nbsp; <i CLASS="fa fa-times"></i> </span></div>&nbsp;';


                if (!isaddon_free(currentaddontype, currentaddon.name)) {
                    qualifier = currentaddon.qual;
                    if (qualifier == 0) {
                        qualifier = 0.5;
                    } else if (currentaddon.side != 1) {
                        qualifier = qualifier * 0.5;
                    }
                    paidtoppings += qualifier;
                }

            }

            HTML += ucfirst(addonname)
                + '$'
                + paidtoppings
                + free
                + freetoppings
                + '<br>'
                + tempstr
                + '</DIV>';

        }


        $("#theaddons").html(HTML);
        $(".currentitem.thisside").trigger("click");
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////


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


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function list_addons(table, halves) {
        currentaddontype = table;
        var HTML = '<DIV style="height:170px;background: #fafafa;" class=""><DIV id="theaddons"></DIV></DIV>';
        if (currentstyle == 0) {
            HTML += '<DIV CLASS="bg-danger addonlist" ID="addontypes">';
        } else {
        }

        var types = Object.keys(alladdons[table]);

        if (currentstyle == 0) {
            $("#addonlist").html(HTML + '</DIV>');
        } else {
            var colors = ["secondary", "secondary", "secondary", "secondary", "secondary"];
            for (var i = 0; i < types.length; i++) {

                HTML += '<strong class="col-xs-12 btn-sm btn-secondary  " id="' + toclassname(types[i]) + '">' + types[i] + '</strong>';
                for (var i2 = 0; i2 < alladdons[currentaddontype][types[i]].length; i2++) {
                    var addon = alladdons[currentaddontype][types[i]][i2];
                    HTML += '<div style="" class="col-xs-6 col-md-3 btn-sm btn-secondary ' + colors[i] + ' addon-addon">' + addon + '</DIV>';
                }
            }
            $("#addonlist").html(HTML + '</SPAN>');
            $(".addon-addon").click(
                function () {
                    list_addon_addon(event);
                }
            );
        }
        $(".addon-type").click(
            function () {
                list_addon_type(event);
            }
        );
        hashalves = halves;
        generateaddons();
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////


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
        //$("#addonlist").append(HTML + '</DIV></DIV>');
        $(e.target).after(HTML + '</DIV>');
        $(".addon-addon").click(
            function () {
                list_addon_addon(event);
            }
        );
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////


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


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////


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


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////


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


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////


    function addtoitem() {
        if (!hashalves) {
            currentside = 1;
        }
        currentaddonlist[currentitemindex].push({
            name: addonname,
            side: currentside,
            qual: currentqualifier,
            type: currentaddontype
        });

        if (!oneclick) {
            $(".addon-selected").removeClass("addon-selected");
            $("#addonall").remove();
            $("#addonedit").remove();
        }
        generateaddons();
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////


    function selectitem(e, index) {
        $(".currentitem").removeClass("thisside");
        $(".currentitem" + index).addClass("thisside");
        /*
         $(".itemcontents").hide();
         $(".itemcontents" + index).show();
         */
        currentitemindex = index;
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////


    function removelistitem(index, subindex) {
        if (isUndefined(subindex)) {
            removeindex(currentaddonlist, index);
        } else {
            removeindex(currentaddonlist[index], subindex);
        }
        generateaddons();
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////


    function ucfirst(text) {
        return text.left(1).toUpperCase() + text.right(text.length - 1);
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////


</SCRIPT>

