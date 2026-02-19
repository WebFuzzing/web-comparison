var appPort;
var contPort;
var recordStatusMsg;
var tabID;
/**
 * message to connect with content script
 * @type {[type]}
 */
var contentConnection = function (tabId) {
    chrome.tabs.query({ active: true, currentWindow: true }, function (tabs) {
        chrome.tabs.sendMessage(tabId, { type: 'OpenPort' }, undefined);
    });
};
/**
 * the initial setup
 */
var initalSetup = function (port, msg) {
    switch (msg.type) {
        // MessageType.InitialContent
        // IF MENSAGEM DE ATIVAÇÂO DO CONTENT SCRIPT COM O BP
        case 1:
            contPort = port;
            break;
        // MessageType.InitialApp
        // VOLTAR A REATIVAR A LIGAÇÃO ENTRE A APP E O CS
        default:
            appPort = port;
            recordStatusMsg = undefined;
            tabID = msg.tabId;
            contentConnection(msg.tabId);
            break;
    }
};
/**
 * Send to content script the last status of record option,
 * normally used after the reconnection.
 * @type {chrome.runtime.port} port Activate port of content script
 */
var sendStatusOfContent = function (port) {
    if (recordStatusMsg !== undefined) {
        port.postMessage(recordStatusMsg);
    }
};
/**
 * Check the message the type of message is equal for Record Type
 * and change the value of recordStatusMsg
 * @type {Message} msg Message
 */
var changeRecordStatus = function (msg) {
    if (msg.type === 4) {
        recordStatusMsg = msg;
    }
};
chrome.runtime.onConnect.addListener(function (port) {
    var listener = function (msg, sender) {
        console.log('01 - LISTENER BP -', msg, sender);
        // IF INITIAL MESSAGE OF CONNECTION, ESTABELISH THE CONNECTION BETWEEN
        // THE APP AND THE CONTENT SCRIPT
        if (msg.type === 0 || msg.type === 1 || !appPort || !contPort) {
            initalSetup(port, msg);
            sendStatusOfContent(contPort); // if reconnect
        }
        else {
            if (port === appPort) {
                contPort.postMessage(msg);
                changeRecordStatus(msg);
            }
            else {
                appPort.postMessage(msg);
            }
        }
    };
    /**
     * check if the communication between BP and content script are changed,
     * and reconnect.
     * @type {[type]}
     */
    var disconnectListener = function () {
        console.log('The communication was interrupted!');
        if (port.name === 'content-port') {
            contPort = undefined;
            setTimeout(contentConnection(tabID), 1000 * 1);
        }
        // appPort = undefined;
    };
    port.onMessage.addListener(listener);
    port.onDisconnect.addListener(disconnectListener);
});

//# sourceMappingURL=background.js.map
