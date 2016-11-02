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
</STYLE>
<DIV ID="addonlist" class="addonlist"></DIV>

<SCRIPT>
    var currentaddontype = "";
    var hashalves = true;
    var currentside = "", currentqualifier = "", addonname = "";

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
        if(classname == "addon-qualifier" && index == 0){index = "0.5";}
        $("." + classname).removeClass("addon-selected");
        $(e.target).addClass("addon-selected");
        switch(classname){
            case "addon-qualifier": currentqualifier = index; break;
            case "addon-side": currentside = index; break;
        }
        log(classname + "." +  listitemname + "=" + index);
    }

    function addtoitem(){
        log(addonname + " Side: " + currentside + " Qualifier: " + currentqualifier);
    }

    list_addons("toppings", true);
</SCRIPT>