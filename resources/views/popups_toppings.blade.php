<STYLE>
    .addon-selected{
        border: 1px solid black;
        background-color: lightblue;
    }

    .addon-selected::before, .currentitem.thisside::before {
        font-family: FontAwesome;
        content: "\f0da  ";
    }

    .addon-type{
        cursor: pointer;
    }

    .addon-title{
        border: 1px solid black;
        text-align: center;
        font-weight: bold;
        cursor: pointer;
    }

    .thisside{
        background-color: lightblue;
    }

    #addonall{
        padding-left: 15px;
    }

    .free{
        background: url('<?= webroot("resources/views"); ?>/circle.gif') no-repeat 0px 1px;
        padding-left: 4px;
        padding-right: 4px;
    }

    .btn-mini {
        padding: 1px 3px;
        font-size: 11px;
        line-height: 13px;
        margin: 1px !important;
    }

    article.scrollspy-example {
        position: relative;
        overflow-y: scroll;
        overflow-x: hidden;
        height: 200px;
    }

    .addon-addon{
        margin-right: 4px !important;
    }

    .btn-round{
        -webkit-border-radius: 4px !important;
        -moz-border-radius: 4px !important;
        border-radius: 4px !important;
    }

    .fa-strong{
        font-weight: bold;
    }
</STYLE>

<div class="row">
    <DIV ID="addonlist" class="addonlist"></DIV>
</div>

<SCRIPT>
    var oneclick = true, currentstyle = 1;
    var currentaddontype = "", currentside = "", currentqualifier = "", addonname = "", hashalves = true;
    var currentaddonlist = new Array, currentitemindex = 0, currentitemname = "";

    function toclassname(text){
        return text.toLowerCase().replaceAll(" ", "_");
    }

    function list_addons_quantity(quantity, tablename, halves, name){
        currentaddonlist = new Array();
        currentitemindex=0;
        for(var i=0; i<quantity; i++){
            currentaddonlist.push([]);
        }
        currentitemname=name;
        list_addons(tablename, halves);
    }

    function list_addons(table, halves){
        currentaddontype=table;
        var HTML = '<DIV CLASS="col-md-12"><DIV id="theaddons"></DIV></DIV>';
        if(currentstyle == 0){
            HTML += '<DIV CLASS="col-md-12 addonlist" ID="addontypes">';
        } else {
            HTML += '<SPAN CLASS="col-md-12"><nav id="scrollspy-nav" class="navbar" style="padding: .5rem 0 !important;"><ul class="nav nav-pills">';
        }
        var types = Object.keys(alladdons[table]);
        for(var i=0;i<types.length;i++){
            var type =  types[i];
            if(currentstyle == 0){
                HTML += '<DIV CLASS="addon-type">' + type + '</DIV>';
            } else {
                HTML += '<li class="nav-item"><a class="nav-link" href="#' + toclassname(type) + '">' + type + '</a></li></DIV>';
            }
        }
        if(currentstyle == 0){
            $("#addonlist").html(HTML + '</DIV>');
        } else {
            HTML += '</ul></nav><article data-spy="scroll" data-target="#scrollspy-nav" data-offset="0" class="scrollspy-example">';
            var colors = ["info", "danger", "success", "secondary", "primary"];
            for(var i=0;i<types.length;i++) {
                var type = types[i];
                HTML += '<h4 id="' + toclassname(type) + '">' + type + '</h4><p>';
                for(var i2=0; i2< alladdons[currentaddontype][type].length; i2++){
                    var addon = alladdons[currentaddontype][type][i2];
                    HTML += '<div class="btn btn-' + colors[i] + ' btn-sm cursor-pointer addon-addon"> ' + addon + '</DIV>';
                }
                HTML += '</P>';
            }
            $("#addonlist").html(HTML + '</article></SPAN>');
            $(".addon-addon").click(
                function(){list_addon_addon(event);}
            );
        }
        $(".addon-type").click(
            function(){list_addon_type(event);}
        );
        hashalves = halves;
        generateaddons();
    }

    function list_addon_type(e){
        $(".addon-type").removeClass("addon-selected");
        $(e.target).addClass("addon-selected");
        $("#addonall").remove();
        $("#addonedit").remove();
        var HTML = '<DIV ID="addonall">';
        var addontype = $(e.target).text();
        for(var i=0; i< alladdons[currentaddontype][addontype].length; i++){
            var addon = alladdons[currentaddontype][addontype][i];
            HTML += '<DIV class="cursor-pointer addon-addon">' + addon + '</DIV>';
        }
        //$("#addonlist").append(HTML + '</DIV></DIV>');
        $(e.target).after(HTML + '</DIV>');
        $(".addon-addon").click(
            function(){list_addon_addon(event);}
        );
    }

    //alladdons, freetoppings, qualifiers, isaddon_free, isaddon_onall
    function list_addon_addon(e){
        addonname = $(e.target).text();
        if(oneclick){
            currentqualifier=1;
            return addtoitem();
        }
        $(".addon-addon").removeClass("addon-selected");
        $(e.target).addClass("addon-selected");
        $("#addonedit").remove();
        var HTML = '<DIV ID="addonedit">';
        if(isaddon_free(currentaddontype, addonname)){
            HTML += '<DIV>This is a free addon</DIV>';
        }

        if(hashalves) {
            if (isaddon_onall(currentaddontype, addonname)) {
                HTML += '<DIV>This addon goes on the whole item</DIV>';
                currentside = 1;
            } else {
                HTML += makelist("Side", "addon-side", ["Left", "Whole", "Right"], 1);
            }
        }

        if( qualifiers[currentaddontype].hasOwnProperty(addonname) ) {
            HTML += makelist("Qualifier", "addon-qualifier", qualifiers[currentaddontype][addonname], 1);
        } else {
            HTML += makelist("Qualifier", "addon-qualifier", qualifiers["DEFAULT"], 1);
        }

        HTML += '<DIV CLASS="col-md-12" style="margin: 15px;" align="CENTER"><BUTTON ONCLICK="addtoitem();" CLASS="form-control btn btn-primary">Add to item</BUTTON></DIV>';
        $(e.target).after(HTML + '</DIV>');
    }

    function makelist(Title, classname, data, defaultindex){
        var HTML = '<DIV><DIV CLASS="col-md-12">' + Title + ':</DIV>';
        var columns = 12 / data.length;
        var selected;
        for(var i = 0; i<data.length; i++){
            selected = "";
            if(i == defaultindex){selected = " addon-selected";}
            HTML += '<DIV CLASS="cursor-pointer addon-list col-md-' + columns + ' ' + classname + selected + '" ONCLICK="list_addon_list(event, ' + "'" + classname + "', " + i + ');">' + data[i] + '</DIV>';
        }
        switch(classname){
            case "addon-qualifier": currentqualifier = defaultindex; break;
            case "addon-side": currentside = defaultindex; break;
        }
        return HTML + '</DIV>';
    }

    function list_addon_list(e, classname, index){
        var listitemname = $(e.target).text();
        //if(classname == "addon-qualifier" && index == 0){index = "0.5";}
        $("." + classname).removeClass("addon-selected");
        $(e.target).addClass("addon-selected");
        switch(classname){
            case "addon-qualifier": currentqualifier = index; break;
            case "addon-side": currentside = index; break;
        }
        log(classname + "." +  listitemname + "=" + index);
    }

    function addtoitem(){
        if(!hashalves){currentside=1;}
        currentaddonlist[currentitemindex].push({
            name: addonname,
            side: currentside,
            qual: currentqualifier,
            type: currentaddontype
        });

        if(!oneclick) {
            $(".addon-selected").removeClass("addon-selected");
            $("#addonall").remove();
            $("#addonedit").remove();
        }
        generateaddons();
    }

    function generateaddons(){
        var HTML = '<DIV CLASS="col-md-12">';
        var free = ' <SPAN class="free" TITLE="Free addons">$</SPAN> ';
        var columns = 3, addonname = "";
        if(hashalves && currentstyle == 0){
            //HTML += '<TH WIDTH="7%">L</TH><TH WIDTH="7%">R</TH>';
            columns=4;
        }
        switch(currentaddontype){
            case "toppings":    addonname = "toppings";         break;
            case "wings_sauce": addonname = "sauces";           break;
            default: addonname = "error: " + currentaddontype;  break;
        }
        
        var thisside = ' CLASS="thisside" ALIGN="CENTER"><I CLASS="fa fa-check"></I></DIV>';

        for(var itemindex=0; itemindex<currentaddonlist.length; itemindex++){
            var freetoppings = 0;
            var paidtoppings = 0;
            HTML += '<DIV ONCLICK="selectitem(event, ' + itemindex + ');" CLASS="col-md-12 currentitem cursor-pointer currentitem' + itemindex;
            if(currentitemindex == itemindex) {HTML += ' thisside';}
            HTML += '">' + currentitemname + ' #: ' + (itemindex+1);//<TD COLSPAN="' + columns + '">
            var classname = 'itemcontents itemcontents' + itemindex;
            var tempstr = '';
            if(currentaddonlist[itemindex].length==0){
                tempstr = '<DIV CLASS="col-md-12 ' + classname + '">No ' + addonname + '</DIV>';
            }
            for(var i=0; i<currentaddonlist[itemindex].length; i++){
                var currentaddon = currentaddonlist[itemindex][i], qualifier = "";
                if( qualifiers[currentaddontype].hasOwnProperty(addonname) ) {
                    qualifier = qualifiers[currentaddontype][addonname][currentaddon.qual];
                } else {
                    qualifier = qualifiers["DEFAULT"][currentaddon.qual];
                }
                tempstr += '<DIV CLASS="col-md-12 ' + classname + '">' + currentaddon.name;

                /*
                if(hashalves) {
                    tempstr += '<DIV CLASS="col-md-12 ' + classname + '"><TD>' + qualifier + '</TD><TD>' + currentaddon.name + '</TD>';
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
                */

                tempstr += '<DIV CLASS="pull-right"><BUTTON CLASS="btn btn-mini btn-round btn-danger" ONCLICK="removelistitem(' + itemindex + ', ' + i + ');"><I CLASS="fa fa-times"></I></BUTTON></DIV></DIV>';
                if(!isaddon_free(currentaddontype, currentaddon.name)){
                    qualifier = currentaddon.qual;
                    if(qualifier == 0){
                        qualifier = 0.5;
                    } else if(currentaddon.side != 1) {
                        qualifier = qualifier * 0.5;
                    }
                    paidtoppings += qualifier;
                }
            }

            HTML += '<SPAN CLASS="pull-right">' + ucfirst(addonname) + ' $ ' + paidtoppings + free + freetoppings + '</SPAN></TD></DIV>' + tempstr;
        }
        $("#theaddons").html(HTML + '</DIV>');
        $(".currentitem.thisside").trigger("click");
    }

    function selectitem(e, index){
        $(".currentitem").removeClass("thisside");
        $(".currentitem" + index).addClass("thisside");
        $(".itemcontents").hide();
        $(".itemcontents" + index).show();
        currentitemindex = index;
    }
    function removelistitem(index, subindex){
        if(isUndefined(subindex)) {
            removeindex(currentaddonlist, index);
        } else {
            removeindex(currentaddonlist[index], subindex);
        }
        generateaddons();
    }

    function ucfirst(text){
        return text.left(1).toUpperCase() + text.right(text.length-1);
    }

    list_addons_quantity(3, "toppings", false, "Pizza");
</SCRIPT>