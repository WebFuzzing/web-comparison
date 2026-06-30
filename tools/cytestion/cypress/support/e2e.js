import './commands';
import './test-setup';
import './custom-commands';
import 'cypress-failed-log';
import '@testing-library/cypress/add-commands';
import 'cypress-wait-until';
import { plantWaitUntilSomeRequestOccurs } from './utils/auto-stub';
import { registerCommand } from 'cypress-wait-for-stable-dom';
import '@cypress/code-coverage/support';

const mutationObserver = {
  subtree: true,
  childList: true,
  attributes: true,
  attributeOldValue: true,
  characterData: true,
  characterDataOldValue: true,
};
registerCommand({ pollInterval: 1000, timeout: 10000, mutationObserver });

const resizeObserverLoopErrRe = /^[^(ResizeObserver loop limit exceeded)]/;
const uncaughtError = /Uncaught: /;
const typeErrorError = /TypeError: /;

Cypress.on('uncaught:exception', (err) => {
  /* returning false here prevents Cypress from failing the test */
  if (resizeObserverLoopErrRe.test(err.message)) {
    return false;
  }
});

Cypress.on('window:before:load', (win) => {
  cy.stub(win.console, 'error')
    .callsFake((msg) => {
      if (uncaughtError.test(msg) || typeErrorError.test(msg)) {
        const previousValue = Cypress.env('errorsDetected');
        Cypress.env('errorsDetected', previousValue + 1);
        throw new Error(msg);
      }
    })
    .as('consoleError');
  cy.stub(win, 'open')
    .callsFake((url) => {
      win.open.wrappedMethod.call(win, url, '_self');
    })
    .as('windowOpen');
  cy.stub(win, 'print')
    .callsFake((url) => {
      win.open.wrappedMethod.call(win, url, '_self');
    })
    .as('windowPrint');
});

Cypress.on('url:changed', (newUrl) => {
  plantWaitUntilSomeRequestOccurs();
  console.log('newUrl', newUrl);
});
