//natural user interface

var tables = ["toppings", "wings_sauce"];//individual topping tables
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
];//when these words are directly before a topping, they indicate a quantity of the topping  ⁵⁶⁷⁸⁹
var qualifier_tables = new Array;

//makes a copy of an array/object without referencing the source
function cloneData(data) {
    var jsonString = JSON.stringify(data);
    return JSON.parse(jsonString);
}

//splits text up by " ", then checks if the cells contain $words (can be a string or an array),
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

//gets words between $leftword and $rightword. if $rightword isn't specified, gets all words after $leftword
function getwordsbetween(text, leftword, rightword){
    text = text.toLowerCase().split(" ");
    var start  = leftword+1;//text.indexOf(leftword)+1;
    var finish = text.length;
    if(!isUndefined(rightword)){
        finish = rightword;//text.indexOf(rightword, start);
    }
    return text.slice(start, finish).join(" ");
}

//procedural version of string.replaceAll
function replaceAll(Source, Find, ReplaceWith){
    Find = Find.replaceAll("[?]", "[?]");
    return Source.replaceAll(Find, ReplaceWith);
}

function replacesynonyms(searchstring, thesynonyms, includenotfounds, returnArray){
    //replace synonyms with the first term to normalize the search
    //thesynonyms [OPTIONAL]: if missing, will use global synonyms
    //includenotfounds [OPTIONAL]: if missing, the words that don't have synonyms will be included in the result
    //returnArray [OPTIONAL, assumes false]: if true, returns the array instead of the joined text
    if(isUndefined(thesynonyms)){thesynonyms = synonyms;}
    if(isUndefined(includenotfounds)){includenotfounds=true;}
    if(isUndefined(returnArray)){returnArray = false;}
    if(isArray(searchstring)){searchstring = searchstring.join(" ");}
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
    if(!includenotfounds){//filter words that do not have registered synonyms
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
    if(returnArray) {return searchstring;}
    return searchstring.join(" ").trim();
}

//make sure there are no instances of $texttoremove within $text (ie: search for double spaces in "       ", ending up with " "
function removemultiples(text, texttoremove, replacewith){
   while(text.indexOf(texttoremove) > -1){
       text = text.replaceAll(texttoremove, replacewith);
   }
   return text.trim();
}

//remove $words from $text
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

//gets the toppings and qualifiers from the search text (requires both the original and synonym-processed text)
function get_toppings(originalsearchstring, thesearchstring){
    var searchstring = replacesynonyms(thesearchstring, synonyms, true, true);
    var ret = new Array;
    var labelindex;
    var tablename;
    select(".tr-addon", function (element) {
        var Found = -1;
        var label = enum_labels(element);
        var qualifier;
        for(var searchindex = 0; searchindex<searchstring.length; searchindex++){
            if(searchstring[searchindex]) {
                if (label.indexOf(searchstring[searchindex]) > -1) {
                    tablename = attr(element, "table");
                    Found = searchindex;
                    qualifier = getqualifier(originalsearchstring, searchstring[searchindex]);
                    if(needsRemoving){searchstring[searchindex-1] = false;}
                    searchstring[searchindex] = false;//remove it from the search, no need to check it twice
                    searchindex = searchstring.length;
                }
            }
        }
        if(Found > -1){//qualifytopping("", qualifier, label);
            ret.push({searchindex: Found, qualifier: qualifier, label: label, needsRemoving: needsRemoving, tablename: tablename });
        }
    });
    return ret;
}

//get all toppings labels for the spellcheck
function enum_labels(element){
    if(isUndefined(element)) {
        qualifier_tables = new Array;
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
    qualifier_tables.push(attr(element, "table"));
    return attr(element, "normalized");
}

//remove empty cells from an array
function removeempties(array){
    for (var searchindex = array.length-1; searchindex > -1 ; searchindex--) {
        if(!array[searchindex]){
            removeindex(array, searchindex, 1);
        }
    }
}

//get toppings that weren't spelled correctly
function get_typos(itemname, originalsearchstring, thesearchstring, labels){
    var ret = new Array;
    if(isUndefined(labels)){labels = enum_labels();}
    if(!isArray(thesearchstring)){var searchstring = thesearchstring.split(" ")} else {var searchstring = cloneData(thesearchstring);}//should already be processed by replacesynonyms and get_toppings
    for (var searchindex = searchstring.length-1; searchindex > -1 ; searchindex--) {
        if (findsynonym(searchstring[searchindex], qualifiers)[0] == -1) {//handle simple typos
            if(itemname.indexOf( searchstring[searchindex] ) == -1) {
                var closestword = findclosestsynonym(searchstring[searchindex], 1, labels);
                closestword.word = closestword.word.replaceAll(" ", "-");
                if(closestword.word) {
                    var qualifier = getqualifier(originalsearchstring, searchstring[searchindex], closestword.word);
                    ret.push({searchindex: searchindex, qualifier: qualifier, label: closestword.word, needsRemoving: needsRemoving, originalword: searchstring[searchindex], distance: closestword.distance, tablename: qualifier_tables[closestword.parent], parent: closestword.parent, child: closestword.child });
                    searchstring[searchindex] = false;
                }
            }
        }
    }
    return ret;
}

//check all the topping's radio buttons
function qualifytoppings(toppings, searchstring, ID){
    if(isArray(toppings)) {
        for (var i = toppings.length - 1; i > -1; i--) {
            qualifytopping("", toppings[i].qualifier, toppings[i].label);
            if (toppings[i].needsRemoving) {
                searchstring[toppings[i].searchindex - 1] = false;
                //removeindex(searchstring, i, 1);
            }
            searchstring[toppings[i].searchindex] = false;
            //removeindex(searchstring, i, 1);
        }
        removeempties(searchstring);
    } else if ( isNumeric(toppings) ){
        if(toppings > -1){//make sure the quantity even exists
            var quantityselect = searchstring[toppings];
            if(select("#select" + ID + " option[id='" + searchstring[toppings] + "']").length > 0) {
                value("#select" + ID, searchstring[toppings]);
                searchstring[toppings] = false;
                //removeindex(searchstring, toppings, 1);
            }
            return quantityselect;
        }
    }
    return searchstring;
}

//gets the name of a menu item from the table
function get_itemname(ID){
    return replacesynonyms(text("#itemtitle" + ID));//can also use "#row" + ID + "-item"
}

//handles the processing of search text
function assimilate(ID, originalsearchstring){
    //if(isUndefined(originalsearchstring)) {originalsearchstring = value("#textsearch");}
    var defaults = clearaddons();
    originalsearchstring = removewords(originalsearchstring);
    var startsearchstring = replacesynonyms(originalsearchstring);
    var searchstring = startsearchstring.split(" ");
    var itemname = get_itemname(ID);
    var searchindex = get_quantity(searchstring, itemname);
    var quantity = qualifytoppings(searchindex, searchstring, ID);
    var toppings = get_toppings(originalsearchstring, searchstring);
    searchstring = qualifytoppings(toppings, searchstring);
    var typos = get_typos(itemname, originalsearchstring, searchstring);
    qualifytoppings(typos, cloneData(searchstring));
    defaults = removeduplicatetoppings(defaults, toppings);
    defaults = removeduplicatetoppings(defaults, typos);
    return [startsearchstring, searchstring, toppings, typos, defaults, quantity, itemname];
}

function removeduplicatetoppings(filterthis, leavethis){
    for(var i=0; i< leavethis.length; i++){
        for(var v=filterthis.length-1; v>-1; v--){
            //ret.push({qualifier: qualifier, label: topping, needsRemoving: false, tablename: table});
            if(filterthis[v].tablename.isEqual( leavethis[i].tablename ) && filterthis[v].label.isEqual( leavethis[i].label )){
                removeindex(filterthis, v);
                v=-1;
            }
        }
    }
    return filterthis;
}

//check a single topping's radio button
function qualifytopping(table, qualifier, topping){
    if(qualifier){qualifier = qualifier.replaceAll(" ", "-").toLowerCase();}
    if(!table){
        for(var i=0; i < tables.length; i++){
            qualifytopping(tables[i], qualifier, topping);
        }
    } else if(visible(".addons-" + table, false)) {
        if (!qualifier) {qualifier = "single";}
        var element = select(".tr-addon-" + table + "[normalized='" + topping + "']");
        attr(element, "SELECTED", qualifier);
        attr(children(element, "input[value='" + qualifier + "']"), "checked", true);
    }
}

//find the closest-spelled synonym to a keyword
//cutoff: the tolerance, words must be below this to count as spelled similarly to the keyword
//thesynonyms: multi-dimensional array of synonyms, first cell in each sub-array will be treated as the primary term
function findclosestsynonym(keyword, cutoff, thesynonyms){
    keyword = keyword.toLowerCase();
    var ret = "", parentID = -1, childID = -1;
    for(var synonymparentindex = 0; synonymparentindex< synonyms.length; synonymparentindex++){
        for(var synonymchildindex = 0; synonymchildindex < synonyms[synonymparentindex].length; synonymchildindex++){
            var value = levenshteinWeighted(keyword, synonyms[synonymparentindex][synonymchildindex]);
            if(value == 0){
                return {distance: 0, word: synonyms[synonymparentindex][synonymchildindex], parent: synonymparentindex, child: synonymchildindex};
            } else if (value < cutoff){
                cutoff = value;
                ret = synonyms[synonymparentindex][0];
                parentID = synonymparentindex;
                childID = synonymchildindex;
            }
        }
    }
    for(synonymparentindex=0; synonymparentindex < thesynonyms.length; synonymparentindex++){
        var value = levenshteinWeighted(keyword, thesynonyms[synonymparentindex]);
        if(value == 0){return thesynonyms[synonymparentindex];} else if (value < cutoff){
            cutoff = value;
            ret = thesynonyms[synonymparentindex];
            parentID = synonymparentindex;
        }
    }
    return {distance: cutoff, word: ret, parent: parentID, child: childID};
}
//finds the parent synonym of keyword, returning [it's parent ID, the child ID of the keyword itself]. returns [-1,-1] if not found
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

//checks the original search string for the word BEFORE the topping to see if it's a qualifer
var needsRemoving = false;//used to return 2 variables, check it AFTER running getqualifier()
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

//gets the custom qualifiers of a topping from the radio buttons
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

//gets all selected toppings/addons
function getaddons(table, astext){
    if(!table){
        var addons = new Array;
        for(var i=0; i < tables.length; i++){
            addons.push( getaddons(tables[i], true) );
        }
        return addons.join(", ").trim().trimright(",");
    }
    if(isUndefined(astext)){astext = false;}
    var qualifiers = new Array;
    var toppingIDs = new Array;
    var toppingNames = new Array;
    var tablenames = new Array;
    //if(visible(".addons-" + table)) {
        select(".tr-addon-" + table, function (element) {
            var Selected = attr(element, "SELECTED");
            if (Selected) {
                if (astext) {
                    Selected = gettoppingqualifier(table, Selected, attr(element, "name"));
                    qualifiers.push(Selected + " " + attr(element, "name"));
                } else {
                    qualifiers.push(Selected);
                    toppingIDs.push(attr(element, "TOPPINGID"));
                    toppingNames.push(attr(element, "name"));
                    tablenames.push(attr(element, "table"));
                }
            }
        });
    //}
    if(astext){
        return qualifiers.join(", ");
    }
    return [qualifiers, toppingIDs, toppingNames, tablenames];
}

//reset the toppings/addons
function clearaddons(table){
    if(isUndefined(table)){
        var defaults = new Array;
        for(var i=0; i < tables.length; i++){
            clearaddons(tables[i]);
        }
        defaults.push(clicktopping("toppings", "single", "cheese"));
        defaults.push(clicktopping("toppings", "single", "italian tomato sauce"));
        defaults.push(clicktopping("toppings", "regular", "cooked"));
        return defaults;
    } else {
        value(".quantityselect", "1");
        table = "-" + table;
        attr(".tr-addon" + table, "SELECTED", "");
        attr(".addon" + table, "checked", false);
    }
}

//click a topping's radio button
function clicktopping(table, qualifier, topping){
    var classname = ".addon-" + table + "-" + qualifier.toLowerCase() + "-" + topping.toLowerCase().replaceAll(" ", "-");
    trigger(classname, "click");
    attr(classname, "checked", true);
    return {qualifier: qualifier, label: topping, needsRemoving: false, tablename: table};
}

//compares $arr with $comparewith, starting at $arr[$startingindex] and $comparewith[0]
//if $comparewith isn't an array/is a string, it'll be split on " "
function arraycompare(arr, startingindex, comparewith){
    if(!isArray(comparewith)){comparewith = comparewith.split(" ");}
    for(var i = 0; i < comparewith.length; i++){
        if(i+startingindex > arr.length - 1){return false;}
        if (!arr[i+startingindex].isEqual( comparewith[i])){return false;}
    }
    return true;
}