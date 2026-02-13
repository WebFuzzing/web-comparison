describe('Cytestion', () => {
  beforeEach(() => {
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
