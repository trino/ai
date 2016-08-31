//natural user interface

var synonyms = [//multi-dimensional array of multi-word terms, the first term is the primary terms, followed by the secondary terms
    ["jalapenos", "jalapeno", "jalapeño", "jalapeños", "jalape?o"],
    ["green peppers"],
    ["red peppers"],
    ["black olives", "kalamata olives"],
    ["sundried tomatoes", "sun dried tomatoes", "sun dried tomatoes", "sun dried tomatos", "sun dried tomatos", "sundried tomatos"],
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
    ["single", "regular", "normal"],
    ["double", "extra", "more"],
    ["triple", "three"],
    ["quadruple", "four"]
];//when these words are directly before a topping, they indicate a quantity of the topping  ⁵⁶⁷⁸⁹
var qualifier_tables = new Array;
var quantityselect = 0;

var order = new Array;
var surcharge = 3.50;
var lastquantity = 0;

//makes a copy of an array/object without referencing the source
function cloneData(data) {
    var jsonString = JSON.stringify(data);
    return JSON.parse(jsonString);
}

//splits text up by " ", then checks if the cells contain $words (can be a string or an array),
String.prototype.containswords = function (words){
    return containswords(this, words);
};

function containswords(text, words){
    if(!isArray(text)) {text = text.toLowerCase().split(" ");}
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
}

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
    var labels = enum_labels();
    var tablename;
    var maxwordtolerance = 3;

    for(var searchindex = 0; searchindex < searchstring.length; searchindex++){
        var closestword = findclosestsynonym(searchstring[searchindex], 1, labels);
        if(closestword.word){
            var tablename = GetAddonTable(closestword.word);
            var qualifier = getqualifier(originalsearchstring, searchstring[searchindex]);
            if (needsRemoving) {searchstring[searchindex - 1] = false;}
            searchstring[searchindex] = false;//remove it from the search, no need to check it twice
            ret.push({searchindex: searchindex, qualifier: qualifier, label: closestword.word, needsRemoving: needsRemoving, tablename: tablename });
        }
    }
    /*
    select(".tr-addon", function (element) {
        var Found = -1;
        var label = enum_labels(element);
        var qualifier;
        for(var searchindex = 0; searchindex<searchstring.length; searchindex++){
            if(searchstring[searchindex]) {
                if (label.indexOf(searchstring[searchindex]) > -1) {//if this topping contains the entire text, so sundried tomatoes would count for tomatoes
                    var wordsinlabel = label.split(" ").length;
                    if(containswords(searchstring, label) == wordsinlabel) {
                        tablename = attr(element, "table");
                        Found = searchindex;
                        qualifier = getqualifier(originalsearchstring, searchstring[searchindex]);
                        if (needsRemoving) {searchstring[searchindex - 1] = false;}
                        searchstring[searchindex] = false;//remove it from the search, no need to check it twice
                        searchindex = searchstring.length;
                    }
                }
            }
        }
        if(Found > -1){//qualifytopping("", qualifier, label);
            ret.push({searchindex: Found, qualifier: qualifier, label: label, needsRemoving: needsRemoving, tablename: tablename });
        }
    });
    */
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
                if(!isUndefined(closestword.word)){
                    closestword.word = closestword.word.replaceAll(" ", "-");
                    if(closestword.word && labels.indexOf(closestword.word) > -1) {
                        var qualifier = getqualifier(originalsearchstring, searchstring[searchindex], closestword.word);
                        ret.push({searchindex: searchindex, qualifier: qualifier, label: closestword.word, needsRemoving: needsRemoving, originalword: searchstring[searchindex], distance: closestword.distance, tablename: qualifier_tables[closestword.parent], parent: closestword.parent, child: closestword.child });
                        searchstring[searchindex] = false;
                    }
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
            quantityselect = searchstring[toppings];
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
function assimilate(ID, originalsearchstring, isPerfectlyFormed){
    if(isUndefined(isPerfectlyFormed)) {isPerfectlyFormed = false;}
    var itemname = get_itemname(ID);
    if(isPerfectlyFormed){
        var startsearchstring = originalsearchstring.split(",");
        for(var i=0; i<startsearchstring.length; i++){
            startsearchstring[i] = startsearchstring[i].split("|");
            //qualifytopping(startsearchstring[i][0], startsearchstring[i][1]);
            startsearchstring[i] = {searchindex: i, qualifier: startsearchstring[i][0], label: startsearchstring[i][1], needsRemoving: false, tablename: ""};
        }
        return startsearchstring;
    } else {
        var defaults = clearaddons();
        originalsearchstring = removewords(originalsearchstring);
        var startsearchstring = replacesynonyms(originalsearchstring);
        var searchstring = startsearchstring.split(" ");
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
        if(value == 0){
            return {distance: 0, word: thesynonyms[synonymparentindex], parent: synonymparentindex, child: 0};
        } else if (value < cutoff){
            cutoff = value;
            ret = thesynonyms[synonymparentindex];
            parentID = synonymparentindex;
        }
    }
    return {distance: cutoff, word: ret, parent: parentID, child: childID};
}

//finds the parent synonym of keyword, returning [0=it's parent ID, 1=the child ID of the keyword itself, 2=distance, 3=closest parent word, 4=closest child word].
//returns [-1,-1,-1, "", ""] if not found. cutoff needs to be above 0 to search for typos
function findsynonym(keyword, thesynonyms, cutoff){
    if(isUndefined(thesynonyms)){thesynonyms = synonyms;}
    if(isUndefined(cutoff)){cutoff = 0;}
    keyword = keyword.toLowerCase();
    var ret = "", parentID = -1, childID = -1, wordfound = "";
    for(var synonymparentindex = 0; synonymparentindex< thesynonyms.length; synonymparentindex++){
        for(var synonymchildindex = 0; synonymchildindex < thesynonyms[synonymparentindex].length; synonymchildindex++){
            if(thesynonyms[synonymparentindex][synonymchildindex] == keyword){
                return [synonymparentindex, synonymchildindex, 0, thesynonyms[synonymparentindex][0],thesynonyms[synonymparentindex][0]];//found exact match
            } else if(cutoff>0){
                var value = levenshteinWeighted(thesynonyms[synonymparentindex][synonymchildindex], keyword);
                if (value < cutoff){
                    cutoff = value;
                    ret = thesynonyms[synonymparentindex][0];
                    parentID = synonymparentindex;
                    childID = synonymchildindex;
                    wordfound = thesynonyms[synonymparentindex][synonymchildindex];
                }
            }
        }
    }
    if(ret){return [parentID,childID,cutoff,ret,wordfound];}//found typo
    return [-1,-1,-1, "", ""];//found nothing
}

//checks the original search string for the word BEFORE the topping to see if it's a qualifer
var needsRemoving = false;//used to return 2 variables, check it AFTER running getqualifier()
function getqualifier(startsearchstring, keyword, toppingword){
    needsRemoving = false;
    keyword = replacesynonyms(keyword);
    if(isUndefined(toppingword)){toppingword = keyword;}
    var synonymindex = findsynonym(keyword);
    if(synonymindex[0] > -1){
        keyword = synonyms[synonymindex[0]][0].replaceAll(" ", "-");
        startsearchstring = startsearchstring.replaceAll( synonyms[synonymindex[0]][synonymindex[1]], keyword);
    }
    startsearchstring = startsearchstring.split(" ");
    var wordID = startsearchstring.indexOf(keyword);
    if(wordID > 0){
        var qualifier = startsearchstring[wordID-1].toLowerCase();

        var qualiferdistance = 1;
        var qualiferName = "";

        //custom qualifiers
        for(var i = 0; i < qualifiers.length; i++) {
            var qualifierKey = qualifiers[i][0];
            var qualifierValue = gettoppingqualifier("", qualifierKey, toppingword);
            var currentweight = levenshteinWeighted(qualifier, qualifierValue);

            if(qualifier && qualifierValue && !qualifierKey.isEqual(qualifierValue)) {
                var found = qualifierValue.toLowerCase().indexOf(qualifier) > -1;
                console.log("Checking if " + qualifier + " matches " + qualifierValue + " (" + found  + ")");
                if(found){
                    needsRemoving=true;
                    return qualifierKey;
                }
            } else if (currentweight < qualiferdistance) {
                qualiferName = qualifierKey;
                qualiferdistance = currentweight;
            }
        }

        if(qualiferName){qualifier = qualiferName;}

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
        var classname = ".addon-" + table + "-" + qualifier + "-" + topping.toLowerCase().replaceAll(" ", "-").replaceAll(",", "");
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
    seq1 = seq1.toLowerCase();
    seq2 = seq2.toLowerCase();
    if(seq1 == seq2){return 0;}
    var len1=seq1.length, len2=seq2.length, i, j, dist, ic, dc, rc, last, old, column;

    var weighter={
        insert:     function(c)     { return 1.0; },
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
        defaults.push(clicktopping("toppings", "single", "tomato sauce"));
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








//receipt generation
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
        var addons = assimilateaddons(ID, element, i, DoPerfectlyFormed);
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
        if(!addons[i].label.isEqual("quantity")) {
            if (!addons[i].tablename) {
                if(IsAddonInTable(addons[i].label, tablename)){
                    ret.push(addons[i]);
                }
            } else if (tablename.isEqual(addons[i].tablename)) {
                ret.push(addons[i]);
            }
        }
    }
    return ret;
}

function GetAddonTable(addon){
    for(var i=0; i < tables.length; i++){
        if(IsAddonInTable(addon, tables[i])){
            return tables[i];
        }
    }
}
function IsAddonInTable(addon, tablename){
    var Sections = Object.keys(addons[tablename]);
    addon = addon.replaceAll(" ", "");
    for(var SectionID = 0; SectionID < Sections.length; SectionID++){
        var Toppings = Object.keys(addons[tablename][Sections[SectionID]]);
        for(var ToppingID = 0; ToppingID < Toppings.length; ToppingID++){
            if(addon.isEqual(Toppings[ToppingID].replaceAll(" ", ""))){return true;}
        }
    }
}

var assimilate_enabled = true;
function assimilateaddons(ID, element, Index, isPerfectlyFormed){
    //[0=startsearchstring, 1=searchstring, 2=toppings, 3=typos, 4=defaults, 5=quantity, 6=itemname]
    if(isUndefined(isPerfectlyFormed)){isPerfectlyFormed = false;}
    var defaults = true;
    if(isUndefined(Index)){
        var defaults = false;
        var toppings = assimilate(ID, element, isPerfectlyFormed);
    } else {
        var toppings = assimilate(ID, element.getAttribute("item" + Index), isPerfectlyFormed);
    }
    if(isPerfectlyFormed){return toppings;}
    lastquantity = toppings[5];
    if(defaults){
        toppings = toppings[2].concat( toppings[3] ).concat( toppings[4] );
    } else {
        toppings = toppings[2].concat( toppings[3] );
    }
    return removeduplicates(toppings, "label");
}

function removeduplicates(source, key){
    var dest = new Array();
    for (var i = 0; i < source.length; i++){
        var current = source[i];
        if(isUndefined(current.checked)){
            for(var v=i+1; v<source.length; v++){
                if( current[key].isEqual( source[v][key] )){
                    source[v].checked = true;
                }
            }
            dest.push(source[i]);
        }
    }
    return dest;
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
        if(item.quantity > 1){text += 'x' + item.quantity + '<HR>($' + Number(item.price * item.quantity).toFixed(2) + ')';}
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
    item.quantity = Number(item.quantity) + Number(value);
    if(item.quantity == 0){
        deleteitem(index);
    }
    generatereceipt();
}

function stringifyaddons(addons, istoppingslist){
    var text = "";
    var delimiter = ", ";
    if(isUndefined(istoppingslist)){istoppingslist = false;}
    if(istoppingslist){delimiter=",";}
    for(var i=0; i<addons.length;i++){
        if(i > 0){text += delimiter;}
        if(istoppingslist){
            text +=  addons[i].qualifier + "|" + addons[i].label;
        } else {
            text += '<I TITLE="' + JSON.stringify(addons[i]).replaceAll('"', "'") + '">' + addons[i].qualifier + "</I>  " + addons[i].label;
        }
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