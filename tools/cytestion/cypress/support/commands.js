import { hashCode, levenshteinDistance, objectifiesTagHtml, getInnerText, handleImageLink } from './utils/utils';
import dayjs from 'dayjs';

Cypress.Commands.add('writeContent', (actualId) => {
  cy.waitForStableDOM();
  const actualIdName = actualId.join('->');
  const actualIdHashCoded = hashCode(actualIdName);
  cy.url().then((url) => {
    cy.window().then((win) => {
      cy.task('writeFile', {
        filePath: `tmp/${actualIdHashCoded}`,
        data: `$$${url}$$ ${win.document.body.outerHTML}`,
      });
    });
  });
  cy.readFile('tmp/translate.json').then((obj) => {
    obj[actualIdHashCoded] = actualIdName;
    cy.task('writeFile', {
      filePath: 'tmp/translate.json',
      data: JSON.stringify(obj),
    });
  });
});

Cypress.Commands.add('isElementInViewport', ($id) => {
  cy.window({ log: false }).then((win) => {
    const elementFromView = Cypress.dom.getElementAtPointFromViewport(win.document, $id[0].getBoundingClientRect().x, $id[0].getBoundingClientRect().y);
    if (!elementFromView) return false;
    return elementFromView.contains($id[0]) || elementFromView.parentNode === $id[0] || $id[0].contains(elementFromView);
  });
});

Cypress.Commands.add('actionabilityCheck', (tag) => {
  const $el = Cypress.$(tag);
  return (
    $el.is(':visible') &&
    !$el.is(':disabled') &&
    cy.isElementInViewport($el).then((isInView) => isInView)
  );
});

Cypress.Commands.add('clearThenType', { prevSubject: true }, (subject, text) => {
  cy.wrap(subject).clear().type(text);
});

Cypress.Commands.add('mouseOutIfExist', (element) => {
  cy.wait(200, { log: false });
  cy.get('body', { log: false }).then((body) => {
    if (body.find(element).length > 0) {
      cy.get(element).each(($el) => cy.wrap($el).trigger('mouseout', { force: true }));
    }
  });
});

Cypress.Commands.add('clickIfExist', (element) => {
  cy.waitNetworkFinished();
  cy.get('body').then((body) => {
    if (body.find(element).length > 0) {
      cy.get(element)
        .first()
        .scrollIntoView()
        .then(($id) => {
          cy.isElementInViewport($id).then((isInView) => {
            if (isInView && $id.is(':visible') && !$id.is(':disabled')) {
              handleImageLink($id);
              $id.prevObject?.length > 1 ? cy.get(element).first().click({ force: true }) : cy.wrap($id).click();
              cy.mouseOutIfExist('.ant-tooltip-open');
            }
          });
        });
    }
  });
});

Cypress.Commands.add('clickCauseExist', (element) => {
  cy.waitNetworkFinished();
  cy.get(element)
    .first()
    .scrollIntoView()
    .then(($id) => {
      cy.isElementInViewport($id).then((isInView) => {
        if (isInView && $id.is(':visible') && !$id.is(':disabled')) {
          handleImageLink($id);
          $id.prevObject?.length > 1 ? cy.get(element).first().click({ force: true }) : cy.wrap($id).click();
          cy.mouseOutIfExist('.ant-tooltip-open');
        }
      });
    });
});

Cypress.Commands.add('clickTextIfExist', (text) => {
  cy.waitNetworkFinished();
  cy.get('body', { log: false }).then(($body) => {
    const findClickable = ($el) => {
      cy.wrap($el, { log: false }).scrollIntoView({ log: false });
      cy.actionabilityCheck($el).then((actionable) => {
        if ($el.is('button, a, li, div') && actionable) {
          cy.log(`Click on element ${text}`);
          cy.wrap($el, { log: false }).click();
          cy.mouseOutIfExist('.ant-tooltip-open');
        } else {
          const $parent = $el.parent();
          if ($parent.length > 0) {
            findClickable($parent);
          }
        }
      });
    };
    if ($body.text().includes(text)) {
      cy.findTagByText(text)
        .then(($id) => {
          if ($id) {
            findClickable($id);
          }
        });
    }
  });
});

Cypress.Commands.add("findTagByText", { prevSubject: false }, (text) => {
  return cy.window({ log: false }).then((win) => {
    let allTags = [].slice.call(win.document.querySelectorAll('*'));
    allTags.reverse();
    const tagResult = allTags.find((tag) => {
      const innerTextMatches = getInnerText(tag.outerHTML) === text;
      if (!innerTextMatches) return false;
      return cy.actionabilityCheck(tag);
    });
    return cy.wrap(tagResult, { log: false });
  });
});

Cypress.Commands.add('fillInput', (element, value) => {
  cy.get('body').then((body) => {
    if (body.find(element).length > 0) {
      cy.get(element)
        .first()
        .scrollIntoView()
        .then(($id) => {
          cy.isElementInViewport($id).then((isInView) => {
            if (isInView && $id.is(':visible') && !$id.is(':disabled')) {
              cy.get(element).first().click({ force: true }).clearThenType(value);
            }
          });
        });
    }
  });
});

Cypress.Commands.add('fillInputSelect', (element) => {
  cy.get('body').then((body) => {
    if (body.find(element).length > 0) {
      cy.get(element)
        .first()
        .scrollIntoView()
        .then(($id) => {
          cy.isElementInViewport($id).then((isInView) => {
            if (isInView && !$id.is(':disabled')) {
              cy.get(element).first().trigger('click').wait(50).type('{downarrow}').type('{enter}');
            }
          });
        });
    }
  });
});

Cypress.Commands.add('fillInputPowerSelect', (element, tries = ['a', 'e', '1']) => {
  if (!Array.isArray(tries)) tries = [tries];
  const fillInputPowerSelectRecursive = (element, tries) => {
    cy.get('body').then((body) => {
      if (body.find(element).length > 0) {
        cy.get(element)
          .first()
          .scrollIntoView()
          .then(($id) => {
            cy.isElementInViewport($id).then((isInView) => {
              if (isInView && $id.is(':visible') && !$id.is(':disabled') && tries.length > 0) {
                cy.get(element).first().click({ force: true }).wait(200).clearThenType(tries[0]).wait(1000);
                cy.waitNetworkFinished();
                cy.get(element)
                  .first()
                  .click({ force: true })
                  .type('{downarrow}')
                  .type('{enter}')
                  .wait(200)
                  .invoke('attr', 'aria-activedescendant')
                  .then((value) => {
                    if (value.includes('list_-1')) fillInputPowerSelectRecursive(element, tries.splice(1, tries.length));
                  });
              }
            });
          });
      }
    });
  };
  fillInputPowerSelectRecursive(element, tries);
});

Cypress.Commands.add('fillInputDate', (element, type, dateString) => {
  const format = type === 'month' ? 'MM/YYYY' : 'DD/MM/YYYY';
  const date = dateString ? dayjs(dateString).format(format) : dayjs();
  cy.get('body').then((body) => {
    if (body.find(element).length > 0) {
      cy.get(element)
        .first()
        .scrollIntoView()
        .then(($id) => {
          cy.isElementInViewport($id).then((isInView) => {
            if (isInView && $id.is(':visible') && !$id.is(':disabled')) {
              if (type !== 'range') {
                cy.get(element).first().click({ force: true }).clear().type(date.format(format)).type('{enter}');
              } else {
                cy.get(element).first().click({ force: true }).clear().type(date.format(format)).type('{enter}');
                cy.get(element).last().click({ force: true }).clear().type(date.add('days', 1).format(format)).type('{enter}');
              }
            }
          });
        });
    }
  });
});

Cypress.Commands.add('fillInputCheckboxOrRadio', (element) => {
  cy.get('body').then((body) => {
    if (body.find(element).length > 0) {
      cy.get(element)
        .first()
        .scrollIntoView()
        .then(($id) => {
          cy.isElementInViewport($id).then((isInView) => {
            if (isInView && !$id.is(':disabled')) {
              cy.get(element).first().click({ force: true });
            }
          });
        });
    }
  });
});

Cypress.Commands.add('submitIfExist', (element) => {
  cy.get('body').then((body) => {
    if (body.find(element).length > 0) {
      cy.get(element).first().submit().wait(200);
    }
  });
});

Cypress.Commands.add('waitNetworkFinished', () => {
  cy.wait(500, { log: false });
  const checkPendingAPICount = (retryCount) => {
    return cy.wrap(null, { log: false }).then(() => {
      cy.log(`Waiting for pending API requests: ${Cypress.env('pendingAPICount')}`);
      if (Cypress.env('pendingAPICount') === 0) {
        cy.log('All requests completed!');
      } else if (retryCount >= 150) {
        cy.log('Timeout of waiting, proceeding!');
      } else {
        return cy.wait(200, { log: false }).then(() => checkPendingAPICount(retryCount + 1));
      }
    });
  };
  return checkPendingAPICount(0).then(() => {
    cy.wait(500, { log: false });
  });
});

Cypress.Commands.add('checkErrorsWereDetected', () => {
  cy.waitNetworkFinished();
  cy.get('body', { log: false }).should(() => {
    expect(Cypress.env('errorsDetected'), 'Check errors were detected').to.eq(0);
  });
  const errorMessage = Cypress.env('errorMessage');
  const check400 = Cypress.env('check400');
  const check500 = Cypress.env('check500');
  const ignoreEndPointError = Cypress.env('ignoreEndpointError');
  const listEndpointsError = ignoreEndPointError
    ? ignoreEndPointError.split(',').map((string) => {
      const [endpoint, code] = string.split(':');
      return { endpoint, code };
    })
    : null;
  let allowableCodeError = Cypress.env('allowableCodeError');
  allowableCodeError = parseInt(allowableCodeError);
  if (errorMessage) {
    const errorMessageSplitted = errorMessage.split(',');
    errorMessageSplitted.forEach((message) => {
      cy.get('body', { timeout: 500 }).should('not.contain', message);
    });
  }
  const checkRequestErrorRecursive = (aliases) => {
    if (aliases.length > 0) {
      cy.get(aliases[0]).then((alias) => {
        if (alias)
          cy.get(`${aliases[0]}.all`).each(($al) => {
            if ($al.response) {
              cy.wrap($al)
                .its('response')
                .should((response) => {
                  const { statusCode, url } = response;
                  const objEndpointCode = listEndpointsError?.find((obj) => url.endsWith(obj.endpoint) && Number(obj.code) === statusCode);
                  if (!objEndpointCode && statusCode) {
                    if (check500) {
                      expect(statusCode, 'Check requests with server error').be.lt(500);
                    }
                    if (check400) {
                      const conditionAllowableFunc = (code) => (allowableCodeError ? code === allowableCodeError : false);
                      expect(statusCode, 'Check requests with client error').to.satisfy((code) => {
                        return code < 400 || conditionAllowableFunc(code) || code >= 500;
                      });
                    }
                  }
                });
            }
          });
        checkRequestErrorRecursive(aliases.splice(1, aliases.length));
      });
    }
  };
  checkRequestErrorRecursive(['@getRequest', '@postRequest', '@putRequest', '@patchRequest', '@deleteRequest']);
});

/**
 * Function capable to get the most similar web element for the object provide.
 * Usage: object = { tag: '<button', label: 'josé', data-cy: 'cadastros e consulta' }
 * Based on: https://doi.org/10.1145/3571855
 */
Cypress.Commands.add('getSimilar', (object) => {
  const result = {};
  let similarity = 0;
  cy.window().then((win) => {
    const allTags = [].slice.call(win.document.querySelectorAll('*'));
    for (let idx = 0; idx < allTags.length; idx++) {
      const tag = allTags[idx];
      const tagString = tag.outerHTML.split('>')[0];
      const tagObject = objectifiesTagHtml(tagString);
      Object.keys(object).forEach((attr) => {
        const value = tagObject[attr];
        if (value) {
          const calcDistance = levenshteinDistance(object[attr], value);
          similarity += (10 - Math.min(calcDistance, 10)) / 10;
        }
      });
      result[idx] = similarity;
      similarity = 0;
    }
    const idxMostSimilar = Object.keys(result).reduce((a, b) => (result[a] > result[b] ? a : b));
    return cy.wrap(allTags[idxMostSimilar]);
  });
});
