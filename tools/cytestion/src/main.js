requireUncached = (module) => {
  delete require.cache[require.resolve(module)];
  return require(module);
};
const fs = requireUncached('fs');
const { execSync } = require('child_process');
const util = require('./util');
const generate = require('./generate');
const uaes = require('./uaes');
const path_project = require('path');

const baseUrl = process.env.BASE_URL;
const e2eFolder = process.env.E2E_FOLDER || '../e2e/exploratory';
const searchDepthValue = parseInt(process.env.SEARCH_DEPTH_VALUE);
const fileTestName = 'cytestion.cy.js';

const generateCode = () => {
  const pathToTmp = '../tmp/';
  const fileTestFinal = `${e2eFolder}/${fileTestName}`;
  const fileTestTmp = `${e2eFolder}/tmp/${fileTestName}`;
  const fileCatalog = `${path_project.resolve(__dirname, pathToTmp)}/catalog.json`;

  if (!fs.existsSync(fileTestTmp)) {
    fs.mkdirSync(`${e2eFolder}/tmp/`, { recursive: true });
    const executionMode = process.env.EXECUTION_MODE;
    let rootFile;
    if (executionMode === 'production') {
      rootFile = util.getRootTestFileProd();
    } else {
      rootFile = util.getRootTestFile();
    }
    fs.writeFileSync(fileTestTmp, rootFile);
    fs.writeFileSync(`${path_project.resolve(__dirname, pathToTmp)}/translate.json`, JSON.stringify({}));
  } else {
    let catalog = {};
    if (fs.existsSync(fileCatalog)) {
      const rawCatalog = fs.readFileSync(fileCatalog);
      catalog = JSON.parse(rawCatalog);
    }

    const contentTestFile = fs.readFileSync(fileTestTmp).toString();
    const codeList = contentTestFile.split('//--CODE--');

    const header = codeList.shift();
    const footer = codeList.pop();

    let codeListProcessed = util.dataProcessor(codeList);

    let filesTmp = fs.readdirSync(path_project.resolve(__dirname, pathToTmp)).filter((file) => file !== '.gitignore');

    let filesTmpRead = readTmpFiles(filesTmp, uaes.search);
    filesTmpRead = util.filterFilesByBaseUrl(baseUrl, filesTmpRead);

    let newCodes = [];
    codeListProcessed.forEach((code) => {
      if (util.canContinue(code, filesTmpRead)) {
        const actualFile = filesTmpRead.find((file) => file.name === code.actualId.join('->'));
        if (actualFile) {
          generate.generateNewTestCodes(code, actualFile, newCodes, catalog);
        }
      }
    });

    if (newCodes.length > 0 && !(process.env.COUNT_DEPTH > searchDepthValue)) {
      //generate new tests
      util.putSkipInTests(codeListProcessed);
      let result = [];
      result.push(header);
      result.push(...codeListProcessed.map((elem) => elem.codeText));
      result.push(...newCodes);
      result.push(footer);
      fs.writeFileSync(fileTestTmp, result.join('//--CODE--'));
      fs.writeFileSync(fileCatalog, JSON.stringify(catalog, null, 2));
    } else {
      if (fs.existsSync(fileCatalog)) fs.copyFileSync(fileCatalog, `${e2eFolder}/catalog.json`);
      //clear final tests
      if (filesTmp.length > 0) {
        execSync(`rm -v ${path_project.resolve(__dirname, pathToTmp)}/*`);
      }
      let result = [];
      const codeListProcessedFiltered = util.filterAndClearCodeList(codeListProcessed);
      result.push(header);
      result.push(...codeListProcessedFiltered.map((elem) => elem.codeText));
      result.push('\n});');
      execSync(`rm -R ${e2eFolder}/tmp`);
      fs.writeFileSync(fileTestFinal, result.join(''));
      execSync(`yarn format-file ${fileTestFinal}`, { stdio: 'pipe' });
    }
  }
};

const readTmpFiles = (filesTmp, search) => {
  let generation;
  const result = [];
  const pathToTmp = '../tmp/';
  const rawdata = fs.readFileSync(`${path_project.resolve(__dirname, pathToTmp)}/translate.json`);
  const translate = JSON.parse(rawdata);

  Object.keys(translate).forEach((key) => {
    const current = (translate[key].match(/->/g) || []).length;
    if (!generation || current > generation) generation = current;
  });
  let filteredFilesTmp = filesTmp.filter((file) => file !== 'translate.json' && file !== 'catalog.json');
  filteredFilesTmp = filteredFilesTmp.filter((file) => (translate[file]?.match(/->/g) || []).length === generation);

  filteredFilesTmp.forEach((file) => {
    const testObj = {};
    testObj.name = translate[file];
    testObj.generation = generation;
    testObj.parentContent = util.getParentContent(testObj.name);
    testObj.content = fs.readFileSync(path_project.resolve(__dirname, pathToTmp) + '/' + file).toString();
    search(testObj);

    result.push(testObj);
  });

  return result;
};

generateCode();
