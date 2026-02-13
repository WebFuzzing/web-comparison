/// <reference types="cypress" />

declare namespace Cypress {
  interface Cypress {
    env(key: 'apiHosts'): string;
    env(key: 'baseUrl'): string;
    env(key: 'loginUrl'): string;
    env(key: 'userLogin'): string;
    env(key: 'userPassword'): string;
    env(key: 'errorMessage'): string;
    env(key: 'check500'): string;
    env(key: 'check400'): string;
    env(key: 'allowableCodeError'): string;
    env(key: 'decoyRequestTimeout'): any;
    env(key: 'pendingAPICount'): number;
    env(key: 'errorsDetected'): number;
    env(key: 'window'): any;
  }
}
