//natural user interface

var wordstoignore = ["the", "with", "and", "times", "on"];
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
var qualifiers = [
    ["¼", "quarter"],
    ["½", "less", "easy", "half"],
    ["¹", "single", "regular", "normal", "one", "1"],
    ["²", "double", "extra", "more", "two", "2"],
    ["³", "triple", "three", "3"],
    ["⁴", "quadruple", "four", "4"]
];//⁵⁶⁷⁸⁹

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

function replacesynonyms(searchstring, thesynonyms, includenotfounds){
    //replace synonyms with the first term to normalize the search
    //thesynonyms [OPTIONAL], if missing, will use global synonyms
    //includenotfounds [OPTIONAL], if missing, the words that don't have synonyms will be included in the result
    if(isUndefined(thesynonyms)){thesynonyms = synonyms;}
    includenotfounds = isUndefined(includenotfounds);
    searchstring = searchstring.trim().toLowerCase().replaceAll("-", " ");
    var searchstring2 = "";
    var temp = -1;
    for(var synonymparentindex = 0; synonymparentindex< thesynonyms.length; synonymparentindex++){
        for(var synonymchildindex = 0; synonymchildindex < thesynonyms[synonymparentindex].length; synonymchildindex++){
            temp = searchstring.indexOf(thesynonyms[synonymparentindex][synonymchildindex]);
            if(temp  > -1){
                searchstring = replaceAll(searchstring, thesynonyms[synonymparentindex][synonymchildindex], "");
                searchstring2 = searchstring2 + " " + thesynonyms[synonymparentindex][0].replaceAll(" ", "-");
            }
        }
    }
    if(includenotfounds) {searchstring2 = searchstring2.trim() + " " + searchstring.trim();}
    return searchstring2.trim();
}

function removemultiples(text, texttoremove, replacewith){
   while(text.indexOf(texttoremove) !== -1){
       text = text.replaceAll(text, replacewith);
   }
    return text;
}

function removewords(text, words){
    if(isUndefined(words)){words = wordstoignore;}
    text = text.toLowerCase().split(" ");
    for(var i=text.length-1; i>-1; i--){
        if(words.indexOf(text[i]) >-1){
            removeindex(text, i);
        }
    }
    return removemultiples(text.join(" "), "  ", " ").trim();
}

function assimilate(ID){
    var originalsearchstring = removewords(value("#textsearch"));
    var startsearchstring = replacesynonyms(originalsearchstring);
    var searchstring = startsearchstring.split(" ");
    var itemname = replacesynonyms(text("#itemtitle" + ID));
    //quantity
    for(var searchindex = 0; searchindex<searchstring.length; searchindex++){
        if(isNumeric( searchstring[searchindex] )){
            if(select("#select" + ID + " option[id='" + searchstring[searchindex] + "']").length > 0) {//make sure the quantity even exists
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
        var qualifier;
        for(var searchindex = 0; searchindex<searchstring.length; searchindex++){
            if(searchstring[searchindex]) {
                if (label.indexOf(searchstring[searchindex]) > -1) {
                    Found = searchindex;
                    qualifier = getqualifier(originalsearchstring, searchstring[searchindex]);
                    searchstring[searchindex] = false;//remove it from the search, no need to check it twice
                    searchindex = searchstring.length;
                }
            }
        }
        if(Found > -1){
            attr("#ver" + element.id, 'title', qualifier);
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

function findsynonym(keyword, thesynonyms){
    if(isUndefined(thesynonyms)){thesynonyms = synonyms;}
    keyword = keyword.toLowerCase();
    for(var synonymparentindex = 0; synonymparentindex< thesynonyms.length; synonymparentindex++){
        for(var synonymchildindex = 0; synonymchildindex < thesynonyms[synonymparentindex].length; synonymchildindex++){
            if(thesynonyms[synonymparentindex][synonymchildindex] == keyword){return [synonymparentindex, synonymchildindex];}
        }
    }
    return [-1,-1];
}

function getqualifier(startsearchstring, keyword){
    keyword = replacesynonyms(keyword);
    var synonymindex = findsynonym(keyword);
    if(synonymindex[0] > -1){
        keyword = synonyms[synonymindex[0]][0].replaceAll(" ", "-");
        startsearchstring = startsearchstring.replaceAll( synonyms[synonymindex[0]][synonymindex[1]], keyword);
    }
    startsearchstring = startsearchstring.split(" ");
    var wordID = startsearchstring.indexOf(keyword);
    if(wordID > 0){
        var qualifier = startsearchstring[wordID-1];
        return replacesynonyms(qualifier, qualifiers, false);
    }
    return "¹";
}