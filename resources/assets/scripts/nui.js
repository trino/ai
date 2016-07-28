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
    var label = select("label[for='"+attr(element, 'id')+"']");
    if (label.length == 0) {
        label = closest(element, 'label')
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
    var startsearchstring = replacesynonyms(value("#textsearch"));
    var searchstring = startsearchstring.split(" ");
    var itemname = replacesynonyms(text("#itemtitle" + ID));
    //quantity
    for(var searchindex = 0; searchindex<searchstring.length; searchindex++){
        log("Checking: " + searchstring[searchindex]);
        if(!isNaN( searchstring[searchindex] )){
            if(select("#select" + ID + " option:contains('" + searchstring[searchindex] + "')").length > 0) {//make sure the quantity even exists
                if(itemname.indexOf( searchstring[searchindex] ) == -1) {//make sure the number isn't part of the item name
                    value("#select" + ID, searchstring[searchindex]);
                    searchstring[searchindex] = false;//remove it from the search, no need to check it twice
                }
            }
        }
    }

    //add ons/toppings
    select("#product-pop-up_" + ID  + " input", function (element) {
        var Found = -1;
        var label = findlabel(element);
        if( !hasattribute(element, "normalized") ){
            label = text(label);
            attr(element, "normalized", replacesynonyms(label));//cache results
        }
        label = attr(element, "normalized");
        
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
            attr(element, 'checked', true);
        }
    });

    for (var i = searchstring.length-1; i > -1 ; i--) {
        if(!searchstring[i]) {
            searchstring.splice(i, 1);
        }
    }

    return [startsearchstring, searchstring];
}