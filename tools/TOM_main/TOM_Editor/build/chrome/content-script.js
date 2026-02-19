"use strict";
/***** GLOBAL */
/***/
/**
 * Initialize the port with a name
 * @type {chrome.runtime.Port} connection Global variable, used to connect with the BP
 */
var connection = chrome.runtime.connect({ name: 'content-port' });
/**
 * Initialize record to false, used for automatic capture of elements
 * @type {Boolean} record Global variable, used to control the capture of elements
 */
var record = false;
/**
 * Capture the last highligthed element on the page
 * @type {HTMLElement} prevElem
 */
var prevElem = undefined;
/**
 * Method to remove the highligthed information on page
 * @type {Method}
 */
var removeSelectionOperation = undefined;
/**
 * Used to save the data that is sent to the form
 * @type {Array} formData Used in fill of forms
 */
var formData = [];
/**
 * This variable are used because is needed to open dialog or form to fill with data
 * and contains the mapping definition of element
 * @type {Message} openDial used to save information of click in modal/dialog/etc
 */
var openDialMap = undefined;
/***** METHODS */
/***/
/**
 * Get the XPath Selector from HTMLELement
 * Thanks to http://stackoverflow.com/a/5178132
 * @type {HTMLElement} el
 * @return {String} Returns the xpath of element
 */
var getXPathSelector = function (el) {
    var allNodes = document.getElementsByTagName('*');
    var segs = [];
    for (segs; el && el.nodeType === 1; el = el.parentNode) {
        if (el.hasAttribute('id')) {
            var uniqueIdCount = 0, n = 0;
            var flag = false;
            for (n; n < allNodes.length && !flag; n++) {
                if (allNodes[n].hasAttribute('id') && allNodes[n].id === el.id) {
                    uniqueIdCount++;
                }
                if (uniqueIdCount > 1) {
                    flag = true;
                }
            }
            if (uniqueIdCount === 1) {
                segs.unshift('id("' + el.getAttribute('id') + '")');
                return segs.join('/');
            }
            else {
                segs.unshift(el.localName.toLowerCase() + '[@id="' + el.getAttribute('id') + '"]');
            }
        }
        else {
            if (el.hasAttribute('class')) {
                segs.unshift(el.localName.toLowerCase() + '[@class="' + el.getAttribute('class') + '"]');
            }
            else {
                var i = 1;
                var sib = el.previousSibling;
                for (i, sib; sib; sib = sib.previousSibling) {
                    if (sib.localName === el.localName) {
                        i++;
                    }
                }
                segs.unshift(el.localName.toLowerCase() + '[' + i + ']');
            }
        }
    }
    return segs.length ? '/' + segs.join('/') : null;
};
/**
 * Get the path over CSS Selector
 * @param {HTMLElement} el
 * @return {String} Returns the CSS Selector Path
 */
var getCssSelector = function (el) {
    var names = [];
    var flag = false;
    while (el.parentNode && !flag) {
        if (el.id) {
            names.unshift('#' + el.id);
            flag = true;
        }
        else {
            if (el === el.ownerDocument.documentElement) {
                names.unshift(el.tagName);
            }
            else {
                var c = 1;
                var e = el;
                for (c, e; e.previousElementSibling; e = e.previousElementSibling, c++) {
                    ;
                }
                names.unshift(el.tagName + ':nth-child(' + c + ')');
            }
            el = el.parentNode;
        }
    }
    return names.join(' > ');
};
/**
 * Check the selector size for the class types
 * @type {HTMLSelector} selector
 * @return {Number} Returns the number of cases found with this selector
 */
var classSelectorSize = function (selector) {
    return document.getElementsByClassName(selector).length;
};
/**
 * Create a class selector based on classlist of element
 * @type {[type]}
 * @return {string} Return the class string selector
 */
// let createClassSelector = (elem): string => {
//   let classText = elem.classList.value.replace(/ /g, '.');
//   return elem.nodeName + '[class=\'' + classText + '\']';
// };
/**
 * Get the mapping data of an html element and construct the
 * message we send to application.
 * Checks if the element exists and is unique in certain modes
 * HowToFind: 0 ID // 1 cssSelector // 2 className // 3 linkText
 * 4 name // 5 tagName // 6 partialLinkText // 7 xpath
 * @param {HTMLElement} elem
 * @return {Message} Returns the mapping object
 */
var getMappingOfElement = function (elem) {
    var linkSelector, nameSelector, map = undefined;
    if (elem.localName === 'a') {
        linkSelector = elem.innerText;
        if (linkSelector && (linkSelector.charAt(0) >= 'a' && linkSelector.charAt(0) <= 'z') ||
            (linkSelector.charAt(0) >= 'A' && linkSelector.charAt(0) <= 'Z')) {
            map = { howToFind: 3, whatToFind: linkSelector, whatToDo: 0 };
        }
    }
    if (elem.localName === 'input') {
        nameSelector = elem.name;
        if (nameSelector) {
            map = { howToFind: 4, whatToFind: nameSelector, whatToDo: 3 };
        }
    }
    if (!map) {
        var idSelector = elem.id;
        if (idSelector) {
            map = { howToFind: 0, whatToFind: idSelector, whatToDo: 0 };
        }
        else {
            if (elem.classList.length === 1 && classSelectorSize(elem.classList.value) === 1) {
                // let classSelector = createClassSelector(elem);
                map = { howToFind: 2, whatToFind: elem.classList.value, whatToDo: 0 };
            }
            else {
                var cssSelector = getCssSelector(elem);
                var xpathSelector = getXPathSelector(elem);
                if (document.querySelector(cssSelector) !== undefined) {
                    map = { howToFind: 1, whatToFind: cssSelector, whatToDo: 0 };
                }
                else {
                    map = { howToFind: 7, whatToFind: xpathSelector, whatToDo: 0 };
                }
            }
        }
    }
    return map;
};
/**
 * Verifies that was necessary to click on an element to open the form
 * And push to formData the info to do that on model.
 */
var checkClickToOpenForm = function () {
    if (openDialMap) {
        var msg = { element: 1, required: true, map: openDialMap };
        formData.push(msg);
    }
    openDialMap = undefined;
};
// IT IS NOT A TRANSITION
// 1. IF INPUT / SELECT / BUTTON != SUBMIT
// 1.1 INITIALIZE VALUE DEF OF FORM
// 1.2 CHECK IF BELONGS TO THE SAME FORM
// 1.3 CHECK IF CLICKED A SUBMITED TYPE BUTTON
// selectbox need a value, the selected value
var blurEvent = function (event) {
    var elem = event.target || event.srcElement;
    var msg = undefined;
    if (elem.localName === 'textarea' || elem.localName === 'input') {
        if (elem.type !== 'checkbox') {
            // Verifies that was necessary to click on an element to open the form
            checkClickToOpenForm();
            var msgMap = getMappingOfElement(elem);
            msgMap.whatToDo = 3;
            msg = { element: 3, required: true, value: elem.value, map: msgMap };
            formData.push(msg);
            console.log('INPUT PUSH TO FORM!');
        }
    }
    if (elem.localName === 'select') {
        var msgMap = getMappingOfElement(elem);
        msgMap.whatToDo = 0;
        msg = { element: 2, required: true, value: elem.value, map: msgMap };
        formData.push(msg);
        console.log('SELECT PUSH TO FORM!');
    }
};
/**
 * Check if href data is equal to the expression
 * @type {HTMLElement} elem
 * @return {Boolean} Returns true or false
 */
var javascriptRef = function (elem) {
    return 'javascript:void(0)' === elem.href;
};
/**
 * Check if exist in the path one anchor element
 * If type = 0 (normal transition); If type = 1 (modal or dialog click)
 * @type {Array} path Existent paths after the click on the element
 * @return {Number} Return the type of this path
 */
var checkPathOfElement = function (path) {
    var i = 0, type = -1;
    while (i < 5 && type < 0) {
        var elem = path[i];
        if (elem.localName === 'a') {
            if (elem.href && elem.href.search('\#') === -1 && !javascriptRef(elem)) {
                if (elem.dataset && elem.dataset.method && elem.dataset.method === 'delete') {
                    type = 2; // AJAX REQUEST IN HTML ANCHOR HTML (DELETE)
                }
                else {
                    type = 0; // normal transition to other page
                }
            }
            else {
                type = 1; // modal, dialog
            }
        }
        i++;
    }
    return type;
};
/**
 * Check is the click on the web page is a transition for other page
 * If the click dont go for other page, check it is a click to open a modal or dialog
 * @type {HTMLEvent} event The event of the click in the page
 * @type {HTMLElement} elem The element it has clicked
 * @return {Message} The mapping of element
 */
var checkIsTransition = function (event, elem) {
    var elemMapping = undefined;
    var transitionType = checkPathOfElement(event.path);
    switch (transitionType) {
        case 0:
            elemMapping = getMappingOfElement(elem);
            break;
        case 1:
            openDialMap = getMappingOfElement(elem);
            break;
        case 2: break;
        default:
            console.log('ERROR! CLICK NOT RECOGNIZED!');
            break;
    }
    return elemMapping;
};
/**
 * Checks is the click is made on button
 * @type {HTMLElement} elem the clicked element
 */
var isClickOnButton = function (elem) {
    if (elem.type === 'submit') {
        var transMap = getMappingOfElement(elem);
        transMap.whatToDo = 4;
        var label = elem.form.id;
        var data = { label: label, values: formData, transition: { map: transMap } };
        var msg = { type: 5, data: data, response: 4, source: 0 };
        connection.postMessage(msg);
        formData = [];
        console.log('FORMULARIO ENVIADO!');
    }
    else {
        var transMap = getMappingOfElement(elem);
        transMap.whatToDo = 0;
        var msg = { element: 1, required: true, value: '', map: transMap };
        formData.push(msg);
        console.log('BUTTON CLICK', msg);
    }
};
/**
 * Checks if the click is in a checkbox
 * @type {HTMLElement} elem the clicked element
 */
var isClickOnCheckbox = function (elem) {
    if (elem.type === 'checkbox') {
        var msgMap = getMappingOfElement(elem);
        msgMap.whatToDo = 0;
        var msg = { element: 0, required: true, value: '', map: msgMap };
        formData.push(msg);
    }
};
/**
 * Handler to check if it is a valid click on web page, like TRANSITION
 * SUBMITION FORM, click on checkbox
 *
 * TYPE OR RESPONDE: 0 InitialApp // 1 InitialContent // 2 Domain // 3 Select
 * // 4 Record // 5 Response // 6 CancelOperation
 * @type {HTMLEvent} event The event after the click
 */
var clickEvent = function (event) {
    var elem = event.target || event.srcElement;
    var transitionMappingData = checkIsTransition(event, elem);
    if (transitionMappingData) {
        var str = '';
        if (elem.innerText) {
            str = elem.innerText.trim();
        }
        var trans = { label: str, map: transitionMappingData };
        connection.postMessage({ type: 5, data: trans, response: 4, source: 2 });
        openDialMap = undefined;
        formData = [];
    }
    if (elem.localName === 'button') {
        isClickOnButton(elem);
    }
    if (elem.localName === 'input') {
        isClickOnCheckbox(elem);
    }
};
/**
 * Set the variable record with new information from background page
 * @type {Boolean} recordInfo
 */
var setRecordData = function (recordInfo) {
    record = recordInfo;
};
/**
 * Set the capture mode of the web page.
 * If param false disable all captures listeners, if true activate all the listeners and empty the data of forms
 * @type {boolean} recordInfo The information about the capture mode.
 */
var setRecordEvent = function (recordInfo) {
    if (recordInfo) {
        formData = [];
        openDialMap = undefined;
        document.addEventListener('click', clickEvent, false);
        document.addEventListener('blur', blurEvent, true);
    }
    else {
        document.removeEventListener('click', clickEvent, false);
        document.removeEventListener('blur', blurEvent, true);
    }
};
/**
 * Removes the styles caused by the mouse selector (blue box) after been disabled
 * @type {HTMLElement} elem The element with styles
 */
var removeStyles = function (elem) {
    elem.classList.remove('mouseOn');
    elem.removeAttribute('style');
};
/**
 * Send a message with selector data of an element for an activate component in app
 * type = 5 Response: the type of message
 * response = 3 Select: because is selector
 * @type {Mapping} data mapping definition
 */
var sendsSelector = function (data) {
    console.log('SEND RESPONSE: ', data);
    connection.postMessage({ type: 5, data: data, response: 3 });
    removeSelectionOperation();
};
/**
 * Listener to select a method on DOM
 * @type {HTMLEvent} event The mouse event
 */
var getClickedElement = function (event) {
    var elem = event.target || event.srcElement;
    elem.classList.remove('mouseOn');
    var mappingMsg = getMappingOfElement(elem);
    if (mappingMsg !== undefined) {
        sendsSelector(mappingMsg);
        setRecordEvent(record);
    }
    event.preventDefault();
    event.stopPropagation();
};
/**
 * Hightlight a element on DOM with a blue box
 * Updated for the location of the mouse
 * @type {HTMLEvent} event The mouse event
 */
var highlightElement = function (event) {
    var elem = event.target || event.srcElement;
    if (!elem.isSameNode(document.body)) {
        if (prevElem) {
            removeStyles(prevElem);
        }
        elem.classList.add('mouseOn');
        elem.setAttribute('style', 'background-color: #bcd5eb !important; outline: 2px solid #5166bb !important;');
        prevElem = elem;
    }
};
/**
 * Disable the event associated with select element on DOM
 * Remove listeners and set the capture if enabled
 */
removeSelectionOperation = function () {
    document.body.removeEventListener('mousemove', highlightElement);
    document.removeEventListener('click', getClickedElement, true);
    if (prevElem) {
        removeStyles(prevElem);
    }
    if (record) {
        setRecordEvent(true);
    }
};
/**
 * Activate the listeners (highlightElement, getClickedElement) to select a
 * HTML element on document.
 * set record to false, because double events if active.
 */
var getSelectedElement = function () {
    setRecordEvent(false);
    document.body.addEventListener('mousemove', highlightElement);
    document.addEventListener('click', getClickedElement, true);
};
/**
 * Get the domain of the web app
 * and send that to the background page.
 */
var getDomain = function () {
    var domain = document.location.hostname;
    connection.postMessage({ type: 5, data: domain, response: 2 });
};
/**
 * Listener to receive the menssages from BP
 * Handler to delegate the information
 * @type {Message} msg The message data
 */
connection.onMessage.addListener(function (msg, port) {
    switch (msg.type) {
        case 2:
            getDomain();
            break;
        case 3:
            getSelectedElement();
            break;
        case 4:
            setRecordData(msg.data.enabled);
            setRecordEvent(msg.data.enabled);
            break;
        case 6:
            removeSelectionOperation();
            break;
        default:
            console.log('Error: communication type not recognized!');
            break;
    }
});
/**
 * Listener to receveid opening connection from background page
 * Short connection, to guarantee the connection between this layers
 */
chrome.runtime.onMessage.addListener(function (request, sender, sendResponse) {
    if (request.type === 'OpenPort') {
        connection.postMessage({ type: 1 });
        getDomain();
    }
    return true;
});

//# sourceMappingURL=content-script.js.map
