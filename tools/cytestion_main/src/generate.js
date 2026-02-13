const util = require('./util');
const faker = require('faker-br');

const generateNewTestCodes = (code, actualFile, newCodes, catalog) => {
  actualFile.idClick.length > 0 && generateIdClick(code, actualFile.idClick, newCodes, catalog);
  actualFile.idText.length > 0 && generateTextClick(code, actualFile.idText, newCodes, catalog);
  actualFile.idsForm.length > 0 && generateIdsForm(code, actualFile.idsForm, newCodes, catalog);
};

const generateIdClick = (code, idList, newCodes, catalog) => {
  idList.forEach((elem) => {
    const url = util.extractUrl(elem);
    let newActualId = [...code.actualId, elem.id];
    const actualString = `const actualId = [${'`' + newActualId.join('`,`') + '`'}];`;
    if (util.willNotGenerateDuplicate(url, `[${elem.typeId}"${elem.id}"]`, elem.tag, catalog)) {
      let newCode = putIdClickSnippet(code.codeText, elem);
      newCode = newCode.replace(/^.*const actualId = .*$/gm, actualString);
      newActualId.shift();
      newCode = newCode.replace(util.getContentBetween(newCode, 'it(`', '`,'), `Click on element ${newActualId.join('->')}`);

      newCodes.push(newCode);
    }
  });
};

const generateTextClick = (code, textList, newCodes, catalog) => {
  textList.forEach((elem) => {
    const url = util.extractUrl(elem);
    let newActualId = [...code.actualId, elem.id];
    const actualString = `const actualId = [${'`' + newActualId.join('`,`') + '`'}];`;
    if (util.willNotGenerateDuplicate(url, elem.id, elem.tag, catalog)) {
      let newCode = putTextClickSnippet(code.codeText, elem);
      newCode = newCode.replace(/^.*const actualId = .*$/gm, actualString);
      newActualId.shift();
      newCode = newCode.replace(util.getContentBetween(newCode, 'it(`', '`,'), `Click on element ${newActualId.join('->')}`);

      newCodes.push(newCode);
    }
  });
};

const generateIdsForm = (code, idsForm, newCodes, catalog) => {
  const idsFormFiltered = idsForm.filter((elem) => util.willNotGenerateDuplicate(util.extractUrl(elem), `[${elem.typeId}"${elem.id}"]`, elem.tag, catalog));
  if (idsFormFiltered.length > 0) {
    const listOnlyId = idsFormFiltered.map((elem) => elem.id);
    let newActualId = [...code.actualId, listOnlyId.join('-')];
    const actualString = `const actualId = [${'`' + newActualId.join('`,`') + '`'}];`;
    let newCode = putIdFormSnippet(code.codeText, idsFormFiltered);
    newCode = newCode.replace(/^.*const actualId = .*$/gm, actualString);
    newActualId.shift();
    newCode = newCode.replace(util.getContentBetween(newCode, 'it(`', '`,'), `Filling values ${newActualId.join('->')} and submit`);

    newCodes.push(newCode);
  }
};

const putIdClickSnippet = (codeText, obj) => {
  const id = obj.id;
  const typeId = obj.typeId;
  const url = util.extractUrl(obj);
  const urlParent = util.extractParentUrl(obj);

  if (url !== urlParent) {
    const actualId = codeText.match(/^.*const actualId = .*$/gm);
    const newCodeText = codeText.substring(0, codeText.indexOf(actualId[0]) + actualId[0].length);

    const clickCodeWithVisit = `cy.visit('${url}');
    cy.waitNetworkFinished();
    cy.clickIfExist(\`[${typeId}"${id}"]\`);
    cy.checkErrorsWereDetected();
    cy.writeContent(actualId);
  });`;

    return newCodeText + '\n' + clickCodeWithVisit;
  } else {
    const clickCode = `cy.clickIfExist(\`[${typeId}"${id}"]\`);
    cy.checkErrorsWereDetected();\n`;
    return codeText.replace('cy.checkErrorsWereDetected();\n', clickCode);
  }
};

const putTextClickSnippet = (codeText, obj) => {
  const innerText = obj.id;
  const url = util.extractUrl(obj);
  const urlParent = util.extractParentUrl(obj);

  if (url !== urlParent) {
    const actualId = codeText.match(/^.*const actualId = .*$/gm);
    const newCodeText = codeText.substring(0, codeText.indexOf(actualId[0]) + actualId[0].length);

    const clickCodeWithVisit = `cy.visit('${url}');
    cy.waitNetworkFinished();
    cy.clickTextIfExist(\`${innerText}\`);
    cy.checkErrorsWereDetected();
    cy.writeContent(actualId);
  });`;

    return newCodeText + '\n' + clickCodeWithVisit;
  } else {
    const clickCode = `cy.clickTextIfExist(\`${innerText}\`);
      cy.checkErrorsWereDetected();\n`;
    return codeText.replace('cy.checkErrorsWereDetected();\n', clickCode);
  }
};

const putIdFormSnippet = (codeText, idsForm) => {
  let fillingCode = '';
  idsForm.forEach((elem) => (fillingCode += getCorrectTest(elem)));
  fillingCode += `cy.submitIfExist(\`.ant-form\`);\n
      cy.checkErrorsWereDetected();\n`;

  return codeText.replace('cy.checkErrorsWereDetected();\n', fillingCode);
};

const getCorrectTest = (element) => {
  const insideInput = element.insideInputText ?? '';
  if (!element.classId || element.classId === 'input') {
    return `cy.fillInput(\`[${element.typeId}"${element.id}"]${insideInput}\`, \`${faker.random.word().replace(/[^\w\s]/gi, '')}\`);\n`;
  } else if (element.classId === 'input-cpf') {
    return `cy.fillInput(\`[${element.typeId}"${element.id}"]${insideInput}\`, \`${faker.br.cpf({
      format: true,
    })}\`);\n`;
  } else if (element.classId === 'input-cnpj') {
    return `cy.fillInput(\`[${element.typeId}"${element.id}"]${insideInput}\`, \`${faker.br.cnpj({
      format: true,
    })}\`);\n`;
  } else if (element.classId === 'input-number') {
    return `cy.fillInput(\`[${element.typeId}"${element.id}"]${insideInput}\`, \`${faker.random.number({
      min: 1,
      max: 10,
    })}\`);\n`;
  } else if (element.classId === 'input-big-decimal' || element.classId === 'input-monetary') {
    return `cy.fillInput(\`[${element.typeId}"${element.id}"]${insideInput}\`, \`${faker.random
      .float({
        min: 1,
        max: 10,
      })
      .toString()
      .replace('.', ',')}\`);\n`;
  } else if (element.classId === 'input-power-search') {
    return `cy.fillInputPowerSearch(\`[${element.typeId}"${element.id}"]${insideInput}\`);\n`;
  } else if (element.classId === 'input-power-select') {
    return `cy.fillInputPowerSelect(\`[${element.typeId}"${element.id}"]${insideInput}\`);\n`;
  } else if (element.classId.includes('ant-transfer-customize-list')) {
    return `cy.fillMultipleSelection(\`[${element.typeId}"${element.id}"]${insideInput}\`);\n`;
  } else if (element.classId === 'input-select' || element.classId === 'input-select-multiple') {
    return `cy.fillInputSelect(\`[${element.typeId}"${element.id}"]${insideInput}\`);\n`;
  } else if (element.classId === 'input-checkbox' || element.classId === 'input-radio') {
    return `cy.fillInputCheckboxOrRadio(\`[${element.typeId}"${element.id}"]${insideInput}\`);\n`;
  } else if (element.classId === 'input-picker' || element.classId === 'input-month-picker' || element.classId === 'input-range-picker') {
    const type = element.classId === 'input-month-picker' ? 'month' : element.classId === 'input-range-picker' ? 'range' : 'picker';
    return `cy.fillInputDate(\`[${element.typeId}"${element.id}"]${insideInput}\`, \`${type}\`);\n`;
  } else if (element.classId === 'input-date') {
    return `cy.clickIfExist('[${element.typeId}"${element.id}"]');\n`;
  } else if (element.classId === 'input-submit') {
    return `cy.clickIfExist('[${element.typeId}"${element.id}"]');\n`;
  }
};

module.exports = {
  generateNewTestCodes,
};
