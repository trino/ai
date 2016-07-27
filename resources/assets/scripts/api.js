//Mini Jquery replacement

String.prototype.replaceAll = function (search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};
Element.prototype.remove = function() {
    this.parentElement.removeChild(this);
}
NodeList.prototype.remove = HTMLCollection.prototype.remove = function() {
    for(var i = this.length - 1; i >= 0; i--) {
        if(this[i] && this[i].parentElement) {
            this[i].parentElement.removeChild(this[i]);
        }
    }
}

function isundefined(variable){
    return typeof variable === 'undefined';
}
function isElement(variable){
    return iif(variable && variable.nodeType, true, false);
}
function isFunction(functionToCheck) {
    var getType = {};
    return functionToCheck && getType.toString.call(functionToCheck) === '[object Function]';
}

function select(Selector, myFunction){
    if(Array.isArray(Selector)){
        var Elements = Selector;
    } else if(isElement(Selector)){
        var Elements = [Selector];
    } else {
        var Elements = document.querySelectorAll(Selector);
    }
    if(!isundefined(myFunction)) {
        for (var index = 0; index < Elements.length; index++) {
            myFunction(Elements[index], index);
        }
    }
    return Elements;
}

/*
$.fn.hasAttr = function(name) {
    return this.attr(name) !== undefined;
};
*/

//Value: if missing, return the value. Otherwise set it.
//KeyID: 0=value (Default), 1=innerHTML, 2=outerHTML, text=attribute
function value(Selector, Value, KeyID){
    if(isundefined(KeyID)){KeyID=0;}
    if(isundefined(Value)){
        Value = new Array;
        select(Selector, function (element, index) {
            switch(KeyID){
                case 0: Value.push(element.value); break;
                case 1: Value.push(element.innerHTML); break;
                case 2: Value.push(element.outerHTML); break;
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
                default: element.setAttribute(KeyID, Value);
            }
        });
    }
}

//Value: if missing, return the HTML. Otherwise set it.
function innerHTML(Selector, HTML){
    return value(Selector, HTML, 1);
}

//Push a function to be run when done loading the page
var todoonload = new Array;
function doonload(myFunction){
    todoonload.push( myFunction );
    return todoonload.length;
}
window.onload = function(){
    console.log("window.onload! " + todoonload.length);
    for(var index = 0; index < todoonload.length; index++){
        todoonload[index]();
    }
};

//returns true if any selected element has the attribute
function hasattribute(Selector, Attribute){
    return select(Selector, function (element, index) {
        if(element.hasAttribute(Attribute)){
            return true;
        }
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
function fadein(Selector, Delay, whenDone){
    show(Selector);
    fade(Selector, 0, 100, 100/fadesteps, Delay/fadesteps, whenDone)
}
//Fades elements out over the course of Delay, executes whenDone at the end
function fadeout(Selector, Delay, whenDone){
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
function removeelements(Selector){
    return select(Selector, function (element, index) {
        element.remove();
    });
}

//if value=true, returns istrue, else returns isfalse
function iif(value, istrue, isfalse){
    if(value){return istrue;}
    if(isundefined(isfalse)){return "";}
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
    classop(Selector, iif(isundefined(needsAll), 4, 3), theClass);
}

//INTERNAL-Use addclass/removeclass/toggleclass/containsclass instead
//Operation: 0=Add class, 1=remove class, 2=toggle class, 3=returns true if any element contains class, 4=returns true if all elements contains class
function classop(Selector, Operation, theClass){
    var Ret = select(Selector, function (element, index) {
        switch(Operation){
            case 0: element.classList.add(theClass); break;
            case 1: element.classList.remove(theClass); break;
            case 2: element.classList.toggle(theClass); break;
            case 3: if (element.classList.contains(theClass)){return true;} break;
            case 4: if(!element.classList.contains(theClass)){return false;} break;
        }
    });
    if(Operation < 3){ return Ret;}
    return Operation == 4;
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
    })
}

//send a POST AJAX request
//data: object of parameters to send as the post header
//whenDone: function to run when done.
    //whenDone Parameters:
        //message: data recieved
        //status: true if successful
function ajax(URL, data, whenDone){
    var request = new XMLHttpRequest();
    request.open('POST', URL, true);

    request.onreadystatechange = function() {
        if (this.readyState === 4) {
            if (this.status >= 200 && this.status < 400) {
                whenDone(this.responseText, true); //Success!
            } else {
                whenDone("", false); //Failure!
            }
        }
    };

    request.send(data);
    //request = null;
}


/*
//testing api
doonload(function () {
    value("#textsearch", "TEST");
    fadeout("#textsearch", 2000);
    addlistener("#textsearch", "click", function(){alert("TEST click!");});
    toggleclass("#textsearch", "TESTINGCLASS");
    innerHTML("#thepopup", "TESTING !23");
});
*/