var order = new Array;
var surcharge = 3.50;
var lastquantity = 0;

function orderitem(element) {
    var ID = element.getAttribute("value");
    var item = {id: ID, name: element.getAttribute("itemname"), price: element.getAttribute("price"), typeid: element.getAttribute("typeid"), type: element.getAttribute("type"), quantity: 1};
    if(element.hasAttribute("quantity")){item.quantity = element.getAttribute("quantity");}
    for(var i=0; i < tables.length; i++){
        item[tables[i]] = new Array();
        for(var v = 0; v < element.getAttribute(tables[i]); v++){
            item[tables[i]].push("");
        }
    }

    var items = element.getAttribute("itemcount");
    for(var i=0; i < items; i++){
        var addons = assimilateaddons(ID, element, i);
        if(lastquantity > 1){item.quantity = lastquantity;}
        for(var v=0; v < tables.length; v++) {
            if(item[tables[v]].length > i){
                item[tables[v]][i] = filteraddons(addons, tables[v]);
            }
        }
    }

    if(items == 1){
        for(var v=0; v < tables.length; v++) {
            for(var i=1; i < item[tables[v]].length; i++){
                item[tables[v]][i] = cloneData( item[tables[v]][0] );
            }
        }
    }

    order.push(item);
    generatereceipt();
}

function filteraddons(addons, tablename){
    var ret = new Array();
    for(var i=0;i<addons.length;i++){
        if(tablename.isEqual(addons[i].tablename)){
            ret.push( addons[i] );
        }
    }
    return ret;
}

var assimilate_enabled = true;
function assimilateaddons(ID, element, Index){
    //[0=startsearchstring, 1=searchstring, 2=toppings, 3=typos, 4=defaults, 5=quantity, 6=itemname]
    var defaults = true;
    if(isUndefined(Index)){
        var defaults = false;
        var toppings = assimilate(ID, element);
    } else {
        var toppings = assimilate(ID, element.getAttribute("item" + Index));
    }
    lastquantity = toppings[5];
    if(defaults){return toppings[2].concat( toppings[3] ).concat( toppings[4] );}
    return toppings[2].concat( toppings[3] );
}

function makerow(Label, Price, Extra, newcol){
    if(isUndefined(Extra)){Extra = "";}
    if(isUndefined(newcol)){newcol = "";}
    if(newcol) {newcol = '<TD COLSPAN="2" ROWSPAN="4" ALIGN="CENTER"><BUTTON ONCLICK="clearorder();">Clear</BUTTON><P><BUTTON>Checkout button goes here</BUTTON></TD>'}
    return '<TR><TD COLSPAN="2">' + Extra + '</TD><TD>' + Label + '</TD><TD ALIGN="right"><SPAN STYLE="float:left;">$</SPAN>' + Price.toFixed(2) + '</TD>' + newcol + '</TR>';
}

function clearorder(){
    if(confirm("Are you sure you want to erase your entire order?")) {
        order = new Array;
        generatereceipt();
    }
}

function generatereceipt(index){
    var text, subtotal = 0, items = 0;
    if(isUndefined(index)){//do entire order
        if(order.length == 0){
            text = "Your order is empty";
        } else {
            text =  '<BUTTON ID="saveitems" STYLE="float:right;display:none;width:100px;height:100px;" ONCLICK="saveitem();">Save</BUTTON>' +
                    '<TABLE BORDER="1"><TR><TH>Index</TH><TH>Item</TH><TH>QTY</TH><TH>Price</TH><TH>Items</TH><TH>Actions</TH></TR>';
            for(var i=0; i < order.length; i++){
                var item = order[i];
                text += generatereceipt(i);
                subtotal += Number(item.price) * Number(item.quantity);
                items += Number(item.quantity);
            }

            text += makerow("Subtotal", subtotal, items + pluralize(" item", items), true);
            subtotal += surcharge;
            text += makerow("Surcharge", surcharge, "THIS IS AN EXAMPLE");
            text += makerow("Tax (13%)", subtotal*0.13) + makerow("Total", subtotal*1.13) + '</TABLE>';
        }
        innerHTML("#receipt", text);
    } else {//return 1 item
        var item = order[index];
        var tableterm = "123TABLE123";
        text = '<TR><TD CLASS="item' + item.id + '">' + index + '</TD><TD>' + item.name + '</TD><TD>' +
                '<BUTTON CLASS="minus" ONCLICK="itemdir(' + index + ', -1);">-</BUTTON><SPAN STYLE="float:right;">' + item.quantity + '<BUTTON CLASS="plus" ONCLICK="itemdir(' + index + ', 1);">+</BUTTON></SPAN></TD><TD ALIGN="right"><SPAN STYLE="float:left;">$</SPAN>' + item.price;
        if(item.quantity > 1){text += 'x' + item.quantity + '<HR>(' + Number(item.price * item.quantity).toFixed(2) + ')';}
        text += '</TD><TD>' + tableterm;
        var doit = false;
        for(var i=0; i < tables.length; i++){
            for(var v=0; v < item[tables[i]].length; v++){
                doit = true;
                var addons = stringifyaddons(item[tables[i]][v]);
                if(!addons){addons = "<B>NO ADD-ONS SELECTED</B>";}
                text += '<TR><TD>' + (v+1) + '</TD><TD>' + addons + '</TD><TD CLASS="tdbtn">' +
                        '<BUTTON ONCLICK="edititem(this);" STYLE="width: 100%; height: 100%;" itemindex="' + index + '" type="' + tables[i] + '" addonindex="' + i + '">Edit</BUTTON></TD></TR>';
            }
        }
        if(doit){
            text = text.replaceAll(tableterm, '<TABLE BORDER="1" WIDTH="100%"><TR><TH WIDTH="5%">#</TH><TH>Add-ons</TH><TH WIDTH="10%">Actions</TH></TR>') + '</TABLE>';
        } else {
            text = text.replaceAll(tableterm, "");
        }
        text += '</TD><TD CLASS="tdbtn"><BUTTON ONCLICK="deleteitem(' + index + ');" STYLE="height:100%">Delete</BUTTON></TD></TR>';
    }
    return text;
}

function pluralize(text, qty, append){
    if(isUndefined(append)){append="s";}
    if(qty==1){return text;}
    return text + append;
}

function itemdir(index, value){
    var item = order[index];
    item.quantity = item.quantity + value;
    if(item.quantity == 0){
        deleteitem(index);
    }
    generatereceipt();
}

function stringifyaddons(addons){
    var text = "";
    for(var i=0; i<addons.length;i++){
        if(i > 0){text += ",  ";}
        text += '<I TITLE="' + JSON.stringify(addons[i]).replaceAll('"', "'") + '">' + addons[i].qualifier + "</I>  " + addons[i].label;
    }
    return text;
}

function deleteitem(index){
    removeindex(order, index, 1);
    generatereceipt();
}

var selecteditem;
function edititem(element){
    show("#saveitems");
    clearaddons();

    var table = element.getAttribute("type");
    selecteditem = {itemindex: element.getAttribute("itemindex"), type: table, addonindex: element.getAttribute("addonindex")};
    show(".addons-" + table);

    var item = order[selecteditem.itemindex];
    var addons = item[selecteditem.type][selecteditem.addonindex];
    for(var i=0; i< addons.length; i++){
        qualifytopping(table, addons[i].qualifier, addons[i].label);
    }
}

function saveitem(ID, element, index){
    hide("#saveitems");
    var addons = getaddonslikeassimilate(selecteditem.type);
    order[ selecteditem.itemindex].quantity = quantityselect; //value(".quantityselect");
    order[ selecteditem.itemindex ][ selecteditem.type ][ selecteditem.addonindex ] = addons;
    generatereceipt();
}

function getaddonslikeassimilate(table){
    var addons = getaddons(table);
    var ret = new Array;
    //0=qualifiers, 1=ids, 2=names, 3=table name
    for(var i = 0; i < addons[0].length; i++){
        ret.push({qualifier: addons[0][i], label: addons[2][i]});
    }
    return ret;
}

//the body can only be manipulated after the page has loaded
doonload(function(){
    if(select("#receipt").length == 0) {append("body", '<DIV ID="receipt" CLASS="red"></DIV>');}
    generatereceipt();
    //trigger(".order0", "click");
});