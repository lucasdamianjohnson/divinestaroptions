/**
* Some code is copied and linked to its resportiroes. 
* @copyright  Copyright (c) 2020 Divine Star LLC (http://www.divinestarsoftware.org)
* @license    License: GPLv3 or later
* @version    Alpha: .2  
* @since      Class available since Alpha 0
*/


/**
* DocReady function. Loads when the HTML dom is ready. 
* Found here: https://github.com/jfriend00/docReady
* Thank you!
*/
(function(funcName, baseObj) {
    "use strict";
    // The public function name defaults to window.docReady
    // but you can modify the last line of this function to pass in a different object or method name
    // if you want to put them in a different namespace and those will be used instead of 
    // window.docReady(...)
    funcName = funcName || "docReady";
    baseObj = baseObj || window;
    var readyList = [];
    var readyFired = false;
    var readyEventHandlersInstalled = false;
    
    // call this when the document is ready
    // this function protects itself against being called more than once
    function ready() {
        if (!readyFired) {
            // this must be set to true before we start calling callbacks
            readyFired = true;
            for (var i = 0; i < readyList.length; i++) {
                // if a callback here happens to add new ready handlers,
                // the docReady() function will see that it already fired
                // and will schedule the callback to run right after
                // this event loop finishes so all handlers will still execute
                // in order and no new ones will be added to the readyList
                // while we are processing the list
                readyList[i].fn.call(window, readyList[i].ctx);
            }
            // allow any closures held by these functions to free
            readyList = [];
        }
    }
    
    function readyStateChange() {
        if ( document.readyState === "complete" ) {
            ready();
        }
    }
    
    // This is the one public interface
    // docReady(fn, context);
    // the context argument is optional - if present, it will be passed
    // as an argument to the callback
    baseObj[funcName] = function(callback, context) {
        if (typeof callback !== "function") {
            throw new TypeError("callback for docReady(fn) must be a function");
        }
        // if ready has already fired, then just schedule the callback
        // to fire asynchronously, but right away
        if (readyFired) {
            setTimeout(function() {callback(context);}, 1);
            return;
        } else {
            // add the function and context to the list
            readyList.push({fn: callback, ctx: context});
        }
        // if document already ready to go, schedule the ready function to run
        // IE only safe when readyState is "complete", others safe when readyState is "interactive"
        if (document.readyState === "complete" || (!document.attachEvent && document.readyState === "interactive")) {
            setTimeout(ready, 1);
        } else if (!readyEventHandlersInstalled) {
            // otherwise if we don't have event handlers installed, install them
            if (document.addEventListener) {
                // first choice is DOMContentLoaded event
                document.addEventListener("DOMContentLoaded", ready, false);
                // backup is window load event
                window.addEventListener("load", ready, false);
            } else {
                // must be IE
                document.attachEvent("onreadystatechange", readyStateChange);
                window.attachEvent("onload", ready);
            }
            readyEventHandlersInstalled = true;
        }
    }
})("docReady", window);




function hasClass(element, class_name) {
    return (' ' + element.className + ' ').indexOf(' ' + class_name+ ' ') > -1;
}

function removeClass(element,remove_class) {

  var re = new RegExp(remove_class,"g");
  element.className = element.className.replace(re, "");

}

function addClass(element,add_class) {

  classes = element.className.split(" ");
  if (classes.indexOf(add_class) == -1) {
    element.className += " " + add_class;
  }

}



function addRemoveClass(element,add_class,remove_class) {
 classes = element.className.split(" ");
  if (classes.indexOf(add_class) == -1) {
    element.className += " " + add_class;
  }

  var re = new RegExp(remove_class,"g");
  element.className = element.className.replace(re, "");
 

}


function toggleClass(element,toggle_class) { 

if (element.classList) {
  element.classList.toggle(toggle_class);
} else {
  // For IE9
  var classes = element.className.split(" ");
  var i = classes.indexOf(toggle_class);

  if (i >= 0)
    classes.splice(i, 1);
  else
    classes.push(toggle_class);
    element.className = classes.join(" ");
}

}





