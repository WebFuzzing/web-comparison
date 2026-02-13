import { CyHttpMessages } from 'cypress/types/net-stubbing';

function increaseAPICountOnRequestStart() {
  const previousValue = Cypress.env('pendingAPICount');
  Cypress.env('pendingAPICount', previousValue + 1);
}

function decreaseAPICountOnRequestFinish() {
  if (Cypress.env('decoyRequestTimeout')) {
    clearTimeout(Cypress.env('decoyRequestTimeout'));
    Cypress.env('decoyRequestTimeout', undefined);
    subtractPendingAPICount();
  }
  const delayTime = 500;
  if (Cypress.env('pendingAPICount') === 1) {
    setTimeout(() => {
      subtractPendingAPICount();
    }, delayTime);
  } else {
    subtractPendingAPICount();
  }
}

function subtractPendingAPICount() {
  if (Cypress.env('pendingAPICount') > 0) {
    const previousValue = Cypress.env('pendingAPICount');
    Cypress.env('pendingAPICount', previousValue - 1);
  }
}

function routeHandlerWithRealResponse(request: CyHttpMessages.IncomingHttpRequest) {
  increaseAPICountOnRequestStart();
  request.on('response', (response) => {
    handlerResponseWithFile(response);
    decreaseAPICountOnRequestFinish();
  });
}

function handlerResponseWithFile({ headers }) {
  const includesAttachment = headers['content-disposition']?.includes('attachment');
  const includesOctetStream = headers['content-type']?.includes('application/octet-stream');
  if (includesAttachment || includesOctetStream) {
    setTimeout(function () {
      Cypress.env('window').document.location?.reload();
    }, 5000);
  }
}

export function plantWaitUntilSomeRequestOccurs() {
  Cypress.env('pendingAPICount', 1);
  Cypress.env(
    'decoyRequestTimeout',
    setTimeout(() => {
      Cypress.env('decoyRequestTimeout', undefined);
      subtractPendingAPICount();
    }, 5000)
  );
}

export function setupCypressInterception() {
  cy.window().then((window) => {
    Cypress.env('decoyRequestTimeout', undefined);
    Cypress.env('pendingAPICount', 0);
    Cypress.env('errorsDetected', 0);
    Cypress.env('window', window);
    startMonitorAPI();
  });
}

function startMonitorAPI() {
  const apiHosts = Cypress.env('apiHosts').split(',');
  apiHosts.forEach((pattern: string) => {
    const apiRegex = new RegExp(pattern.trim());
    cy.intercept('GET', apiRegex, routeHandlerWithRealResponse).as('getRequest');
    cy.intercept('POST', apiRegex, routeHandlerWithRealResponse).as('postRequest');
    cy.intercept('PUT', apiRegex, routeHandlerWithRealResponse).as('putRequest');
    cy.intercept('PATCH', apiRegex, routeHandlerWithRealResponse).as('patchRequest');
    cy.intercept('DELETE', apiRegex, routeHandlerWithRealResponse).as('deleteRequest');
  });
}
