//Mini Jquery replacement
//get more functionality from http://youmightnotneedjquery.com/
//Siblings, Prev, Prepend, Position Relative To Viewport, Position, Parent, Outer Width With Margin, Outer Width, Outer Height With Margin, Outer Height, Offset Parent, Offset, Next, Matches Selector, matches, Find Children, Filter, Contains Selector, Contains, Clone, Children, Append

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
String.prototype.isEqual = function (str){
    return this.toUpperCase()==str.toUpperCase();
};
String.prototype.left = function(n) {
    return this.substring(0, n);
};
String.prototype.right = function(n) {
    if(n<0){n=this.length+n;}
    return this.substring(0, this.length-n);
};

Object.prototype.getName = function() {
    var funcNameRegex = /function (.{1,})\(/;
    var results = (funcNameRegex).exec((this).constructor.toString());
    return (results && results.length > 1) ? results[1] : "";
};

function isNumeric(variable){
    return !isNaN(Number(variable));
}
function isUndefined(variable){
    return typeof variable === 'undefined';
}
function isElement(variable){
    return iif(variable && variable.nodeType, true, false);
}
function isFunction(variable) {
    var getType = {};
    return variable && getType.toString.call(variable) === '[object Function]';
}
function isString(variable){
    return typeof variable === 'string';
}
function isArray(variable){
    return Array.isArray(variable);
}
function isObject(variable, typename){
    if (typeof variable == "object"){
        if(isUndefined(typename)) {return true;}
        return variable.getName().toLowerCase() == typename.toLowerCase();
    }
    return false;
}
function isSelector(variable){
    return isArray(variable) || isObject(variable, "NodeList") || isElement(variable) || isString(variable);
}

function select(Selector, myFunction){
    if(isArray(Selector) || isObject(Selector, "NodeList")){
        var Elements = Selector;
    } else if(isElement(Selector)){
        var Elements = [Selector];
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

function filter(Selector, bywhat, myFunction) {
    var elements = select(Selector);
    var out = [];
    console.log("GOT HERE  -----");

    for (var i = elements.length; i--;) {
        if (checkelement(elements[i], i, bywhat)) {
            out.unshift(elements[i]);
            console.log(elements[i]);
        }
    }

    console.log(out);
    return select(out, myFunction);
}
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
            switch(KeyID){
                case 0: Value.push(element.value); break;
                case 1: Value.push(element.innerHTML); break;
                case 2: Value.push(element.outerHTML); break;
                case 3: Value.push(element.textContent); break;
                case 4: Value.push( getComputedStyle(element)[ValueID] ); break;
                default: Value.push(element.getAttribute(KeyID));
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
                default: element.setAttribute(KeyID, Value);
            }
        });
    }
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

function style(Selector, Key, Value){
    return value(Selector, Value, 4, Key);
}

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

function find(Selector, ChildSelector){
    var ret = new Array;
    select(Selector, function(element, index){
        var ret2 = element.querySelectorAll(ChildSelector);
        if(ret2 !== null) {ret = ret.concat(ret2);}
    });
    return ret;
}

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
var todoonload = new Array;
function doonload(myFunction){
    todoonload.push( myFunction );
    return todoonload.length;
}
window.onload = function(){
    console.log("window.onload queue: " + todoonload.length + " functions");
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
            case 0://add
                if (element.classList) {
                    element.classList.add(theClass);
                } else {//IE
                    element.className = element.className + " " +  theClass;
                }
                break;
            case 1://remove
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

function hasclass(element, theClass){
    if (element.classList) {
        return element.classList.contains(className);
    }else {//IE
        return hasword(element.className, theClass) > -1;
    }
}
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

//hide elements
function hide(Selector){
    select(Selector, function (element, index) {
        element.style.display = 'none';
    });
}
//show elements
function show(Selector){
    select(Selector, function (element, index) {
        element.style.display = '';
    });
}

//trigger all eventName events for the Selector elements
//options [optional]: an object of parameters you want to pass into the event
function trigger(Selector, eventName, options) {
    if (window.CustomEvent) {
        var event = new CustomEvent(eventName, options);
    } else {
        var event = document.createEvent('CustomEvent');
        event.initCustomEvent(eventName, true, true, options);
    }
    select(Selector, function (element, index) {
        console.log(element.getAttribute("name"));
        console.log(event);
        element.dispatchEvent(event);
    });
}

//send a POST AJAX request
//data (OPTIONAL): object of parameters to send as the post header
//whenDone (OPTIONAL): function to run when done.
    //whenDone Parameters:
        //message: data recieved
        //status: true if successful
//async (OPTIONAL): if undefined, async=true, else false
function post(URL, data, whenDone, async){
    var request = new XMLHttpRequest();
    request.open('POST', URL, isUndefined(async));
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.onload = function() {
        whenDone(this.responseText, request.status >= 200 && request.status < 400); //Success!
    };
    if(isUndefined(data)){data = "";} else if(!isString(data)) { data = serialize(data); }
    request.send(data);//request = null;
}

serialize = function(obj, prefix) {
    var str = [];//return '?'+Object.keys(obj).reduce(function(a,k){a.push(k+'='+encodeURIComponent(obj[k]));return a},[]).join('&');
    for(var p in obj) {
        if (obj.hasOwnProperty(p)) {
            var k = prefix ? prefix + "[" + p + "]" : p, v = obj[p];
            str.push(typeof v == "object" ? serialize(v, k) : encodeURIComponent(k) + "=" + encodeURIComponent(v));
        }
    }
    return str.join("&");
}

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

function append(Selector, HTML){
    addHTML(Selector, HTML);
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