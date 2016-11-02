<STYLE>
    #addonlist{
        border-style: solid;
    }

    .addonlist{
        height: 250px;
    }

    .overflow-y{
        overflow-y: auto;
    }

    .cursor-pointer{
        cursor: pointer;
    }

    .addon-selected{
        border: 1px solid black;
        background-color: lightblue;
    }

    .addon-title{
        border: 1px solid black;
        text-align: center;
        font-weight: bold;
    }

    .addon-list{
        text-align: center;
    }

    .addon-button{
        position: absolute;
        bottom: 6px;
    }

    .thisside{
        background-color: lightblue;
    }
</STYLE>
<DIV ID="addonlist" class="addonlist"></DIV>

<SCRIPT>
    var currentaddontype = "", currentside = "", currentqualifier = "", addonname = "", hashalves = true;
    var currentaddonlist = new Array;

    function toclassname(text){
        return text.toLowerCase().replaceAll(" ", "_");
    }

    function list_addons(table, halves){
        currentaddontype=table;
        var HTML = '<DIV CLASS="col-md-3 overflow-y">Your current addons: <DIV id="theaddons"></DIV></DIV><DIV CLASS="col-md-3 addonlist overflow-y" ID="addontypes">';
        var types = Object.keys(alladdons[table]);
        for(var i=0;i<types.length;i++){
            var type =  types[i];
            HTML += '<DIV CLASS="cursor-pointer addon-type">' + type + '</DIV>';
        }
        $("#addonlist").html(HTML + '</DIV>');
        $(".addon-type").click(
            function(){list_addon_type(event);}
        );
        hashalves = halves;
    }

    function list_addon_type(e){
        $(".addon-type").removeClass("addon-selected");
        $(e.target).addClass("addon-selected");
        $("#addonall").remove();
        $("#addonedit").remove();
        var HTML = '<DIV CLASS="col-md-3 addonlist overflow-y" ID="addonall">';
        var addontype = $(e.target).text();
        for(var i=0; i< alladdons[currentaddontype][addontype].length; i++){
            var addon = alladdons[currentaddontype][addontype][i];
            HTML += '<DIV class="cursor-pointer addon-addon">' + addon + '</DIV>';
        }
        $("#addonlist").append(HTML + '</DIV>');
        $(".addon-addon").click(
            function(){list_addon_addon(event);}
        );
    }

    //alladdons, freetoppings, qualifiers, isaddon_free, isaddon_onall
    function list_addon_addon(e){
        addonname = $(e.target).text();
        $(".addon-addon").removeClass("addon-selected");
        $(e.target).addClass("addon-selected");
        $("#addonedit").remove();
        var HTML = '<DIV CLASS="col-md-3 addonlist overflow-y" ID="addonedit">';
        HTML += '<DIV CLASS="addon-title">' + addonname + '</DIV>';
        if(isaddon_free(currentaddontype, addonname)){
            HTML += '<DIV>This is a free addon</DIV>';
        }

        if(isaddon_onall(currentaddontype, addonname) || !hashalves) {
            HTML += '<DIV>This addon goes on the whole item</DIV>';
            currentside=1;
        } else {
            HTML += makelist("Side", "addon-side", ["Left", "Whole", "Right"], 1);
        }

        if( qualifiers[currentaddontype].hasOwnProperty(addonname) ) {
            HTML += makelist("Qualifier", "addon-qualifier", qualifiers[currentaddontype][addonname], 1);
        } else {
            HTML += makelist("Qualifier", "addon-qualifier", qualifiers["DEFAULT"], 1);
        }

        HTML += '<DIV CLASS="col-md-12 addon-button"><BUTTON ONCLICK="addtoitem();" CLASS="form-control btn btn-primary">Add to item</BUTTON>';

        $("#addonlist").append(HTML + '</DIV>');
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
        currentaddonlist.push({
            name: addonname,
            side: currentside,
            qual: currentqualifier,
            type: currentaddontype
        });
        $(".addon-selected").removeClass("addon-selected");
        $("#addonall").remove();
        $("#addonedit").remove();
        generateaddons();
    }

    function generateaddons(){
        var HTML = '<TABLE CELLPADDING="2" BORDER="1" WIDTH="100%"><TR><TH>Q</TH><TH>Name</TH><TH WIDTH="7%">L</TH><TH WIDTH="7%">R</TH><TD WIDTH="7%" ALIGN="CENTER"><B><i class="fa fa-trash-o"></i></B></TD></TR>';
        var thisside = ' CLASS="thisside" ALIGN="CENTER"><I CLASS="fa fa-check"></I></TD>';
        var freetoppings = 0;
        var paidtoppings = 0;
        for(var i=0; i<currentaddonlist.length; i++){
            var currentaddon = currentaddonlist[i], qualifier = "";
            if( qualifiers[currentaddontype].hasOwnProperty(addonname) ) {
                qualifier = qualifiers[currentaddontype][addonname][currentaddon.qual];
            } else {
                qualifier = qualifiers["DEFAULT"][currentaddon.qual];
            }
            HTML += '<TR><TD>' + qualifier + '</TD><TD>' + currentaddon.name + '</TD>';
            switch(currentaddon.side){
                case 0://left
                    HTML += '<TD' + thisside + '<TD></TD>';
                    break;
                case 1://all
                    HTML += '<TD COLSPAN="2"' + thisside;
                    break;
                case 2://right
                    HTML += '<TD></TD><TD' + thisside;
                    break;
            }
            HTML += '<TD><BUTTON CLASS="btn btn-sm btn-danger" ONCLICK="removelistitem(' + i + ');"><I CLASS="fa fa-times"></I></BUTTON></TD></TR>';
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

        $("#theaddons").html(HTML + '<TR><TD COLSPAN="2">Paid toppings:</TD><TD COLSPAN="3" ALIGN="RIGHT">' + paidtoppings + '</TD></TR></TABLE>');
    }

    function removelistitem(index){
        removeindex(currentaddonlist, index);
        generateaddons();
    }

    list_addons("toppings", true);
</SCRIPT>