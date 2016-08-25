/* Generated at 1472138014 */ /*nui*/  var tables=["toppings","wings_sauce"];var synonyms=[  ["jalapenos","jalapeno","jalapeño","jalapeños","jalape?o"], ["green peppers"], ["red peppers"], ["black olives","kalamata olives"], ["sun dried tomatoes","sun dried tomatoes","sundried tomatoes","sun dried tomatos","sun dried tomatos","sundried tomatos"], ["tomatoes","tomatos"], ["pepperoni","pepperonis"], ["red onions"], ["extra large","x-large"], ["anchovies","anchovy"], ["2","two"], ["cooked","done"]];var qualifiers=[  ["quarter"], ["half","less","easy"], ["single","regular","normal"], ["double","extra","more"], ["triple","three"], ["quadruple","four"]];var qualifier_tables=[];var quantityselect=0;function cloneData(data){var jsonString=JSON.stringify(data);return JSON.parse(jsonString);}String.prototype.containswords=function(words){var text=this.toLowerCase().split(" ");var count=[];var index;if(isArray(words)){for(var i=0;i<words.length;i++){index=text.indexOf(words[i].toLowerCase());if(index>-1){count.push(index);}}}else{index=text.indexOf(words.toLowerCase());if(index>-1){count.push(index);}}return count;};function getwordsbetween(text,leftword,rightword){text=text.toLowerCase().split(" ");var start =leftword+1;var finish=text.length;if(!isUndefined(rightword)){finish=rightword;}return text.slice(start,finish).join(" ");}function replaceAll(Source,Find,ReplaceWith){Find=Find.replaceAll("[?]","[?]");return Source.replaceAll(Find,ReplaceWith);}function replacesynonyms(searchstring,thesynonyms,includenotfounds,returnArray){if(isUndefined(thesynonyms)){thesynonyms=synonyms;}if(isUndefined(includenotfounds)){includenotfounds=true;}if(isUndefined(returnArray)){returnArray=false;}if(isArray(searchstring)){searchstring=searchstring.join(" ");}searchstring=searchstring.trim().toLowerCase().replaceAll("-"," ").split(" ");for(var searchstringindex=searchstring.length-1;searchstringindex>=0;searchstringindex--){var wasfound=false;for(var synonymparentindex=0;synonymparentindex<thesynonyms.length;synonymparentindex++){for(var synonymchildindex=0;synonymchildindex<thesynonyms[synonymparentindex].length;synonymchildindex++){if(!wasfound){var synonym=thesynonyms[synonymparentindex][synonymchildindex].split(" ");wasfound=arraycompare(searchstring,searchstringindex,synonym);if(wasfound){searchstring[searchstringindex]=thesynonyms[synonymparentindex][0];if(synonym.length>1){searchstring.splice(searchstringindex+1,synonym.length-1);}}}}}}if(!includenotfounds){for(var searchstringindex=0;searchstringindex<searchstring.length;searchstringindex++){var wasfound=false;for(var synonymparentindex=0;synonymparentindex<thesynonyms.length;synonymparentindex++){var synonym=thesynonyms[synonymparentindex][0].split(" ");wasfound=arraycompare(searchstring,searchstringindex,synonym);if(wasfound){synonymparentindex=thesynonyms.length;}}if(!wasfound){searchstring[searchstringindex]=false;}}removeempties(searchstring);}if(returnArray){return searchstring;}return searchstring.join(" ").trim();}function removemultiples(text,texttoremove,replacewith){while(text.indexOf(texttoremove)>-1){text=text.replaceAll(texttoremove,replacewith);}return text.trim();}function removewords(text,words){if(isUndefined(words)){words=wordstoignore;}text=text.toLowerCase().split(" ");for(var i=text.length-1;i>-1;i--){if(words.indexOf(text[i])>-1){removeindex(text,i);}}text=removemultiples(text.join(" "),"  "," ");return text;}function get_quantity(searchstring,itemname){if(!isArray(searchstring)){searchstring=searchstring.split(" ")}for(var searchindex=0;searchindex<searchstring.length;searchindex++){if(isNumeric(searchstring[searchindex])){if(itemname.indexOf(searchstring[searchindex])==-1){return searchindex;}}}return -1;}function get_toppings(originalsearchstring,thesearchstring){var searchstring=replacesynonyms(thesearchstring,synonyms,true,true);var ret=[];var labelindex;var tablename;select(".tr-addon",function(element){var Found=-1;var label=enum_labels(element);var qualifier;for(var searchindex=0;searchindex<searchstring.length;searchindex++){if(searchstring[searchindex]){if(label.indexOf(searchstring[searchindex])>-1){tablename=attr(element,"table");Found=searchindex;qualifier=getqualifier(originalsearchstring,searchstring[searchindex]);if(needsRemoving){searchstring[searchindex-1]=false;}searchstring[searchindex]=false;searchindex=searchstring.length;}}}if(Found>-1){ret.push({searchindex:Found,qualifier:qualifier,label:label,needsRemoving:needsRemoving,tablename:tablename});}});return ret;}function enum_labels(element){if(isUndefined(element)){qualifier_tables=[];var labels=[];select(".tr-addon",function(element){labels.push(enum_labels(element));});return labels;}var label=attr(element,"name");if(!hasattribute(element,"normalized")){attr(element,"normalized",replacesynonyms(label));}qualifier_tables.push(attr(element,"table"));return attr(element,"normalized");}function removeempties(array){for(var searchindex=array.length-1;searchindex>-1;searchindex--){if(!array[searchindex]){removeindex(array,searchindex,1);}}}function get_typos(itemname,originalsearchstring,thesearchstring,labels){var ret=[];if(isUndefined(labels)){labels=enum_labels();}if(!isArray(thesearchstring)){var searchstring=thesearchstring.split(" ")}else{var searchstring=cloneData(thesearchstring);}for(var searchindex=searchstring.length-1;searchindex>-1;searchindex--){if(findsynonym(searchstring[searchindex],qualifiers)[0]==-1){if(itemname.indexOf(searchstring[searchindex])==-1){var closestword=findclosestsynonym(searchstring[searchindex],1,labels);if(!isUndefined(closestword.word)){closestword.word=closestword.word.replaceAll(" ","-");if(closestword.word&&labels.indexOf(closestword.word)>-1){var qualifier=getqualifier(originalsearchstring,searchstring[searchindex],closestword.word);ret.push({searchindex:searchindex,qualifier:qualifier,label:closestword.word,needsRemoving:needsRemoving,originalword:searchstring[searchindex],distance:closestword.distance,tablename:qualifier_tables[closestword.parent],parent:closestword.parent,child:closestword.child});searchstring[searchindex]=false;}}}}}return ret;}function qualifytoppings(toppings,searchstring,ID){if(isArray(toppings)){for(var i=toppings.length - 1;i>-1;i--){qualifytopping("",toppings[i].qualifier,toppings[i].label);if(toppings[i].needsRemoving){searchstring[toppings[i].searchindex - 1]=false;}searchstring[toppings[i].searchindex]=false;}removeempties(searchstring);}else if(isNumeric(toppings)){if(toppings>-1){quantityselect=searchstring[toppings];if(select("#select"+ID+" option[id='"+searchstring[toppings]+"']").length>0){value("#select"+ID,searchstring[toppings]);searchstring[toppings]=false;}return quantityselect;}}return searchstring;}function get_itemname(ID){return replacesynonyms(text("#itemtitle"+ID));}function assimilate(ID,originalsearchstring){var defaults=clearaddons();originalsearchstring=removewords(originalsearchstring);var startsearchstring=replacesynonyms(originalsearchstring);var searchstring=startsearchstring.split(" ");var itemname=get_itemname(ID);var searchindex=get_quantity(searchstring,itemname);var quantity=qualifytoppings(searchindex,searchstring,ID);var toppings=get_toppings(originalsearchstring,searchstring);searchstring=qualifytoppings(toppings,searchstring);var typos=get_typos(itemname,originalsearchstring,searchstring);qualifytoppings(typos,cloneData(searchstring));defaults=removeduplicatetoppings(defaults,toppings);defaults=removeduplicatetoppings(defaults,typos);return [startsearchstring,searchstring,toppings,typos,defaults,quantity,itemname];}function removeduplicatetoppings(filterthis,leavethis){for(var i=0;i<leavethis.length;i++){for(var v=filterthis.length-1;v>-1;v--){if(filterthis[v].tablename.isEqual(leavethis[i].tablename)&&filterthis[v].label.isEqual(leavethis[i].label)){removeindex(filterthis,v);v=-1;}}}return filterthis;}function qualifytopping(table,qualifier,topping){if(qualifier){qualifier=qualifier.replaceAll(" ","-").toLowerCase();}if(!table){for(var i=0;i<tables.length;i++){qualifytopping(tables[i],qualifier,topping);}}else if(visible(".addons-"+table,false)){if(!qualifier){qualifier="single";}var element=select(".tr-addon-"+table+"[normalized='"+topping+"']");attr(element,"SELECTED",qualifier);attr(children(element,"input[value='"+qualifier+"']"),"checked",true);}}function findclosestsynonym(keyword,cutoff,thesynonyms){keyword=keyword.toLowerCase();var ret="",parentID=-1,childID=-1;for(var synonymparentindex=0;synonymparentindex<synonyms.length;synonymparentindex++){for(var synonymchildindex=0;synonymchildindex<synonyms[synonymparentindex].length;synonymchildindex++){var value=levenshteinWeighted(keyword,synonyms[synonymparentindex][synonymchildindex]);if(value==0){return{distance:0,word:synonyms[synonymparentindex][synonymchildindex],parent:synonymparentindex,child:synonymchildindex};}else if(value<cutoff){cutoff=value;ret=synonyms[synonymparentindex][0];parentID=synonymparentindex;childID=synonymchildindex;}}}for(synonymparentindex=0;synonymparentindex<thesynonyms.length;synonymparentindex++){var value=levenshteinWeighted(keyword,thesynonyms[synonymparentindex]);if(value==0){return thesynonyms[synonymparentindex];}else if(value<cutoff){cutoff=value;ret=thesynonyms[synonymparentindex];parentID=synonymparentindex;}}return{distance:cutoff,word:ret,parent:parentID,child:childID};}function findsynonym(keyword,thesynonyms){if(isUndefined(thesynonyms)){thesynonyms=synonyms;}keyword=keyword.toLowerCase();for(var synonymparentindex=0;synonymparentindex<thesynonyms.length;synonymparentindex++){for(var synonymchildindex=0;synonymchildindex<thesynonyms[synonymparentindex].length;synonymchildindex++){if(thesynonyms[synonymparentindex][synonymchildindex]==keyword){return [synonymparentindex,synonymchildindex];}}}return [-1,-1];}var needsRemoving=false;function getqualifier(startsearchstring,keyword,toppingword){needsRemoving=false;keyword=replacesynonyms(keyword);if(isUndefined(toppingword)){toppingword=keyword;}var synonymindex=findsynonym(keyword);if(synonymindex[0]>-1){keyword=synonyms[synonymindex[0]][0].replaceAll(" ","-");startsearchstring=startsearchstring.replaceAll(synonyms[synonymindex[0]][synonymindex[1]],keyword);}startsearchstring=startsearchstring.split(" ");var wordID=startsearchstring.indexOf(keyword);if(wordID>0){var qualifier=startsearchstring[wordID-1].toLowerCase();var qualiferdistance=1;var qualiferName="";for(var i=0;i<qualifiers.length;i++){var qualifierKey=qualifiers[i][0];var qualifierValue=gettoppingqualifier("",qualifierKey,toppingword);var currentweight=levenshteinWeighted(qualifier,qualifierValue);if(qualifier&&qualifierValue&&!qualifierKey.isEqual(qualifierValue)){var found=qualifierValue.toLowerCase().indexOf(qualifier)>-1;console.log("Checking if "+qualifier+" matches "+qualifierValue+"("+found +")");if(found){needsRemoving=true;return qualifierKey;}}else if(currentweight<qualiferdistance){qualiferName=qualifierKey;qualiferdistance=currentweight;}}if(qualiferName){qualifier=qualiferName;}var qualifierValue=replacesynonyms(qualifier,qualifiers,false);if(qualifierValue){needsRemoving=true;return qualifierValue;}}return "single";}function gettoppingqualifier(table,qualifier,topping){if(table){var classname=".addon-"+table+"-"+qualifier+"-"+topping.toLowerCase().replaceAll(" ","-");return text(closest(classname,"label"));}for(var i=0;i<tables.length;i++){var thetext=gettoppingqualifier(tables[i],qualifier,topping);if(thetext){return thetext;}}return false;}function levenshteinWeighted(seq1,seq2){var len1=seq1.length,len2=seq2.length;var i,j,dist,ic,dc,rc,last,old,column;var weighter={insert: function(c){return 1.0;}, delete: function(c){return 0.5;}, replace: function(c,d){return 0.3;}};if(len1==0||len2==0){dist=0;while(len1){dist+=weighter.delete(seq1[--len1]);}while(len2){dist+=weighter.insert(seq2[--len2]);}return dist;}column=[];column[0]=0;for(j=1;j<=len2;++j){column[j]=column[j - 1]+weighter.insert(seq2[j - 1]);}for(i=1;i<=len1;++i){last=column[0];column[0]+=weighter.delete(seq1[i - 1]);for(j=1;j<=len2;++j){old=column[j];if(seq1[i - 1]==seq2[j - 1]){column[j]=last;}else{ic=column[j - 1]+weighter.insert(seq2[j - 1]);dc=column[j]+weighter.delete(seq1[i - 1]);rc=last+weighter.replace(seq1[i - 1],seq2[j - 1]);column[j]=ic<dc ? ic:(dc<rc ? dc:rc);}last=old;}}dist=column[len2];return dist;}function getaddons(table,astext){if(!table){var addons=[];for(var i=0;i<tables.length;i++){addons.push(getaddons(tables[i],true));}return addons.join(",").trim().trimright(",");}if(isUndefined(astext)){astext=false;}var qualifiers=[];var toppingIDs=[];var toppingNames=[];var tablenames=[];select(".tr-addon-"+table,function(element){var Selected=attr(element,"SELECTED");if(Selected){if(astext){Selected=gettoppingqualifier(table,Selected,attr(element,"name"));qualifiers.push(Selected+" "+attr(element,"name"));}else{qualifiers.push(Selected);toppingIDs.push(attr(element,"TOPPINGID"));toppingNames.push(attr(element,"name"));tablenames.push(attr(element,"table"));}}});if(astext){return qualifiers.join(",");}return [qualifiers,toppingIDs,toppingNames,tablenames];}function clearaddons(table){if(isUndefined(table)){var defaults=[];for(var i=0;i<tables.length;i++){clearaddons(tables[i]);}defaults.push(clicktopping("toppings","single","cheese"));defaults.push(clicktopping("toppings","single","italian tomato sauce"));defaults.push(clicktopping("toppings","regular","cooked"));return defaults;}else{value(".quantityselect","1");table="-"+table;attr(".tr-addon"+table,"SELECTED","");attr(".addon"+table,"checked",false);}}function clicktopping(table,qualifier,topping){var classname=".addon-"+table+"-"+qualifier.toLowerCase()+"-"+topping.toLowerCase().replaceAll(" ","-");trigger(classname,"click");attr(classname,"checked",true);return{qualifier:qualifier,label:topping,needsRemoving:false,tablename:table};}function arraycompare(arr,startingindex,comparewith){if(!isArray(comparewith)){comparewith=comparewith.split(" ");}for(var i=0;i<comparewith.length;i++){if(i+startingindex>arr.length - 1){return false;}if(!arr[i+startingindex].isEqual(comparewith[i])){return false;}}return true;} /*api*/   var debugmode=true;Date.now=function(verbose){if(isUndefined(verbose)){return new Date().getTime();}return new Date().toJSON();};String.prototype.replaceAll=function(search,replacement){var target=this;if(isArray(search)){for(var i=0;i<search.length;i++){if(isArray(replacement)){target=target.replaceAll(search[i],replacement[i]);}else{target=target.replaceAll(search[i],replacement);}}return target;}return target.replace(new RegExp(search,'g'),replacement);};String.prototype.isEqual=function(str){return this.toUpperCase()==str.toUpperCase();};String.prototype.left=function(n){return this.substring(0,n);};String.prototype.startswith=function(str){return this.substring(0,str.length).isEqual(str);};String.prototype.endswith=function(str){return this.right(str.length).isEqual(str);};String.prototype.right=function(n){return this.substring(this.length-n);};String.prototype.middle=function(n,length){return this.substring(n,n+length);};String.prototype.between=function(left,right){var start=this.indexOf(left);if(start>-1){start+=left.length;var finish=this.indexOf(right,start);return this.substring(start,finish);}};String.prototype.trimright=function(str){var target=this;while(target.endswith(str)&&target.length>=str.length&&str.length>0){target=target.left(target.length - str.length);}return target;};String.prototype.contains=function(str){return this.indexOf(str)>-1;};Object.prototype.getName=function(){var funcNameRegex=/function(.{1,})\(/;var results=(funcNameRegex).exec((this).constructor.toString());return(results&&results.length>1)? results[1]:"";};function isNumeric(variable){return !isNaN(Number(variable));}function isUndefined(variable){return typeof variable==='undefined';}function isElement(variable){return iif(variable&&variable.nodeType,true,false);}function isFunction(variable){var getType={};return variable&&getType.toString.call(variable)==='[object Function]';}function isString(variable){return typeof variable==='string';}function isArray(variable){return Array.isArray(variable);}function isObject(variable,typename){if(typeof variable=="object"){if(isUndefined(typename)){return true;}return variable.getName().toLowerCase()==typename.toLowerCase();}return false;}function isSelector(variable){return isArray(variable)||isObject(variable,"NodeList")||isElement(variable)||isString(variable);}function select(Selector,myFunction){if(isArray(Selector)||isObject(Selector,"NodeList")){var Elements=Selector;}else if(isElement(Selector)){var Elements=[Selector];}else if(isString(Selector)){var Elements=document.querySelectorAll(Selector);}else{console.log("Selector not found:"+Selector);}if(!isUndefined(myFunction)&&!isUndefined(Elements)){for(var index=0;index<Elements.length;index++){myFunction(Elements[index],index);}}return Elements;}function children(ParentSelector,ChildSelector,myFunction){var allElements=[];select(ParentSelector,function(element){var Elements=element.querySelectorAll(ChildSelector);for(var index=0;index<Elements.length;index++){allElements.push(Elements[index]);}});if(!isUndefined(myFunction)){return select(allElements,myFunction);}return allElements;}function filter(Selector,bywhat,myFunction){var elements=select(Selector);var out=[];for(var i=elements.length;i--;){if(checkelement(elements[i],i,bywhat)){out.unshift(elements[i]);}}return select(out,myFunction);}function checkelement(element,elementindex,bywhat){var ret=true;if(isFunction(bywhat)){ret=bywhat(element);}else if(isString(bywhat)&&bywhat.left(1)=="1"){bywhat=bywhat.split(' ');for(var i=0;i<bywhat.length;i++){var currentfilter=bywhat[i].toLowerCase();switch(currentfilter){case ":visible": if(!visible(element)){ret=false;}break;case ":even": if(Math.abs(elementindex % 2)==1){ret=false;}break;case ":odd": if(elementindex % 2==0){ret=false;}break;}}}else if(isSelector(bywhat)){}return ret;}function value(Selector,Value,KeyID,ValueID){if(isUndefined(KeyID)){KeyID=0;}if(isUndefined(Value)){Value=[];select(Selector,function(element,index){switch(KeyID){case 0:Value.push(element.value);break;case 1:Value.push(element.innerHTML);break;case 2:Value.push(element.outerHTML);break;case 3:Value.push(element.textContent);break;case 4:Value.push(getComputedStyle(element)[ValueID]);break;default: if(KeyID.isEqual("checked")){Value.push(element.checked);}else{Value.push(element.getAttribute(KeyID));}}});return Value.join();}else{return select(Selector,function(element,index){switch(KeyID){case 0:element.value=Value;break;case 1:element.innerHTML=Value;break;case 2:element.outerHTML=Value;break;case 3:element.textContent=Value;break;case 4:element.style[ValueID]=Value;break;default: element.setAttribute(KeyID,Value);if(KeyID.isEqual("checked")){element.checked=Value;}}});}}function checked(Selector,Value){return value(Selector,Value,"checked");}function innerHTML(Selector,HTML){return value(Selector,HTML,1);}function text(Selector,Value){return value(Selector,Value,3);}function empty(Selector){innerHTML(Selector,"");}function style(Selector,Key,Value){return value(Selector,Value,4,Key);}function visible(Selector,doParents){var ret=true;if(isUndefined(doParents)){doParents=true;}select(Selector,function(element,index){if(ret){if(isObject(element,"HTMLDocument")){return true;}var visibility=style(element,"visibility").isEqual("visible");var display=!style(element,"display").isEqual("none");if(!visibility||!display){ret=false;}if(ret){var parentnodes=visible(parents(element),false);if(!parentnodes){ret=false;}}if(!ret){return false;}}});return ret;}function find(Selector,ChildSelector){var ret=[];select(Selector,function(element,index){var ret2=element.querySelectorAll(ChildSelector);if(ret2 !==null){ret=ret.concat(ret2);}});return ret;}function parents(Selector){var element=select(Selector)[0];var ret=[];while(element.parentNode){ret.push(element.parentNode);element=element.parentNode;}return ret;}var todoonload=[];function doonload(myFunction){todoonload.push(myFunction);return todoonload.length;}window.onload=function(){for(var index=0;index<todoonload.length;index++){todoonload[index]();}};function hasattribute(Selector,Attribute){select(Selector,function(element,index){if(element.hasAttribute(Attribute)){return true;}});return false;}function attr(Selector,Attribute,Value){return value(Selector,Value,Attribute);}function removeattr(Selector,Attribute){return select(Selector,function(element,index){element.removeAttribute(Attribute);});}function addlistener(Selector,Event,myFunction){return select(Selector,function(element,index){element.addEventListener(Event,myFunction);});}function setOpacity(Selector,Alpha){if(Alpha<0){Alpha=0;}if(Alpha>100){Alpha=100;}return select(Selector,function(element,index){element.style.opacity=Alpha / 100;element.style.filter='alpha(opacity='+Alpha+')';});}function fadeIn(Selector,Delay,whenDone){show(Selector);fade(Selector,0,100,100/fadesteps,Delay/fadesteps,whenDone)}function fadeOut(Selector,Delay,whenDone){fade(Selector,100,0,-100/fadesteps,Delay/fadesteps,whenDone)}var fadesteps=16;function fade(Selector,StartingAlpha,EndingAlpha,AlphaIncrement,Delay,whenDone){setOpacity(Selector,StartingAlpha);StartingAlpha=StartingAlpha+AlphaIncrement;if(StartingAlpha<0||StartingAlpha>100){if(isFunction(whenDone)){whenDone();}}else{setTimeout(function(){fade(Selector,StartingAlpha,EndingAlpha,AlphaIncrement,Delay,whenDone);},Delay);}}function remove(Selector){return select(Selector,function(element,index){element.parentNode.removeChild(element);});}function iif(value,istrue,isfalse){if(value){return istrue;}if(isUndefined(isfalse)){return "";}return isfalse;}function addclass(Selector,theClass){classop(Selector,0,theClass);}function removeclass(Selector,theClass){classop(Selector,1,theClass);}function toggleclass(Selector,theClass){classop(Selector,2,theClass);}function containsclass(Selector,theClass,needsAll){classop(Selector,iif(isUndefined(needsAll),4,3),theClass);}function classop(Selector,Operation,theClass){var Ret=select(Selector,function(element,index){switch(Operation){case 0: if(element.classList){element.classList.add(theClass);}else{element.className=element.className+" "+ theClass;}break;case 1: if(element.classList){element.classList.remove(theClass);}else{element.className=element.className.replace(new RegExp('(^|\\b)'+className.split(' ').join('|')+'(\\b|$)','gi'),' ');}break;case 2: if(element.classList){element.classList.toggle(theClass);}else{var classes=element.className.split(' ');var existingIndex=classes.indexOf(theClass);if(existingIndex>=0){classes.splice(existingIndex,1);}else{classes.push(theClass);}element.className=classes.join(' ');}break;case 3:if(hasclass(element,theClass)){return true;}break;case 4:if(!hasclass(element,theClass)){return false;}break;}});if(Operation<3){return Ret;}return Operation==4;}function hasclass(element,theClass){if(element.classList){return element.classList.contains(className);}else{return hasword(element.className,theClass)>-1;}}function hasword(text,word,delimiter){word=word.toLowerCase();if(isUndefined(delimiter)){delimiter=" ";}if(!isArray(text)){text=text.split(delimiter);}for(var i=0;i<text.length;i++){if(text[i].toLowerCase()==word){return i;}}return -1;}function removeindex(arr,index,count,delimiter){if(!isArray(arr)){if(isUndefined(delimiter)){delimiter=" ";}arr=removeindex(arr.split(delimiter),index,count,delimiter).join(delimiter);}else{if(isNaN(index)){index=hasword(arr,index);}if(index>-1&&index<arr.length){if(isUndefined(count)){count=1;}arr.splice(index,count);}}return arr;}function findlabel(element){var label=select("label[for='"+attr(element,'id')+"']");if(label.length==0){label=closest(element,'label')}return label;}function hide(Selector){return select(Selector,function(element,index){element.style.display='none';});}function show(Selector){return select(Selector,function(element,index){element.style.display='';});}function isTrue(Value){if(isUndefined(Value)){Value=false;}if(isArray(Value)){Value=Value[0];}if(isString(Value)){if(Value.isEqual("false")||Value.isEqual("0")){Value=false;}}return Value;}function setvisible(Selector,Status){Status=isTrue(Status);if(Status){show(Selector);}else{hide(Selector);}}function trigger(Selector,eventName,options){if(isUndefined(eventName)){eventName="click"};if(window.CustomEvent){var event=new CustomEvent(eventName,options);}else{var event=document.createEvent('CustomEvent');event.initCustomEvent(eventName,true,true,options);}select(Selector,function(element,index){element.dispatchEvent(event);});}function post(URL,data,whenDone,async){var request=new XMLHttpRequest();request.open('POST',URL,isUndefined(async));request.setRequestHeader("Content-type","application/x-www-form-urlencoded");request.onload=function(){var status=request.status>=200&&request.status<400;var responseText=this.responseText;if(!status){if(responseText.contains('Whoops,looks like something went wrong')){responseText=responseText.between('<span class="exception_title">','</span>');responseText=responseText.between('>','<')+" in "+responseText.between('<a title="','" ondblclick');}}else if(responseText.startswith('<div class="alert alert-danger" role="alert"')&&responseText.contains('View error')){status=false;responseText=responseText.between('<SMALL>','</SMALL>');}whenDone(responseText,status);};if(isUndefined(data)){data="";}else if(!isString(data)){data=serialize(data);}request.send(data);}serialize=function(obj,prefix){var str=[];for(var p in obj){if(obj.hasOwnProperty(p)){var k=prefix ? prefix+"["+p+"]":p,v=obj[p];str.push(typeof v=="object" ? serialize(v,k):encodeURIComponent(k)+"="+encodeURIComponent(v));}}return str.join("&");};function load(Selector,URL,data,whenDone){ajax(URL,data,function(message,status){innerHTML(Selector,message);if(isFunction(whenDone)){whenDone();}})}function multiop(Selector1,Selector2,Operation){var ret=[];if(isUndefined(Operation)){Operation=0;}select(Selector1,function(element,index){var current=false;switch(Operation){case 0: current=getclosest(element,Selector2);break;}if(current){ret.push(current);}});return ret;}function getclosest(element,selector){var matchesFn,parent;['matches','webkitMatchesSelector','mozMatchesSelector','msMatchesSelector','oMatchesSelector'].some(function(fn){if(typeof document.body[fn]=='function'){matchesFn=fn;return true;}return false;});while(element){parent=element.parentElement;if(parent&&parent[matchesFn](selector)){return parent;}element=parent;}return false;}function closest(Selector1,Selector2){return multiop(Selector1,Selector2,0);}function addHTML(Selector,HTML,position){position=isUndefined(position);select(Selector,function(element,index){if(position){element.insertAdjacentHTML('beforeend',HTML);}else{element.insertAdjacentHTML('afterbegin',HTML);}});}function append(Selector,HTML){addHTML(Selector,HTML);}function loadUrl(newLocation){window.location=newLocation;return false;}function ChangeUrl(Title,URL){if(typeof(history.pushState)!="undefined"){var obj={Page:Title,Url:URL};history.pushState(obj,obj.Page,obj.Url);return true;}} /*receipt*/ var order=[];var surcharge=3.50;var lastquantity=0;function orderitem(element){var ID=element.getAttribute("value");var item={id:ID,name:element.getAttribute("itemname"),price:element.getAttribute("price"),typeid:element.getAttribute("typeid"),type:element.getAttribute("type"),quantity:1};if(element.hasAttribute("quantity")){item.quantity=element.getAttribute("quantity");}for(var i=0;i<tables.length;i++){item[tables[i]]=[];for(var v=0;v<element.getAttribute(tables[i]);v++){item[tables[i]].push("");}}var items=element.getAttribute("itemcount");for(var i=0;i<items;i++){var addons=assimilateaddons(ID,element,i);if(lastquantity>1){item.quantity=lastquantity;}for(var v=0;v<tables.length;v++){if(item[tables[v]].length>i){item[tables[v]][i]=filteraddons(addons,tables[v]);}}}if(items==1){for(var v=0;v<tables.length;v++){for(var i=1;i<item[tables[v]].length;i++){item[tables[v]][i]=cloneData(item[tables[v]][0]);}}}order.push(item);generatereceipt();}function filteraddons(addons,tablename){var ret=[];for(var i=0;i<addons.length;i++){if(tablename.isEqual(addons[i].tablename)){ret.push(addons[i]);}}return ret;}var assimilate_enabled=true;function assimilateaddons(ID,element,Index){var defaults=true;if(isUndefined(Index)){var defaults=false;var toppings=assimilate(ID,element);}else{var toppings=assimilate(ID,element.getAttribute("item"+Index));}lastquantity=toppings[5];if(defaults){return toppings[2].concat(toppings[3]).concat(toppings[4]);}return toppings[2].concat(toppings[3]);}function makerow(Label,Price,Extra,newcol){if(isUndefined(Extra)){Extra="";}if(isUndefined(newcol)){newcol="";}if(newcol){newcol='<TD COLSPAN="2" ROWSPAN="4" ALIGN="CENTER"><BUTTON ONCLICK="clearorder();">Clear</BUTTON><P><BUTTON>Checkout button goes here</BUTTON></TD>'}return '<TR><TD COLSPAN="2">'+Extra+'</TD><TD>'+Label+'</TD><TD ALIGN="right"><SPAN STYLE="float:left;">$</SPAN>'+Price.toFixed(2)+'</TD>'+newcol+'</TR>';}function clearorder(){if(confirm("Are you sure you want to erase your entire order?")){order=[];generatereceipt();}}function generatereceipt(index){var text,subtotal=0,items=0;if(isUndefined(index)){if(order.length==0){text="Your order is empty";}else{text= '<BUTTON ID="saveitems" STYLE="float:right;display:none;width:100px;height:100px;" ONCLICK="saveitem();">Save</BUTTON>'+ '<TABLE BORDER="1"><TR><TH>Index</TH><TH>Item</TH><TH>QTY</TH><TH>Price</TH><TH>Items</TH><TH>Actions</TH></TR>';for(var i=0;i<order.length;i++){var item=order[i];text+=generatereceipt(i);subtotal+=Number(item.price)* Number(item.quantity);items+=Number(item.quantity);}text+=makerow("Subtotal",subtotal,items+pluralize(" item",items),true);subtotal+=surcharge;text+=makerow("Surcharge",surcharge,"THIS IS AN EXAMPLE");text+=makerow("Tax(13%)",subtotal*0.13)+makerow("Total",subtotal*1.13)+'</TABLE>';}innerHTML("#receipt",text);}else{var item=order[index];var tableterm="123TABLE123";text='<TR><TD CLASS="item'+item.id+'">'+index+'</TD><TD>'+item.name+'</TD><TD>'+ '<BUTTON CLASS="minus" ONCLICK="itemdir('+index+',-1);">-</BUTTON><SPAN STYLE="float:right;">'+item.quantity+'<BUTTON CLASS="plus" ONCLICK="itemdir('+index+',1);">+</BUTTON></SPAN></TD><TD ALIGN="right"><SPAN STYLE="float:left;">$</SPAN>'+item.price;if(item.quantity>1){text+='x'+item.quantity+'<HR>('+Number(item.price * item.quantity).toFixed(2)+')';}text+='</TD><TD>'+tableterm;var doit=false;for(var i=0;i<tables.length;i++){for(var v=0;v<item[tables[i]].length;v++){doit=true;var addons=stringifyaddons(item[tables[i]][v]);if(!addons){addons="<B>NO ADD-ONS SELECTED</B>";}text+='<TR><TD>'+(v+1)+'</TD><TD>'+addons+'</TD><TD CLASS="tdbtn">'+ '<BUTTON ONCLICK="edititem(this);" STYLE="width:100%;height:100%;" itemindex="'+index+'" type="'+tables[i]+'" addonindex="'+i+'">Edit</BUTTON></TD></TR>';}}if(doit){text=text.replaceAll(tableterm,'<TABLE BORDER="1" WIDTH="100%"><TR><TH WIDTH="5%">#</TH><TH>Add-ons</TH><TH WIDTH="10%">Actions</TH></TR>')+'</TABLE>';}else{text=text.replaceAll(tableterm,"");}text+='</TD><TD CLASS="tdbtn"><BUTTON ONCLICK="deleteitem('+index+');" STYLE="height:100%">Delete</BUTTON></TD></TR>';}return text;}function pluralize(text,qty,append){if(isUndefined(append)){append="s";}if(qty==1){return text;}return text+append;}function itemdir(index,value){var item=order[index];item.quantity=item.quantity+value;if(item.quantity==0){deleteitem(index);}generatereceipt();}function stringifyaddons(addons){var text="";for(var i=0;i<addons.length;i++){if(i>0){text+=", ";}text+='<I TITLE="'+JSON.stringify(addons[i]).replaceAll('"',"'")+'">'+addons[i].qualifier+"</I> "+addons[i].label;}return text;}function deleteitem(index){removeindex(order,index,1);generatereceipt();}var selecteditem;function edititem(element){show("#saveitems");clearaddons();var table=element.getAttribute("type");selecteditem={itemindex:element.getAttribute("itemindex"),type:table,addonindex:element.getAttribute("addonindex")};show(".addons-"+table);var item=order[selecteditem.itemindex];var addons=item[selecteditem.type][selecteditem.addonindex];for(var i=0;i<addons.length;i++){qualifytopping(table,addons[i].qualifier,addons[i].label);}}function saveitem(ID,element,index){hide("#saveitems");var addons=getaddonslikeassimilate(selecteditem.type);order[ selecteditem.itemindex].quantity=quantityselect;order[ selecteditem.itemindex ][ selecteditem.type ][ selecteditem.addonindex ]=addons;generatereceipt();}function getaddonslikeassimilate(table){var addons=getaddons(table);var ret=[];for(var i=0;i<addons[0].length;i++){ret.push({qualifier:addons[0][i],label:addons[2][i]});}return ret;}doonload(function(){if(select("#receipt").length==0){append("body",'<DIV ID="receipt" CLASS="red"></DIV>');}generatereceipt();});