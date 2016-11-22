<DIV ID="addonlist"></DIV>
<div class="clearfix"></div>

<style scoped="true">
    article.scrollspy-example {
        position: relative;
        overflow: scroll;
        height: 200px;
    }
</style>
<br><br>
<h6>design topping selector like below</h6>
<nav id="scrollspy-nav" class="navbar " style="padding: .5rem 0 !important;">
    <ul class="nav nav-pills">
        <li class="nav-item"><a class="nav-link" href="#bast">Meat</a></li>
        <li class="nav-item"><a class="nav-link" href="#brogan">Vegetables</a></li>
        <li class="nav-item"><a class="nav-link" href="#calceology">Preperation</a></li>
    </ul>
</nav>


<article data-spy="scroll" data-target="#scrollspy-nav" data-offset="0" class="scrollspy-example">
    <h4 id="bast">Meat</h4>
    <p>
    <div class="btn btn-danger btn-sm"> Anchovies</div>
    <div class="btn btn-danger btn-sm"> Bacon</div>
    <div class="btn btn-danger btn-sm"> Beef Salami</div>
    <div class="btn btn-danger btn-sm"> Chicken</div>
    <div class="btn btn-danger btn-sm"> Ground Beef</div>
    <div class="btn btn-danger btn-sm"> Ham</div>
    <div class="btn btn-danger btn-sm"> Hot Italian Sausage</div>
    <div class="btn btn-danger btn-sm"> Hot Sausage</div>
    <div class="btn btn-danger btn-sm"> Italian Sausage</div>
    <div class="btn btn-danger btn-sm"> Mild Sausage</div>
    <div class="btn btn-danger btn-sm"> Pepperoni</div>
    <div class="btn btn-danger btn-sm"> Salami</div>
    </p>
    <h4 id="brogan">Vegetables</h4>
    <p>
    <div class="btn btn-success btn-sm"> Artichoke Heart</div>
    <div class="btn btn-success btn-sm"> Black Olives</div>
    <div class="btn btn-success btn-sm"> Broccoli</div>
    <div class="btn btn-success btn-sm"> Fresh Mushroom</div>
    <div class="btn btn-success btn-sm"> Green Olives</div>
    <div class="btn btn-success btn-sm"> Green Peppers</div>
    <div class="btn btn-success btn-sm"> Hot Banana Peppers</div>
    <div class="btn btn-success btn-sm"> Hot Peppers</div>
    <div class="btn btn-success btn-sm"> Jalapeno Peppers</div>
    <div class="btn btn-success btn-sm"> Mushrooms</div>
    <div class="btn btn-success btn-sm"> Onions</div>
    <div class="btn btn-success btn-sm"> Pineapple</div>
    <div class="btn btn-success btn-sm"> Red Onions</div>
    <div class="btn btn-success btn-sm"> Red Peppers</div>
    <div class="btn btn-success btn-sm"> Spinach</div>
    <div class="btn btn-success btn-sm"> Sundried Tomatoes</div>
    <div class="btn btn-success btn-sm"> Tomatoes</div>

    </p>
    <h4 id="calceology">Preperation</h4>
    <p>
    <div class="btn btn-secondary btn-sm"> Cooked</div>
    <div class="btn btn-secondary btn-sm"> Tomato Sauce</div>
    <div class="btn btn-secondary btn-sm"> Cooked</div>
    <div class="btn btn-secondary btn-sm"> Tomato Sauce</div>
    <div class="btn btn-secondary btn-sm"> Cooked</div>
    <div class="btn btn-secondary btn-sm"> Tomato Sauce</div>
    <div class="btn btn-secondary btn-sm"> Cooked</div>
    <div class="btn btn-secondary btn-sm"> Tomato Sauce</div>
    <div class="btn btn-secondary btn-sm"> Cooked</div>
    <div class="btn btn-secondary btn-sm"> Tomato Sauce</div>
    <div class="btn btn-secondary btn-sm"> Cooked</div>
    <div class="btn btn-secondary btn-sm"> Tomato Sauce</div>
    <div class="btn btn-secondary btn-sm"> Tomato Sauce</div>
    <div class="btn btn-secondary btn-sm"> Tomato Sauce</div>
    <div class="btn btn-secondary btn-sm"> Tomato Sauce</div>
    <div class="btn btn-secondary btn-sm"> Tomato Sauce</div>
    <div class="btn btn-secondary btn-sm"> Tomato Sauce</div>
    <div class="btn btn-secondary btn-sm"> Tomato Sauce</div>


</article>


<SCRIPT>
    var oneclick = true;
    var currentaddontype = "", currentside = "", currentqualifier = "", addonname = "", hashalves = true;
    var currentaddonlist = new Array, currentitemindex = 0, currentitemname = "";

    function list_addons(table, halves) {
        currentaddontype = table;
        var HTML = '<DIV id="theaddons"></DIV><DIV CLASS="addonlist" ID="addontypes">';
        var types = Object.keys(alladdons[table]);
        for (var i = 0; i < types.length; i++) {
            var type = types[i];
            HTML += '<DIV CLASS="addon-type bg-success">' + type + '</DIV>';
        }
        $("#addonlist").html(HTML + '</DIV>');
        $(".addon-type").click(
                function () {
                    list_addon_type(event);
                }
        );
        hashalves = halves;
        generateaddons();
    }

    function toclassname(text) {
        return text.toLowerCase().replaceAll(" ", "_");
    }

    function list_addons_quantity(quantity, tablename, halves, name) {
        currentaddonlist = new Array();
        currentitemindex = 0;
        for (var i = 0; i < quantity; i++) {
            currentaddonlist.push([]);
        }
        currentitemname = name;
        list_addons(tablename, halves);
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
            HTML += '<DIV class="bg-warning addon-addon">' + addon + '</DIV>';
        }

        $(e.target).after(HTML + '</DIV>');
        $(".addon-addon").click(
                function () {
                    list_addon_addon(event);
                }
        );
    }

    //alladdons, freetoppings, qualifiers, isaddon_free, isaddon_onall
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

        HTML += '<DIV CLASS="col-md-12" style="margin: 15px;" align="CENTER"><BUTTON ONCLICK="addtoitem();" CLASS="form-control btn btn-primary">Add to item</BUTTON></DIV>';
        $(e.target).after(HTML + '</DIV>');
    }

    function makelist(Title, classname, data, defaultindex) {
        var HTML = '<DIV><DIV CLASS="col-md-12">' + Title + ':</DIV>';
        var columns = 12 / data.length;
        var selected;
        for (var i = 0; i < data.length; i++) {
            selected = "";
            if (i == defaultindex) {
                selected = " addon-selected";
            }
            HTML += '<DIV CLASS="col-md-' + columns + ' ' + classname + selected + '" ONCLICK="list_addon_list(event, ' + "'" + classname + "', " + i + ');">' + data[i] + '</DIV>';
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

    function generateaddons() {
        var HTML = '<TABLE class="">';
        var free = ' <SPAN class="free" TITLE="Free addons">$</SPAN> ';
        var columns = 3, addonname = "";
        if (hashalves) {
            HTML += '<TH WIDTH="7%">L</TH><TH WIDTH="7%">R</TH>';
            columns = 4;
        }
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
        HTML += '</TR>';
        var thisside = '><I CLASS="fa fa-check"></I></TD>';
        for (var itemindex = 0; itemindex < currentaddonlist.length; itemindex++) {
            var freetoppings = 0;
            var paidtoppings = 0;
            HTML += '<TR ONCLICK="selectitem(event, ' + itemindex + ');" CLASS="currentitem rcurrentitem' + itemindex;
            if (currentitemindex == itemindex) {
                HTML += ' thisside';
            }
            HTML += '"><TD COLSPAN="' + columns + '">' + currentitemname + ' #: ' + (itemindex + 1);
            var classname = 'itemcontents itemcontents' + itemindex;
            var tempstr = '';
            if (currentaddonlist[itemindex].length == 0) {
                tempstr = '<TR CLASS="' + classname + '"><TD COLSPAN="5">No ' + addonname + '</TD></TR>';
            }
            for (var i = 0; i < currentaddonlist[itemindex].length; i++) {
                var currentaddon = currentaddonlist[itemindex][i], qualifier = "";
                if (qualifiers[currentaddontype].hasOwnProperty(addonname)) {
                    qualifier = qualifiers[currentaddontype][addonname][currentaddon.qual];
                } else {
                    qualifier = qualifiers["DEFAULT"][currentaddon.qual];
                }
                tempstr += '<TR CLASS="' + classname + '"><TD>' + qualifier + '</TD><TD>' + currentaddon.name + '</TD>';
                if (hashalves) {
                    switch (currentaddon.side) {
                        case 0://left
                            tempstr += '<TD' + thisside + '<TD></TD>';
                            break;
                        case 1://all
                            tempstr += '<TD COLSPAN="2"' + thisside;
                            break;
                        case 2://right
                            tempstr += '<TD></TD><TD' + thisside;
                            break;
                    }
                }
                tempstr += '<TD><BUTTON CLASS="btn btn-mini btn-danger" ONCLICK="removelistitem(' + itemindex + ', ' + i + ');"><I CLASS="fa fa-times"></I></BUTTON></TD></TR>';
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

            HTML += '<SPAN CLASS="pull-right">' + ucfirst(addonname) + ' $ ' + paidtoppings + free + freetoppings + '</SPAN></TD></TR>' + tempstr;
        }
        $("#theaddons").html(HTML + '</TABLE>');
        $(".currentitem.thisside").trigger("click");
    }

    function selectitem(e, index) {
        $(".currentitem").removeClass("thisside");
        $(".currentitem" + index).addClass("thisside");
        $(".itemcontents").hide();
        $(".itemcontents" + index).show();
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

    list_addons_quantity(3, "toppings", false, "Pizza");

</SCRIPT>