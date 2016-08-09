//natural user interface

var tables = ["toppings", "wings_sauce"];
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

    ["2", "two"],
    ["cooked", "done"]
];
var qualifiers = [
    ["quarter"],
    ["half", "less", "easy"],
    ["single", "regular", "normal", "one", "1"],
    ["double", "extra", "more", "two", "2"],
    ["triple", "three", "3"],
    ["quadruple", "four", "4"]
];//⁵⁶⁷⁸⁹

function cloneData(data) {
    var jsonString = JSON.stringify(data);// Convert the data into a string first
    return JSON.parse(jsonString);//  Parse the string to create a new instance of the data
}

String.prototype.containswords = function (words){
    var text = this.toLowerCase().split(" ");
    var count = new Array;
    var index;
    if(isArray(words)) {
        for (var i = 0; i < words.length; i++) {
            index = text.indexOf(words[i].toLowerCase());
            if( index > -1 ){count.push(index);}//count.push(words[i].toLowerCase());
        }
    } else {
        index = text.indexOf(words.toLowerCase());
        if( index > -1 ){count.push(index);}//count.push(words[i].toLowerCase());
    }
    return count;
};

function getwordsbetween(text, leftword, rightword){
    text = text.toLowerCase().split(" ");
    var start  = leftword+1;//text.indexOf(leftword)+1;
    var finish = text.length;
    if(!isUndefined(rightword)){
        finish = rightword;//text.indexOf(rightword, start);
    }
    return text.slice(start, finish).join(" ");
}

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
    if(isUndefined(includenotfounds)){includenotfounds=true;}
    searchstring = searchstring.trim().toLowerCase().replaceAll("-", " ").split(" ");
    for(var searchstringindex = searchstring.length-1; searchstringindex >= 0; searchstringindex--){
        var wasfound = false;
        for(var synonymparentindex = 0; synonymparentindex< thesynonyms.length; synonymparentindex++){
            for(var synonymchildindex = 0; synonymchildindex < thesynonyms[synonymparentindex].length; synonymchildindex++){
                if(!wasfound){
                    var synonym = thesynonyms[synonymparentindex][synonymchildindex].split(" ");
                    wasfound = arraycompare(searchstring, searchstringindex, synonym);
                    if(wasfound) {
                        searchstring[searchstringindex] = thesynonyms[synonymparentindex][0];
                        if(synonym.length>1){
                            searchstring.splice(searchstringindex+1, synonym.length-1);//remove words that were used
                        }
                    }
                }
            }
        }
    }
    if(!includenotfounds){
        for(var searchstringindex = 0; searchstringindex < searchstring.length; searchstringindex++){
            var wasfound = false;
            for(var synonymparentindex = 0; synonymparentindex< thesynonyms.length; synonymparentindex++){
                var synonym = thesynonyms[synonymparentindex][0].split(" ");
                wasfound = arraycompare(searchstring, searchstringindex, synonym);
                if(wasfound){ synonymparentindex = thesynonyms.length; }
            }
            if(!wasfound){
                searchstring[searchstringindex] = false;
            }
        }
        removeempties(searchstring);
    }
    return searchstring.join(" ").trim();
    /*
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
    */
}

function removemultiples(text, texttoremove, replacewith){
   while(text.indexOf(texttoremove) > -1){
       text = text.replaceAll(texttoremove, replacewith);
   }
   return text.trim();
}

function removewords(text, words){
    if(isUndefined(words)){words = wordstoignore;}
    text = text.toLowerCase().split(" ");
    for(var i=text.length-1; i>-1; i--){
        if(words.indexOf(text[i]) > -1){
            removeindex(text, i);
        }
    }
    text = removemultiples(text.join(" "), "  ", " ");
    return text;
}

//DOES NOT RETURN THE QUANTITY!!!! Returns the index of searchstring where the quantity was found (an array split by " ")
function get_quantity(searchstring, itemname){
    if(!isArray(searchstring)){searchstring = searchstring.split(" ")}//should already be processed by replacesynonyms
    for(var searchindex = 0; searchindex<searchstring.length; searchindex++){
        if(isNumeric( searchstring[searchindex] )){
            if(itemname.indexOf( searchstring[searchindex] ) == -1) {//make sure the number isn't part of the item name
                return searchindex;
            }
        }
    }
    return -1;
}

function get_toppings(originalsearchstring, thesearchstring){
    if(!isArray(thesearchstring)){var searchstring = thesearchstring.split(" ")} else {var searchstring = cloneData(thesearchstring);}//should already be processed by replacesynonyms
    var ret = new Array;
    select(".tr-addon", function (element) {
        var Found = -1;
        var label = enum_labels(element); //findlabel(element);
        var qualifier;
        for(var searchindex = 0; searchindex<searchstring.length; searchindex++){
            if(searchstring[searchindex]) {
                if (label.indexOf(searchstring[searchindex]) > -1) {
                    Found = searchindex;
                    qualifier = getqualifier(originalsearchstring, searchstring[searchindex]);
                    if(needsRemoving){searchstring[searchindex-1] = false;}
                    searchstring[searchindex] = false;//remove it from the search, no need to check it twice
                    searchindex = searchstring.length;
                }
            }
        }
        if(Found > -1){//qualifytopping("", qualifier, label);
            ret.push({searchindex: Found, qualifier: qualifier, label: label, needsRemoving: needsRemoving });
        }
    });
    return ret;
}

function enum_labels(element){
    if(isUndefined(element)) {
        var labels = new Array;//add ons/toppings list for spell check
        select(".tr-addon", function (element) {
            labels.push(enum_labels(element));
        });
        return labels;
    }
    var label = attr(element, "name"); //findlabel(element);
    if (!hasattribute(element, "normalized")) {
        attr(element, "normalized", replacesynonyms(label));//cache results
    }
    return attr(element, "normalized");
}

function removeempties(array){
    for (var searchindex = array.length-1; searchindex > -1 ; searchindex--) {
        if(!array[searchindex]){
            removeindex(array, searchindex, 1);
        }
    }
}

function get_typos(itemname, originalsearchstring, thesearchstring, labels){
    var ret = new Array;
    if(isUndefined(labels)){labels = enum_labels();;}
    if(!isArray(thesearchstring)){var searchstring = thesearchstring.split(" ")} else {var searchstring = cloneData(thesearchstring);}//should already be processed by replacesynonyms and get_toppings
    for (var searchindex = searchstring.length-1; searchindex > -1 ; searchindex--) {
        if (findsynonym(searchstring[searchindex], qualifiers)[0] == -1) {//handle simple typos
            if(itemname.indexOf( searchstring[searchindex] ) == -1) {
                var closestword = findclosestsynonym(searchstring[searchindex], 1, labels).replaceAll(" ", "-");
                if(closestword) {
                    var qualifier = getqualifier(originalsearchstring, searchstring[searchindex], closestword);
                    ret.push({searchindex: searchindex, qualifier: qualifier, label: closestword, needsRemoving: needsRemoving });
                }
            }
        }
    }
    return ret;
}

function qualifytoppings(toppings, searchstring, ID){
    if(isArray(toppings)) {
        for (var i = toppings.length - 1; i > -1; i--) {
            if (toppings[i].needsRemoving) {searchstring[toppings[i].searchindex - 1] = false;}//remove words that were used
            qualifytopping("", toppings[i].qualifier, toppings[i].label);
            searchstring[toppings[i].searchindex] = false;
        }
        removeempties(searchstring);
    } else if ( isNumeric(toppings) ){
        if(toppings > -1){//make sure the quantity even exists
            if(select("#select" + ID + " option[id='" + searchstring[toppings] + "']").length > 0) {
                value("#select" + ID, searchstring[toppings]);
                searchstring[toppings] = false;
            }
        }
    }
}

function assimilate(ID, originalsearchstring){
    //if(isUndefined(originalsearchstring)) {originalsearchstring = value("#textsearch");}
    log("originalsearchstring BEFORE: " + originalsearchstring);
    originalsearchstring = removewords(originalsearchstring);
    var startsearchstring = replacesynonyms(originalsearchstring);
    var searchstring = startsearchstring.split(" ");
    var itemname = replacesynonyms(text("#itemtitle" + ID));
    clearaddons();

    log("originalsearchstring AFTER: " + originalsearchstring);
    log("startsearchstring: " + startsearchstring);
    log("searchstring: " + searchstring);
    log("itemname: " + itemname);

    var searchindex = get_quantity(searchstring, itemname);
    qualifytoppings(searchindex, searchstring, ID);

    var toppings = get_toppings(originalsearchstring, searchstring);
    qualifytoppings(toppings, searchstring);

    var typos = get_typos(itemname, originalsearchstring, searchstring);
    qualifytoppings(typos, searchstring);

    return [startsearchstring, searchstring];
}

function qualifytopping(table, qualifier, topping){
    if(qualifier){qualifier = qualifier.replaceAll(" ", "-").toLowerCase();}
    if(!table){
        for(var i=0; i < tables.length; i++){
            qualifytopping(tables[i], qualifier, topping);
        }
    } else {
        if (!qualifier) {qualifier = "single";}
        console.log("qualifytopping: " + table + " " + qualifier + " " + topping);
        var element = select(".tr-addon-" + table + "[normalized='" + topping + "']");
        attr(element, "SELECTED", qualifier);
        attr(children(element, "input[value='" + qualifier + "']"), "checked", true);
    }
}

function findclosestsynonym(keyword, cutoff, thesynonyms){
    keyword = keyword.toLowerCase();
    var ret = "";
    for(var synonymparentindex = 0; synonymparentindex< synonyms.length; synonymparentindex++){
        for(var synonymchildindex = 0; synonymchildindex < synonyms[synonymparentindex].length; synonymchildindex++){
            var value = levenshteinWeighted(keyword, synonyms[synonymparentindex][synonymchildindex]);
            if(value == 0){return synonyms[synonymparentindex][synonymchildindex];} else if (value < cutoff){
                cutoff = value;
                ret = synonyms[synonymparentindex][0];
            }
        }
    }
    for(synonymparentindex=0; synonymparentindex < thesynonyms.length; synonymparentindex++){
        var value = levenshteinWeighted(keyword, thesynonyms[synonymparentindex]);
        if(value == 0){return thesynonyms[synonymparentindex];} else if (value < cutoff){
            cutoff = value;
            ret = thesynonyms[synonymparentindex];
        }
    }
    return ret;
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

var needsRemoving = false;
function getqualifier(startsearchstring, keyword, toppingword){
    needsRemoving = false;
    keyword = replacesynonyms(keyword);
    if(isUndefined(toppingword)){toppingword = keyword;}
    //alert(toppingword + " - " + keyword);

    var synonymindex = findsynonym(keyword);
    if(synonymindex[0] > -1){
        keyword = synonyms[synonymindex[0]][0].replaceAll(" ", "-");
        startsearchstring = startsearchstring.replaceAll( synonyms[synonymindex[0]][synonymindex[1]], keyword);
    }
    startsearchstring = startsearchstring.split(" ");
    var wordID = startsearchstring.indexOf(keyword);
    if(wordID > 0){
        var qualifier = startsearchstring[wordID-1].toLowerCase();

        //custom qualifiers
        for(var i = 0; i < qualifiers.length; i++) {
            var qualifierKey = qualifiers[i][0];
            var qualifierValue = gettoppingqualifier("", qualifierKey, toppingword);
            if(qualifier && qualifierValue && !qualifierKey.isEqual(qualifierValue)) {
                var found = qualifierValue.toLowerCase().indexOf(qualifier) > -1;
                console.log("Checking if " + qualifier + " matches " + qualifierValue + " (" + found  + ")");
                if(found){
                    needsRemoving=true;
                    return qualifierKey;
                }
            }
        }

        var qualifierValue = replacesynonyms(qualifier, qualifiers, false);
        if(qualifierValue){
            needsRemoving=true;
            return qualifierValue;
        }
    }
    return "single";
}

function gettoppingqualifier(table, qualifier, topping){
    if(table) {
        var classname = ".addon-" + table + "-" + qualifier + "-" + topping.toLowerCase().replaceAll(" ", "-");
        return text(closest(classname, "label"));
    }
    for(var i=0; i < tables.length; i++){
        var thetext = gettoppingqualifier(tables[i], qualifier, topping);
        if(thetext){return thetext;}
    }
    return false;
}

//Damerau–Levenshtein distance
//https://gist.github.com/doukremt/9473228
function levenshteinWeighted (seq1,seq2) {
    var len1=seq1.length, len2=seq2.length;
    var i, j, dist, ic, dc, rc, last, old, column;

    var weighter={
        insert:     function(c)     { return 1.; },
        delete:     function(c)     { return 0.5; },
        replace:    function(c, d)  { return 0.3; }
    };

    if (len1 == 0 || len2 == 0) {
        dist = 0;
        while (len1) {
            dist += weighter.delete(seq1[--len1]);
        }
        while (len2) {
            dist += weighter.insert(seq2[--len2]);
        }
        return dist;
    }

    column = [];
    column[0] = 0;
    for (j = 1; j <= len2; ++j) {
        column[j] = column[j - 1] + weighter.insert(seq2[j - 1]);
    }
    for (i = 1; i <= len1; ++i) {
        last = column[0]; /* m[i-1][0] */
        column[0] += weighter.delete(seq1[i - 1]); /* m[i][0] */
        for (j = 1; j <= len2; ++j) {
            old = column[j];
            if (seq1[i - 1] == seq2[j - 1]) {
                column[j] = last; /* m[i-1][j-1] */
            } else {
                ic = column[j - 1] + weighter.insert(seq2[j - 1]);      /* m[i][j-1] */
                dc = column[j] + weighter.delete(seq1[i - 1]);          /* m[i-1][j] */
                rc = last + weighter.replace(seq1[i - 1], seq2[j - 1]); /* m[i-1][j-1] */
                column[j] = ic < dc ? ic : (dc < rc ? dc : rc);
            }
            last = old;
        }
    }

    dist = column[len2];
    return dist;
}

function getaddons(table, astext){
    if(!table){
        var addons = new Array;
        for(var i=0; i < tables.length; i++){
            addons.push( getaddons(tables[i], true) );
        }
        return addons.join(", ");
    }
    if(isUndefined(astext)){astext = false;}
    var qualifiers = new Array;
    var toppingIDs = new Array;
    select(".tr-addon-" + table, function(element){
        var Selected = attr(element, "SELECTED");
        if(Selected){
            if(astext){
                Selected = gettoppingqualifier(table, Selected, attr(element, "name"));
                qualifiers.push(Selected + " " + attr(element, "name"));
            } else {
                qualifiers.push(Selected);
                toppingIDs.push(attr(element, "TOPPINGID"))
            }
        }
    });
    if(astext){
        return qualifiers.join(", ");
    }
    return [qualifiers, toppingIDs];
}

function clearaddons(table){
    if(isUndefined(table)){
        for(var i=0; i < tables.length; i++){
            clearaddons(tables[i]);
        }
    } else {
        value(".quantityselect", "1");
        table = "-" + table;
        attr(".tr-addon" + table, "SELECTED", "");
        attr(".addon" + table, "checked", false);
        clicktopping("toppings", "single", "cheese");
        clicktopping("toppings", "single", "italian tomato sauce");
        clicktopping("toppings", "regular", "cooked");
    }
}

function clicktopping(table, qualifier, topping){
    var classname = ".addon-" + table + "-" + qualifier.toLowerCase() + "-" + topping.toLowerCase().replaceAll(" ", "-");
    trigger(classname, "click");
    attr(classname, "checked", true);
}

function arraycompare(arr, startingindex, comparewith){
    if(!isArray(comparewith)){comparewith = comparewith.split(" ");}
    for(var i = 0; i < comparewith.length; i++){
        if(i+startingindex > arr.length - 1){return false;}
        if (!arr[i+startingindex].isEqual( comparewith[i])){return false;}
    }
    return true;
}