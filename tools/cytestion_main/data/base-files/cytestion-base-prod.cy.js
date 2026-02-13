describe('Cytestion', () => {
  beforeEach(() => {
    cy.visit(Cypress.env('loginUrl'));
    cy.get('#username').type(Cypress.env('userLogin'));
    cy.get('#password').type(Cypress.env('userPassword'));
    cy.get('#btn-login').click();
    cy.visit('/');
    cy.waitNetworkFinished();
  });
  //--CODE--
  it(`Visits index page`, () => {
    const actualId = [`root`];
    cy.checkErrorsWereDetected();
    cy.writeContent(actualId);
  });
  //--CODE--
});
