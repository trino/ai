//natural user interface

var wordstoignore = ["the", "with", "and"];
var synonyms = [//multi-dimensional array of multi-word terms, the first term is the primary terms, followed by the secondary terms
    ["jalapenos", "jalapeno", "jalapeño", "jalapeños", "jalape?o"],
    ["green peppers"],
    ["red peppers"],
    ["black olives", "kalamata olives"],
    ["sun dried tomatoes", "sun dried tomatoes", "sundried tomatoes", "sun dried tomatos", "sun dried tomatos", "sundried tomatos"],
    ["tomatoes", "tomatos"],
    ["pepperoni", "pepperonis"],
    ["red onions"],
    ["extra large", "x-large"],
    ["anchovies", "anchovy"],

    ["2", "two"]
];

function findlabel(element){
    var label = $("label[for='"+element.attr('id')+"']");
    if (label.length == 0) {
        label = element.closest('label')
    }
    return label;
}

function replaceAll(Source, Find, ReplaceWith){
    Find = Find.replaceAll("[?]", "[?]");
    return Source.replaceAll(Find, ReplaceWith);
}

function replacesynonyms(searchstring){
    //replace synonyms with the first term to normalize the search
    searchstring = searchstring.trim().toLowerCase().replaceAll("-", " ");
    var searchstring2 = "";
    var temp = -1;
    for(var synonymparentindex = 0; synonymparentindex< synonyms.length; synonymparentindex++){
        for(var synonymchildindex = 0; synonymchildindex < synonyms[synonymparentindex].length; synonymchildindex++){
            temp = searchstring.indexOf(synonyms[synonymparentindex][synonymchildindex]);
            if(temp  > -1){
                searchstring = replaceAll(searchstring, synonyms[synonymparentindex][synonymchildindex], "");
                searchstring2 = searchstring2 + " " + synonyms[synonymparentindex][0].replaceAll(" ", "-");
            }
        }
    }
    searchstring2 = searchstring2.trim() + " " + searchstring.trim();
    return searchstring2.trim();
}

function assimilate(ID){
    var startsearchstring = replacesynonyms($("#textsearch").val());
    var searchstring = startsearchstring.split(" ");
    var itemname = replacesynonyms($("#itemtitle" + ID).text());

    //quantity
    for(var searchindex = 0; searchindex<searchstring.length; searchindex++){
        log("Checking: " + searchstring[searchindex]);
        if(!isNaN( searchstring[searchindex] )){
            if($("#select" + ID + " option:contains('" + searchstring[searchindex] + "')").length > 0) {//make sure the quantity even exists
                if(itemname.indexOf( searchstring[searchindex] ) == -1) {//make sure the number isn't part of the item name
                    $("#select" + ID).val(searchstring[searchindex]);
                    searchstring[searchindex] = false;//remove it from the search, no need to check it twice
                }
            }
        }
    }

    //add ons/toppings
    $("#product-pop-up_" + ID  + " input").filter(":visible").each(function () {
        var Found = -1;
        var label = findlabel($(this)).find(".ver");
        if( !$(this).hasAttr("normalized") ){
            $(this).attr("normalized", replacesynonyms(label.contents().get(0).nodeValue));//cache results
        }
        label = $(this).attr("normalized");
        for(var searchindex = 0; searchindex<searchstring.length; searchindex++){
            if(searchstring[searchindex]) {
                if (label.indexOf(searchstring[searchindex]) > -1) {
                    Found = searchindex;
                    searchstring[searchindex] = false;//remove it from the search, no need to check it twice
                    searchindex = searchstring.length;
                }
            }
        }
        if(Found > -1){
            $(this).prop('checked', true);
        }
    });

    for (var i = searchstring.length-1; i > -1 ; i--) {
        if(!searchstring[i]) {
            searchstring.splice(i, 1);
        }
    }

    return [startsearchstring, searchstring];
}