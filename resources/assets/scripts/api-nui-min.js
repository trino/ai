/* Generated at 1473255998 */ /*api*/ //Mini Jquery replacement
//get more functionality from http://youmightnotneedjquery.com/
//Siblings, Prev, Prepend, Position Relative To Viewport, Position, Parent, Outer Width With Margin, Outer Width, Outer Height With Margin, Outer Height, Offset Parent, Offset, Next, Matches Selector, matches, Find Children, Filter, Contains Selector, Contains, Clone, Children, Append
var debugmode = true;
var todoonload = new Array;
Date.now = function(verbose) {
    if(isUndefined(verbose)) {return new Date().getTime();}
    return new Date().toJSON();
};

//replaces all instances of $search within a string with $replacement
String.prototype.replaceAll = function (search, replacement) {
    var target = this;
    if(isArray(search)){
        for(var i=0; i<search.length; i++){
            if(isArray(replacement)){
                target = target.replaceAll( search[i], replacement[i] );
            } else {
                target = target.replaceAll( search[i], replacement );
            }
        }
        return target;
    }
    return target.replace(new RegExp(search, 'g'), replacement);
};

//returns true if 2 strings are equal, case-insensitive
String.prototype.isEqual = function (str){
    if(isUndefined(str)){return false;}
    return this.toUpperCase()==str.toUpperCase();
};

//returns the left $n characters of a string
String.prototype.left = function(n) {
    return this.substring(0, n);
};

//returns true if the string starts with str
String.prototype.startswith = function(str) {
    return this.substring(0, str.length).isEqual(str);
};
String.prototype.endswith = function(str) {
    return this.right(str.length).isEqual(str);
};

//returns the right $n characters of a string
String.prototype.right = function(n) {
    return this.substring(this.length-n);
};

//gets the middle length digits starting from n
String.prototype.middle = function(n, length) {
    return this.substring(n, n+length);
};

//gets the text between left and right
String.prototype.between = function(left, right) {
    var start = this.indexOf(left);
    if(start > -1){
        start += left.length;
        var finish = this.indexOf(right, start);
        return this.substring(start, finish);
    }
};

//trims any occurences of $str off the right end of a string
String.prototype.trimright = function (str){
    var target = this;
    while(target.endswith(str) && target.length >= str.length && str.length > 0){
        target = target.left(target.length - str.length);
    }
    return target;
};

//returns if str is contained in this
String.prototype.contains = function (str){
    return this.indexOf(str) > -1;
};

String.prototype.getbetween = function (left, right){
    var subStr = this.match(left + "(.*)" + right);
    return subStr[1];
};

//gets the typename of an object
Object.prototype.getName = function() {
    var funcNameRegex = /function (.{1,})\(/;
    var results = (funcNameRegex).exec((this).constructor.toString());
    return (results && results.length > 1) ? results[1] : "";
};

function isInteger (variable) {
    return typeof variable === "number" && isFinite(variable) && variable > -9007199254740992 && variable < 9007199254740992 && Math.floor(variable) === variable;
}

//returns true if $variable appears to be a valid number
function isNumeric(variable){
    return !isNaN(Number(variable));
}

//returns true if $variable was not defined (ie: a missing optional parameter)
function isUndefined(variable){
    return typeof variable === 'undefined';
}

//returns true if $variable appears to be a valid HTML element
function isElement(variable){
    return iif(variable && variable.nodeType, true, false);
}

//returns true if $variable appears to be a valid function
function isFunction(variable) {
    var getType = {};
    return variable && getType.toString.call(variable) === '[object Function]';
}

//returns true if $variable appears to be a valid string
function isString(variable){
    return typeof variable === 'string';
}

//returns true if $variable appears to be a valid array
function isArray(variable){
    return Array.isArray(variable);
}

//returns true if $variable appears to be a valid object
//typename (optional): the $variable would also need to be of the same object type (case-sensitive)
function isObject(variable, typename){
    if (typeof variable == "object"){
        if(isUndefined(typename)) {return true;}
        return variable.getName().toLowerCase() == typename.toLowerCase();
    }
    return false;
}

//returns true if $variable appears to be a valid Selector
function isSelector(variable){
    return isArray(variable) || isObject(variable, "NodeList") || isElement(variable) || isString(variable);
}

//selects elements, then runs myFunction on them
//Selector can be a CSS selector string, an element itself, an array of elements or a nodelist
function select(Selector, myFunction){
    if(isArray(Selector) || isObject(Selector, "NodeList")){
        var Elements = Selector;
    } else if(isElement(Selector)) {
        var Elements = [Selector];
    //} else if(Selector.isEqual("body")){ var Elements = [document.body];
    } else if(isString(Selector)) {
        var Elements = document.querySelectorAll(Selector);
    } else {
        console.log("Selector not found: " + Selector);
    }
    if(!isUndefined(myFunction) && !isUndefined(Elements)) {
        for (var index = 0; index < Elements.length; index++) {
            myFunction(Elements[index], index);
        }
    }
    return Elements;
}

//returns all children of the parent selector
function children(ParentSelector, ChildSelector, myFunction){
    var allElements = new Array();
    select(ParentSelector, function(element){
        var Elements = element.querySelectorAll(ChildSelector);
        for (var index = 0; index < Elements.length; index++) {
            allElements.push(Elements[index]);
        }
    });
    if(!isUndefined(myFunction)) {return select(allElements, myFunction);}
    return allElements;
}

//filters elements by the ones that checkelement() returns true
function filter(Selector, bywhat, myFunction) {
    var elements = select(Selector);
    var out = [];
    for (var i = elements.length; i--;) {
        if (checkelement(elements[i], i, bywhat)) {
            out.unshift(elements[i]);
        }
    }
    return select(out, myFunction);
}
//checks an element for:
//:visible - is the element visible
//:even/:odd - is the index number even/odd
function checkelement(element, elementindex, bywhat){
    var ret = true;
    if(isFunction(bywhat)) {
        ret = bywhat(element);
    } else if (isString(bywhat) && bywhat.left(1) == "1"){
        bywhat = bywhat.split(' ');
        for(var i=0; i<bywhat.length; i++){
            var currentfilter = bywhat[i].toLowerCase();
            switch(currentfilter){
                case ":visible":    if(!visible(element)){ret = false;} break;
                case ":even":       if (Math.abs(elementindex % 2) == 1){ret = false;} break;
                case ":odd":        if(elementindex % 2 == 0){ret = false;} break;
            }
        }
    } else if (isSelector(bywhat)){
        //not sure how to do this!
    }
    return ret;
}







//Value: if missing, return the value. Otherwise set it.
//KeyID: 0=value (Default), 1=innerHTML, 2=outerHTML, 3=text, 4=style, 5=node value (can't be set), text=attribute
function value(Selector, Value, KeyID, ValueID){
    if(isUndefined(KeyID)){KeyID=0;}
    if(isUndefined(Value)){
        Value = new Array;
        select(Selector, function (element, index) {
            var tempvalue="";
            try {
                switch(KeyID){
                    case 0: tempvalue = element.value; break;
                    case 1: tempvalue = element.innerHTML; break;
                    case 2: tempvalue = element.outerHTML; break;
                    case 3: tempvalue = element.textContent; break;
                    case 4: tempvalue = getComputedStyle(element)[ValueID]; break;// if(isObject(element, "Element")) {
                    default:
                        if(KeyID.isEqual("checked")) {
                            tempvalue = element.checked;
                        } else {
                            tempvalue = element.getAttribute(KeyID);
                        }
                }
                Value.push(tempvalue);
            } catch (err) {
                console.log("ERROR DURING SELECT");
                console.log("ERROR:    " + err.message);
                console.log(Selector);
                console.log(element);
            }
        });
        return Value.join();
    } else {
        return select(Selector, function (element, index) {
            switch(KeyID) {
                case 0: element.value = Value;break;
                case 1: element.innerHTML = Value; break;
                case 2: element.outerHTML = Value; break;
                case 3: element.textContent = Value; break;
                case 4: element.style[ValueID] = Value; break;
                default:
                    element.setAttribute(KeyID, Value);
                    if(KeyID.isEqual("checked")){
                        element.checked = Value;//element.removeAttribute("checked");//radio buttons
                    }
            }
        });
    }
}

function checked(Selector, Value){
    return value(Selector, Value, "checked");
}

//Value: if missing, return the HTML. Otherwise set it.
function innerHTML(Selector, HTML){
    return value(Selector, HTML, 1);
}

//Value: if missing, return the text. Otherwise set it.
function text(Selector, Value){
    return value(Selector, Value, 3);
}

//Empty an element's HTML
function empty(Selector){
    innerHTML(Selector, "");
}

//Value: if missing, return the style. Otherwise set it.
function style(Selector, Key, Value){
    return value(Selector, Value, 4, Key);
}

//attempts to check if the selector is visible
//doParents (optional, assumes true): determines if all of the parent elements need to be checked
function visible(Selector, doParents){
    var ret = true;
    if(isUndefined(doParents)){doParents=true;}
    select(Selector, function(element, index){
        if(ret) {
            if(isObject(element, "HTMLDocument")){return true;}//is the document
            var visibility = style(element, "visibility").isEqual("visible");
            var display = !style(element, "display").isEqual("none") ;
            if(!visibility || !display) { ret = false; }
            if(ret){//don't check if others are false
                var parentnodes = visible(parents(element), false);
                if(!parentnodes){ret = false;}
            }
            if(!ret){return false;}
        }
    });
    return ret;
}

//finds children of the selector
function find(Selector, ChildSelector){
    var ret = new Array;
    select(Selector, function(element, index){
        var ret2 = element.querySelectorAll(ChildSelector);
        if(ret2 !== null) {ret = ret.concat(ret2);}
    });
    return ret;
}

//returns an array of all the element's parents, ending BEFORE the document itself
function parents(Selector){
    var element = select(Selector)[0];
    var ret = new Array;
    while (element.parentNode) {
        ret.push(element.parentNode);
        element = element.parentNode;
    }
    return ret;
}

//Push a function to be run when done loading the page
function doonload(myFunction){
    todoonload.push( myFunction );
    return todoonload.length;
}
window.onload = function(){
    for(var index = 0; index < todoonload.length; index++){
        todoonload[index]();
    }
};

//returns true if any selected element has the attribute
function hasattribute(Selector, Attribute){
    select(Selector, function (element, index) {
        if(element.hasAttribute(Attribute)){
            return true;
        }
    });
    return false;
}

//Value: if missing, return the Attribute. Otherwise set it.
function attr(Selector, Attribute, Value){
    return value(Selector, Value, Attribute);
}

//remove an attribute from the selector
function removeattr(Selector, Attribute){
    return select(Selector, function (element, index) {
        element.removeAttribute(Attribute);
    });
}

//adds an event listener to the elements
function addlistener(Selector, Event, myFunction){
    return select(Selector, function (element, index) {
        element.addEventListener(Event, myFunction);
    });
}

//Sets the opacity of Selector to Alpha (0-100)
function setOpacity(Selector, Alpha) {
    if(Alpha < 0){Alpha = 0;}
    if(Alpha > 100){Alpha = 100;}
    return select(Selector, function (element, index) {
        element.style.opacity = Alpha / 100;
        element.style.filter = 'alpha(opacity=' + Alpha + ')';
    });
}

//Fades elements in over the course of Delay, executes whenDone at the end
function fadeIn(Selector, Delay, whenDone){
    show(Selector);
    fade(Selector, 0, 100, 100/fadesteps, Delay/fadesteps, whenDone)
}
//Fades elements out over the course of Delay, executes whenDone at the end
function fadeOut(Selector, Delay, whenDone){
    fade(Selector, 100, 0, -100/fadesteps, Delay/fadesteps, whenDone)
}

//INTERNAL-Use fadein/fadeout instead
var fadesteps = 16;
function fade(Selector, StartingAlpha, EndingAlpha, AlphaIncrement, Delay, whenDone){
    setOpacity(Selector, StartingAlpha);
    StartingAlpha = StartingAlpha + AlphaIncrement;
    if(StartingAlpha < 0 || StartingAlpha > 100){
        if(isFunction(whenDone)) {whenDone();}
    } else {
        setTimeout( function(){
            fade(Selector, StartingAlpha, EndingAlpha, AlphaIncrement, Delay, whenDone);
        }, Delay );
    }
}

//removes the elements from the DOM
function remove(Selector){
    return select(Selector, function (element, index) {
        element.parentNode.removeChild(element);
    });
}

//if value=true, returns istrue, else returns isfalse
function iif(value, istrue, isfalse){
    if(value){return istrue;}
    if(isUndefined(isfalse)){return "";}
    return isfalse;
}

//adds a class to the elements
function addclass(Selector, theClass){
    classop(Selector, 0, theClass);
}
//removes a class from the elements
function removeclass(Selector, theClass){
    classop(Selector, 1, theClass);
}
//toggleclass a class in the elements
function toggleclass(Selector, theClass){
    classop(Selector, 2, theClass);
}
//checks if the elements contain theClass
//needsAll [Optional]: if not missing, all elements must contain the class to return true. If missing, only 1 element needs to
function containsclass(Selector, theClass, needsAll){
    classop(Selector, iif(isUndefined(needsAll), 4, 3), theClass);
}

//INTERNAL-Use addclass/removeclass/toggleclass/containsclass instead
//Operation: 0=Add class, 1=remove class, 2=toggle class, 3=returns true if any element contains class, 4=returns true if all elements contains class
function classop(Selector, Operation, theClass){
    var Ret = select(Selector, function (element, index) {
        switch(Operation){
            case 0: //add
                if (element.classList) {
                    element.classList.add(theClass);
                } else {//IE
                    element.className = element.className + " " +  theClass;
                }
                break;
            case 1: //remove
                if (element.classList) {
                    element.classList.remove(theClass);
                } else {//IE
                    element.className = element.className.replace(new RegExp('(^|\\b)' + className.split(' ').join('|') + '(\\b|$)', 'gi'), ' ');
                    //element.className = removeindex(element.className, theClass);
                }
                break;
            case 2:
                if (element.classList) {
                    element.classList.toggle(theClass);
                } else {//IE
                    var classes = element.className.split(' ');
                    var existingIndex = classes.indexOf(theClass);
                    if (existingIndex >= 0) {
                        classes.splice(existingIndex, 1);
                    }else {
                        classes.push(theClass);
                    }
                    element.className = classes.join(' ');
                }
                break;
            case 3: if (hasclass(element, theClass)){return true;} break;
            case 4: if(!hasclass(element, theClass)){return false;} break;
        }
    });
    if(Operation < 3){ return Ret;}
    return Operation == 4;
}

//checks if an element contains theClass
function hasclass(element, theClass){
    if (element.classList) {
        return element.classList.contains(className);
    }else {//IE
        return hasword(element.className, theClass) > -1;
    }
}

//checks if text contains word
//delimiter (optional, assumes " "): if arr is text, it'll split the text by the delimiter
function hasword(text, word, delimiter){
    word = word.toLowerCase();
    if(isUndefined(delimiter)){delimiter=" ";}
    if(!isArray(text)){
        text = text.split(delimiter);
    }
    for (var i = 0; i < text.length; i++) {
        if (text[i].toLowerCase() == word) {return i;}
    }
    return -1;
}

//removes cells from an array, starting from index
//count (optional, assumes 1): how many cells to remove
//delimiter (optional, assumes " "): if arr is text, it'll split the text by the delimiter
function removeindex(arr, index, count, delimiter){
    if(!isArray(arr)){
        if(isUndefined(delimiter)){delimiter=" ";}
        arr = removeindex(arr.split(delimiter), index, count, delimiter).join(delimiter);
    } else {
        if(isNaN(index)){index = hasword(arr, index);}
        if (index > -1 && index < arr.length) {
            if (isUndefined(count)) {count = 1;}
            arr.splice(index, count);
        }
    }
    return arr;
}

//finds the label for an element
function findlabel(element){
    var label = select("label[for='"+attr(element, 'id')+"']");
    if (label.length == 0) {
        label = closest(element, 'label')
    }
    return label;
}

//hide elements
function hide(Selector){
    return select(Selector, function (element, index) {
        element.style.display = 'none';
    });
}
//show elements
function show(Selector){
    return select(Selector, function (element, index) {
        element.style.display = '';
    });
}

function isTrue(Value){
    if(isUndefined(Value)){Value = false;}
    if(isArray(Value)){Value=Value[0];}
    if(isString(Value)){if(Value.isEqual("false") || Value.isEqual("0")){Value = false;}}
    return Value;
}

function setvisible(Selector, Status){
    Status = isTrue(Status);
    if(Status){
        show(Selector);
    } else {
        hide(Selector);
    }
}

//trigger all eventName events for the Selector elements
//eventName [optional, assumes 'click']: what event to trigger
//options [optional]: an object of parameters you want to pass into the event
function trigger(Selector, eventName, options) {
    if(isUndefined(eventName)){eventName = "click"};
    if (window.CustomEvent) {
        var event = new CustomEvent(eventName, options);
    } else {
        var event = document.createEvent('CustomEvent');
        event.initCustomEvent(eventName, true, true, options);
    }
    select(Selector, function (element, index) {
        element.dispatchEvent(event);
    });
}

//send a POST AJAX request
//data (OPTIONAL): object of parameters to send as the post header
//whenDone (OPTIONAL): function to run when done.
    //whenDone Parameters:
        //message: data recieved
        //status: true if successful (will check for LARAVEL errors)
//Method (OPTIONAL: assumed POST): POST or GET
//async (OPTIONAL: assumed true): is it asynchronous
function post(URL, data, whenDone, Method, async){
    var request = new XMLHttpRequest();
    if(isUndefined(async)){async=true;}
    if(isUndefined(Method)){Method="POST";}
    request.open(Method.toUpperCase(), URL, isUndefined(async));
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.onload = function() {
        var status = request.status >= 200 && request.status < 400;
        var responseText = this.responseText;
        if(!status){
            if(responseText.contains('Whoops')){
                responseText = responseText.between('<span class="exception_title">', '</span>');
                responseText = responseText.between('>', '<') + " in " + responseText.between('<a title="', '" ondblclick');
            }
        } else if(responseText.startswith('<div class="alert alert-danger" role="alert"') && responseText.contains('View error')){
            status = false;
            responseText = responseText.between('<SMALL>', '</SMALL>');
        }
        whenDone(responseText, status); //Success!
    };
    if(isUndefined(data)){data = "";} else if(!isString(data)) { data = serialize(data); }
    request.send(data);//request = null;
}

//serialize an object into a request string
serialize = function(obj, prefix) {
    var str = [];//return '?'+Object.keys(obj).reduce(function(a,k){a.push(k+'='+encodeURIComponent(obj[k]));return a},[]).join('&');
    for(var p in obj) {
        if (obj.hasOwnProperty(p)) {
            var k = prefix ? prefix + "[" + p + "]" : p, v = obj[p];
            str.push(typeof v == "object" ? serialize(v, k) : encodeURIComponent(k) + "=" + encodeURIComponent(v));
        }
    }
    return str.join("&");
};

//replace the innerHTML of the selector with the results of an AJAX query
function load(Selector, URL, data, whenDone){
    ajax(URL, data, function(message, status){
        innerHTML(Selector, message);
        if(isFunction(whenDone)){
            whenDone();
        }
    })
}


//Operation [optional]: 0=closest
function multiop(Selector1, Selector2, Operation){
    var ret = new Array;
    if(isUndefined(Operation)){Operation=0;}
    select(Selector1, function (element, index) {
        var current = false;
        switch(Operation){
            case 0: //closest
                current = getclosest(element, Selector2);
                break;
        }
        if(current) {ret.push(current);}
    });
    return ret;
}
//Finds the closest element to element, matching selector. Only works with a single element, so use closest() instead
function getclosest(element, selector) {
    var matchesFn, parent; // find vendor prefix
    ['matches','webkitMatchesSelector','mozMatchesSelector','msMatchesSelector','oMatchesSelector'].some(function(fn) {
        if (typeof document.body[fn] == 'function') {
            matchesFn = fn;
            return true;
        }
        return false;
    });
    while (element) {// traverse parents
        parent = element.parentElement;
        if (parent && parent[matchesFn](selector)) {
            return parent;
        }
        element = parent;
    }
    return false;
}

//Gets the closest elements UP the DOM from Selector1 matching Selector2
function closest(Selector1, Selector2){
    return multiop(Selector1, Selector2, 0);
}

//adds HTML to the elements without destroying the existing HTML
//position: If undefined, HTML is added to the end. Else, added to the start
function addHTML(Selector, HTML, position){
    position = isUndefined(position);
    //afterend, beforebegin
    select(Selector, function (element, index) {
        if(position){//append
            element.insertAdjacentHTML('beforeend', HTML);
        } else {
            element.insertAdjacentHTML('afterbegin', HTML);
        }
    });
}

//add HTML to the end of the innerHTML of the selector
function append(Selector, HTML){
    addHTML(Selector, HTML);
}

function loadUrl(newLocation) {
    window.location = newLocation;
    return false;
}

//changes the URL in the history/URL bar without updating the page
function ChangeUrl(Title, URL) {
    if (typeof (history.pushState) != "undefined") {
        var obj = {Page: Title, Url: URL};
        history.pushState(obj, obj.Page, obj.Url);
        return true;
    }
}


//testing api

/*
 doonload(function(){
     addlistener("#startspeech", "click", function(){
        alert( innerHTML(closest(this, "form")) );
     })
     alert( style("#thepopup", "width") );
     style("#thepopup", "color", "red");
 });


doonload(function () {
    value("#textsearch", "TEST");
    fadeout("#textsearch", 2000);
    addlistener("#textsearch", "click", function(){alert("TEST click!");});
    toggleclass("#textsearch", "TESTINGCLASS");
    innerHTML("#thepopup", "TESTING !23");
});
*/

function isRightClick(event){
    event = event || window.event;
    if ("which" in event) {
        return event.which == 3;// Gecko (Firefox), WebKit (Safari/Chrome) & Opera
    } else if ("button" in e) {
        return event.button == 2;// IE, Opera
    }
} /*nui*/ //natural user interface

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
function assimilate(ID, originalsearchstring, isPerfectlyFormed, itemname){
    if(isUndefined(isPerfectlyFormed)) {isPerfectlyFormed = false;}
    if(isUndefined(itemname)) {itemname = get_itemname(ID);}
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
    var len1=seq1.length, len2=seq2.length, i, j, dist, ic, dc, rc, last, old, column;
    if(len1==0 || len2==0 || !isString(seq1) || !isString(seq2)){return 100;}
    seq1 = seq1.toLowerCase();
    seq2 = seq2.toLowerCase();
    if(seq1 == seq2){return 0;}

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
    item.integrity = CRC32(item.name + item.price);
    if(element.hasAttribute("quantity")){item.quantity = element.getAttribute("quantity");}
    for(var i=0; i < tables.length; i++){
        item[tables[i]] = new Array();
        for(var v = 0; v < element.getAttribute(tables[i]); v++){
            item[tables[i]].push("");
        }
    }

    var items = element.getAttribute("itemcount");
    for(var i=0; i < items; i++){
        var addons = assimilateaddons(ID, element, i, DoPerfectlyFormed, element.getAttribute("itemname"));
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

    if(item.quantity == 0){item.quantity=1;}
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
function assimilateaddons(ID, element, Index, isPerfectlyFormed, itemname){
    //[0=startsearchstring, 1=searchstring, 2=toppings, 3=typos, 4=defaults, 5=quantity, 6=itemname]
    if(isUndefined(isPerfectlyFormed)){isPerfectlyFormed = false;}
    var defaults = true;
    if(isUndefined(Index)){
        var defaults = false;
        var toppings = assimilate(ID, element, isPerfectlyFormed, itemname);
    } else {
        var toppings = assimilate(ID, element.getAttribute("item" + Index), isPerfectlyFormed, itemname);
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

function getaddonscost(currentItem, addons){
    var testitem = {name: currentItem.item, size: currentItem.size};
    for(var i = 0; i < tables.length; i++) {
        testitem[tables[i]] = new Array;
    }
    for(i=0; i<addons.length; i++){
        var addon = addons[i].getbetween('<I TITLE="', '">').replaceAll("'", '"');
        var item = JSON.parse(addon);
        testitem[ item.tablename ].push(item);
    }
    testitem = addoncost(testitem);
    return testitem;
}

function addoncost(item){
    var price = 0;
    var count = 0;
    var frees = 0;
    var toppingcost = 0;
    var Size = "";
    var text = new Array();
    var Sizes = Object.keys(freetoppings);
    if(isUndefined(item.size)){item.size = "";}
    for (var i = 0; i < Sizes.length; i++) {
        if( item.name.contains(Sizes[i]) || item.size.isEqual(Sizes[i]) ){
            Size = Sizes[i];
            toppingcost = Number( freetoppings[Size] );
        }
    }
    if(toppingcost>0) {
        for (i = 0; i < tables.length; i++) {
            var tablename = tables[i];
            var tablesaddons = item[tables[i]];
            for (var v = 0; v < tablesaddons.length; v++) {
                var addons = item[tablename][v];//all addons for this table
                if(!isArray(addons)){addons = [addons];}
                for (var t = 0; t < addons.length; t++) {
                    var addon = addons[t];//{qualifier (single, double, triple), label, tablename}
                    var label = addon.label.toLocaleLowerCase().replaceAll(" ", "-");
                    var quantity = Number(attr(".addon-" + addon.tablename + "-" + addon.qualifier + "-" + label, "index"));
                    if (quantity == 0) {quantity = 0.5;}//easy counts as half
                    var isFree = inArray(freetoppings[tablename], addon.label) > -1;
                    if (label.isEqual("cheese") && quantity < 2) {isFree = true;}//half/single cheese is free
                    if(isFree){
                        frees+=quantity;
                    } else {
                        count+=quantity;
                    }
                    text.push(addon.qualifier + " " + label + " is " + iif(isFree, "free", "not free") );
                }
            }
        }
        count = Math.round(count);
        price = count * toppingcost;
    }
    return {price: price.toFixed(2), count: count, frees: Math.round(frees), pertopping: toppingcost, size: Size, summary: text.join(", ")};
}

function inArray(arr, value){
    for(var i=0; i < arr.length; i++) {
        if(value.isEqual( arr[i] )){
            return i;
        }
    }
    return -1;
}

function generatereceipt(index, totaladdons){
    var text, subtotal = 0, items = 0;
    if(isUndefined(index)){//do entire order
        if(order.length == 0){
            text = "Your order is empty";
        } else {
            text =  '<BUTTON ID="saveitems" STYLE="float:right;display:none;width:100px;height:100px;" ONCLICK="saveitem();">Save</BUTTON>' +
                '<TABLE BORDER="1"><TR><TH>Index</TH><TH>Item</TH><TH>QTY</TH><TH>Price</TH><TH>Items</TH><TH>Actions</TH></TR>';
            for(var i=0; i < order.length; i++){
                var item = order[i];
                var addons = addoncost(item);//{price, count, frees, pertopping, size};
                text += generatereceipt(i, addons);
                subtotal += (Number(item.price) + Number(addons.price)) * Number(item.quantity);
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
        var price = Number(item.price) + Number(totaladdons.price);
        text = '<TR><TD CLASS="item' + item.id + '" TITLE="' + item.integrity + '">' + index + '</TD><TD>' + item.name + '</TD><TD>' +
            '<BUTTON CLASS="minus" ONCLICK="itemdir(' + index + ', -1);">-</BUTTON><SPAN STYLE="float:right;">' + item.quantity + '<BUTTON CLASS="plus" ONCLICK="itemdir(' + index + ', 1);">+</BUTTON></SPAN></TD><TD ALIGN="right"><SPAN STYLE="float:left;">$</SPAN>' + price;
        if(item.quantity > 1){text += 'x' + item.quantity + '<HR>($' + (price * Number(item.quantity)).toFixed(2) + ')';}
        text += '</TD><TD>' + tableterm;
        var doit = false;
        for(var i=0; i < tables.length; i++){
            for(var v=0; v < item[tables[i]].length; v++){
                doit = true;
                var addons = stringifyaddons(item[tables[i]][v]);
                if(!addons){
                    addons = "<B>NO ADD-ONS SELECTED</B>";
                }
                text += '<TR><TD>' + (v+1) + '</TD><TD>' + addons + '</TD><TD CLASS="tdbtn">' +
                    '<BUTTON ONCLICK="edititem(this);" STYLE="width: 100%; height: 100%;" itemindex="' + index + '" type="' + tables[i] + '" addonindex="' + i + '">Edit</BUTTON></TD></TR>';
            }
        }
        if(doit){
            text = text.replaceAll(tableterm, '<TABLE BORDER="1" WIDTH="100%"><TR><TH WIDTH="5%">#</TH><TH>Add-ons</TH><TH WIDTH="10%">Actions</TH></TR>');
            if(totaladdons.count>0) {
                text += "<TR TITLE='" + totaladdons.summary + "'><TD COLSPAN='3' ALIGN='CENTER'>$" + price + " = $" + item.price + " + (" + totaladdons.count + " " + totaladdons.size + " " + pluralize(" addon", totaladdons.count) + " at $" + totaladdons.pertopping.toFixed(2) + " each = $" + totaladdons.price + ")</TD></TD></TR>";
            }
            text += '</TABLE>';
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


function CRC32(str){
    var CRCTable=[0x00000000,0x77073096,0xEE0E612C,0x990951BA,0x076DC419,0x706AF48F,0xE963A535,0x9E6495A3,0x0EDB8832,0x79DCB8A4,0xE0D5E91E,0x97D2D988,0x09B64C2B,0x7EB17CBD,0xE7B82D07,0x90BF1D91,0x1DB71064,0x6AB020F2,0xF3B97148,0x84BE41DE,0x1ADAD47D,0x6DDDE4EB,0xF4D4B551,0x83D385C7,0x136C9856,0x646BA8C0,0xFD62F97A,0x8A65C9EC,0x14015C4F,0x63066CD9,0xFA0F3D63,0x8D080DF5,0x3B6E20C8,0x4C69105E,0xD56041E4,0xA2677172,0x3C03E4D1,0x4B04D447,0xD20D85FD,0xA50AB56B,0x35B5A8FA,0x42B2986C,0xDBBBC9D6,0xACBCF940,0x32D86CE3,0x45DF5C75,0xDCD60DCF,0xABD13D59,0x26D930AC,0x51DE003A,0xC8D75180,0xBFD06116,0x21B4F4B5,0x56B3C423,0xCFBA9599,0xB8BDA50F,0x2802B89E,0x5F058808,0xC60CD9B2,0xB10BE924,0x2F6F7C87,0x58684C11,0xC1611DAB,0xB6662D3D,0x76DC4190,0x01DB7106,0x98D220BC,0xEFD5102A,0x71B18589,0x06B6B51F,0x9FBFE4A5,0xE8B8D433,0x7807C9A2,0x0F00F934,0x9609A88E,0xE10E9818,0x7F6A0DBB,0x086D3D2D,0x91646C97,0xE6635C01,0x6B6B51F4,0x1C6C6162,0x856530D8,0xF262004E,0x6C0695ED,0x1B01A57B,0x8208F4C1,0xF50FC457,0x65B0D9C6,0x12B7E950,0x8BBEB8EA,0xFCB9887C,0x62DD1DDF,0x15DA2D49,0x8CD37CF3,0xFBD44C65,0x4DB26158,0x3AB551CE,0xA3BC0074,0xD4BB30E2,0x4ADFA541,0x3DD895D7,0xA4D1C46D,0xD3D6F4FB,0x4369E96A,0x346ED9FC,0xAD678846,0xDA60B8D0,0x44042D73,0x33031DE5,0xAA0A4C5F,0xDD0D7CC9,0x5005713C,0x270241AA,0xBE0B1010,0xC90C2086,0x5768B525,0x206F85B3,0xB966D409,0xCE61E49F,0x5EDEF90E,0x29D9C998,0xB0D09822,0xC7D7A8B4,0x59B33D17,0x2EB40D81,0xB7BD5C3B,0xC0BA6CAD,0xEDB88320,0x9ABFB3B6,0x03B6E20C,0x74B1D29A,0xEAD54739,0x9DD277AF,0x04DB2615,0x73DC1683,0xE3630B12,0x94643B84,0x0D6D6A3E,0x7A6A5AA8,0xE40ECF0B,0x9309FF9D,0x0A00AE27,0x7D079EB1,0xF00F9344,0x8708A3D2,0x1E01F268,0x6906C2FE,0xF762575D,0x806567CB,0x196C3671,0x6E6B06E7,0xFED41B76,0x89D32BE0,0x10DA7A5A,0x67DD4ACC,0xF9B9DF6F,0x8EBEEFF9,0x17B7BE43,0x60B08ED5,0xD6D6A3E8,0xA1D1937E,0x38D8C2C4,0x4FDFF252,0xD1BB67F1,0xA6BC5767,0x3FB506DD,0x48B2364B,0xD80D2BDA,0xAF0A1B4C,0x36034AF6,0x41047A60,0xDF60EFC3,0xA867DF55,0x316E8EEF,0x4669BE79,0xCB61B38C,0xBC66831A,0x256FD2A0,0x5268E236,0xCC0C7795,0xBB0B4703,0x220216B9,0x5505262F,0xC5BA3BBE,0xB2BD0B28,0x2BB45A92,0x5CB36A04,0xC2D7FFA7,0xB5D0CF31,0x2CD99E8B,0x5BDEAE1D,0x9B64C2B0,0xEC63F226,0x756AA39C,0x026D930A,0x9C0906A9,0xEB0E363F,0x72076785,0x05005713,0x95BF4A82,0xE2B87A14,0x7BB12BAE,0x0CB61B38,0x92D28E9B,0xE5D5BE0D,0x7CDCEFB7,0x0BDBDF21,0x86D3D2D4,0xF1D4E242,0x68DDB3F8,0x1FDA836E,0x81BE16CD,0xF6B9265B,0x6FB077E1,0x18B74777,0x88085AE6,0xFF0F6A70,0x66063BCA,0x11010B5C,0x8F659EFF,0xF862AE69,0x616BFFD3,0x166CCF45,0xA00AE278,0xD70DD2EE,0x4E048354,0x3903B3C2,0xA7672661,0xD06016F7,0x4969474D,0x3E6E77DB,0xAED16A4A,0xD9D65ADC,0x40DF0B66,0x37D83BF0,0xA9BCAE53,0xDEBB9EC5,0x47B2CF7F,0x30B5FFE9,0xBDBDF21C,0xCABAC28A,0x53B39330,0x24B4A3A6,0xBAD03605,0xCDD70693,0x54DE5729,0x23D967BF,0xB3667A2E,0xC4614AB8,0x5D681B02,0x2A6F2B94,0xB40BBE37,0xC30C8EA1,0x5A05DF1B,0x2D02EF8D];
    var length = str.length;
    var result = 0xffffffff;
    for(var i=0;i<length;i++){
        result = (result>>8)^CRCTable[str[i]^(result&0x000000FF)];
    }
    return ~result;
}