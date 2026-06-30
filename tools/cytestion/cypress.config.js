const { defineConfig } = require('cypress');

module.exports = defineConfig({
  retries: 2,
  requestTimeout: 10000,
  defaultCommandTimeout: 5000,
  viewportWidth: 1280,
  viewportHeight: 720,
  numTestsKeptInMemory: 0,
  experimentalMemoryManagement: true,
  failOnStatusCode: false,
  video: false,
  env: {
    apiHosts: 'http://127.0.0.1:8080/',
    baseUrl: 'http://127.0.0.1:8080/',
    loginUrl: 'http://127.0.0.1:8080/',
    userLogin: 'user',
    userPassword: 'pass',
    check500: 'true',
    check400: 'true',
    decoyRequestTimeout: null,
    pendingAPICount: 0,
    errorsDetected: 0,
    window: null,
  },
  e2e: {
    setupNodeEvents(on, config) {
      require('@cypress/code-coverage/task')(on, config);
      require('./cypress/plugins/index.js')(on, config);
      return config;
    },
    baseUrl: 'http://127.0.0.1:8080/',
    specPattern: '../e2e/test//**/*.cy.{js,jsx,ts,tsx}',
  },
});
