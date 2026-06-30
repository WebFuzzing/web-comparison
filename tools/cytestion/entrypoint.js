require('dotenv').config();
const fs = require('fs');
const spawn = require('cross-spawn');
const { execSync } = require('child_process');
const pjson = require('./package.json');
const executionMode = process.env.EXECUTION_MODE;
const nodeEnv = process.env.NODE_ENV;
const browser = process.env.BROWSER;
const baseUrl = process.env.BASE_URL;
const baseUrlApi = process.env.BASE_URL_API;
const loginUrl = process.env.LOGIN_URL;
const userLogin = process.env.USER_LOGIN;
const userPassword = process.env.USER_PASSWORD;
const keycloakUrl = process.env.KEYCLOAK_URL;
const clientSecret = process.env.CLIENT_SECRET;

const e2eFolder = process.env.E2E_FOLDER || '../e2e/exploratory';
const fixturesFolder = process.env.FIXTURES_FOLDER;

const errorMessage = process.env.ERROR_MESSAGE;
const check500 = process.env.CHECK_500;
const check400 = process.env.CHECK_400;
const allowableCodeError = process.env.ALLOWABLE_CODE_ERROR;
const ignoreEndpointError = process.env.IGNORE_ENDPOINT_ERROR;
const threads = parseInt(process.env.THREADS) || 2;
const searchDepthValue = process.env.SEARCH_DEPTH_VALUE && parseInt(process.env.SEARCH_DEPTH_VALUE);
const env_blacklist =
  process.env.BANNED_KEYWORDS &&
  process.env.BANNED_KEYWORDS.replace('\n', '')
    .split(',')
    .map((e) => e.trim());
let currentStage;

/**
 * Stage 1 - Verifying existence of cypress config file and set environment variables.
 *
 * Create cypress config file if it does not exist.
 */
const verifyOrCreateDirectoryAndUtilFiles = () => {
  if (!checkCorrectStage(1)) return;

  const configFileExists = fs.existsSync('cypress.config.js') && fs.readFileSync('cypress.config.js').toString().includes('baseUrl');
  const createConfigFile = () => {
    const defineConfig = {
      retries: 2,
      requestTimeout: 10000,
      defaultCommandTimeout: 5000,
      viewportWidth: 1280,
      viewportHeight: 720,
      numTestsKeptInMemory: 0,
      experimentalMemoryManagement: true,
      failOnStatusCode: false,
      video: false,
    };
    defineConfig.env = {
      apiHosts: baseUrlApi,
      baseUrl: baseUrl,
    };
    if (fixturesFolder) {
      defineConfig.fixturesFolder = fixturesFolder;
      defineConfig.env.fixturesFolder = fixturesFolder;
    }
    if (loginUrl && userLogin && userPassword) {
      defineConfig.env = {
        ...defineConfig.env,
        loginUrl: loginUrl,
        userLogin: userLogin,
        userPassword: userPassword,
      };
    }
    if (keycloakUrl && clientSecret && userLogin && userPassword) {
      defineConfig.env = {
        ...defineConfig.env,
        keycloakUrl: keycloakUrl,
        clientSecret: clientSecret,
        userLogin: userLogin,
        userPassword: userPassword,
      };
    }
    if (errorMessage) defineConfig.env.errorMessage = errorMessage;
    if (check500) defineConfig.env.check500 = check500;
    if (check400) defineConfig.env.check400 = check400;
    if (allowableCodeError) defineConfig.env.allowableCodeError = allowableCodeError;
    if (ignoreEndpointError) defineConfig.env.ignoreEndpointError = ignoreEndpointError;

    defineConfig.env = {
      ...defineConfig.env,
      decoyRequestTimeout: null,
      pendingAPICount: 0,
      errorsDetected: 0,
      window: null,
    };

    defineConfig.e2e = {
      setupNodeEvents(on, config) {
        require('@cypress/code-coverage/task')(on, config);
        require('./cypress/plugins/index.js')(on, config);
        return config;
      },
      baseUrl: baseUrl,
      specPattern: `${e2eFolder}/**/*.cy.{js,jsx,ts,tsx}`,
    };
    const importPlugin = `e2e:{\nsetupNodeEvents(on, config) {\nrequire('@cypress/code-coverage/task')(on, config);\nrequire('./cypress/plugins/index.js')(on, config);\nreturn config;\n},`;
    const configFile = `const { defineConfig } = require('cypress');\n\nmodule.exports = defineConfig(${JSON.stringify(defineConfig).replace('"e2e":{', importPlugin)});`;
    fs.writeFileSync('cypress.config.js', configFile);
    execSync(`yarn format-file cypress.config.js`, { stdio: 'pipe' });

    if (nodeEnv !== 'setup') goToNextStage();
  };

  if (baseUrl && baseUrlApi && e2eFolder) {
    createConfigFile();
  } else if (!configFileExists) {
    console.log('Environment variables have not been provided. Furthermore, cypress.config.js file does not exist or does not contain baseUrl property. Finishing the execution.');
    process.exit(1);
  } else if (nodeEnv !== 'setup') {
    goToNextStage();
  }
  process.exit(0);
};

/**
 * Stage 2 - Checks if the tests have already been generated, inform it will be replaced.
 *
 * Check existence of file with exploratory tests and clear tmp directory.
 */
const checkTestsAlreadyGenerate = () => {
  if (!checkCorrectStage(2)) return;

  const nycDir = '.nyc_output';
  const coverageDir = 'coverage';
  const logsDir = 'cypress/logs';
  const fileTestName = 'cytestion.cy.js';
  const fileTest = `${e2eFolder}/${fileTestName}`;
  const fileTestTmp = `${e2eFolder}/tmp/${fileTestName}`;
  const filesTmp = fs.readdirSync('tmp/').filter((file) => file !== '.gitignore');

  if (fs.existsSync(fileTestTmp)) execSync(`rm -R ${e2eFolder}/tmp`);
  if (filesTmp.length > 0) execSync(`rm -v tmp/*`);
  if (fs.existsSync(nycDir)) execSync(`rm -R ${nycDir}`);
  if (fs.existsSync(coverageDir)) execSync(`rm -R ${coverageDir}`);
  if (fs.existsSync(logsDir)) execSync(`rm -R ${logsDir}`);
  if (fs.existsSync(fileTest) && nodeEnv === 'generation') infoPrettyPrint('Important! File with exploratory tests was found. After this execution it will be replaced.');

  goToNextStage();
};

/**
 * Stage 3 - Generating tests
 *
 * Exec yarn command for tests generation
 */
const generateTests = () => {
  if (!checkCorrectStage(3)) return;

  let status = 0;
  const fileTestName = 'cytestion.cy.js';
  const fileTest = `${e2eFolder}/tmp/${fileTestName}`;
  const options = ['test-file-parallel', '-t', threads, '-d', `${e2eFolder}/tmp/batches/**/*.cy.js`, '--browser'];
  if (browser) {
    options.push(browser);
  } else {
    options.push('firefox');
  }
  const execParallelTest = () => {
    const fileTestString = fs.readFileSync(fileTest).toString();
    let tests = fileTestString.split('  it');
    const totalTests = tests.length - 1;
    tests = tests.filter((test) => !test.includes('.skip('));
    const header = tests.shift();

    infoPrettyPrint(`Total test cases: ${totalTests}`);
    infoPrettyPrint(`Total of new test cases: ${tests.length}`);

    let qtdFiles = 1;
    let testsPerFile = [];
    const maxTestsPerFile = 50;
    const qtdPerThread = Math.ceil(tests.length / threads);
    tests.forEach((test, index) => {
      const qtdTests = index + 1;
      testsPerFile.push(test);

      if (testsPerFile.length === maxTestsPerFile || testsPerFile.length === qtdPerThread || qtdTests === tests.length) {
        testsPerFile.unshift(header);
        let content = testsPerFile.join('  it');
        if (!(qtdTests === tests.length)) content += '\n});';

        execSync(`mkdir -p ${e2eFolder}/tmp/batches`);
        const filePath = `${e2eFolder}/tmp/batches/cytestion_${qtdFiles++}.cy.js`;
        fs.writeFileSync(filePath, content);
        infoPrettyPrint(`Batch file created: ${filePath.split('/').pop()}. Total test cases in batch file: ${testsPerFile.length - 1}`);
        testsPerFile = [];
      }
    });

    const execution = spawn.sync('yarn', options, {
      stdio: 'inherit',
    });
    if (execution.status !== 0) status = execution.status;
    execSync(`rm -R ${e2eFolder}/tmp/batches`);
  };

  process.env.BANNED_KEYWORDS && infoPrettyPrint(`PALAVRAS EXCLUIDAS DE ACIONAGEM: ${process.env.BANNED_KEYWORDS.split(',')}`);
  execSync('yarn start-generate');
  process.env.COUNT_DEPTH = 0;
  do {
    execParallelTest();
    infoPrettyPrint(`${currentStage.name}`);
    process.env.COUNT_DEPTH++;
    execSync('yarn start-generate');
  } while (fs.readdirSync('tmp/').filter((file) => file !== '.gitignore').length > 0);
  if (searchDepthValue) infoPrettyPrint(`Test file generated to depth: ${process.env.COUNT_DEPTH - 1}`);
  console.log(`Test file generated! (${e2eFolder}/${fileTestName})`);
  console.timeEnd('Time execution');
  saveCoverageReport();

  const logsDir = 'cypress/logs';
  if (fs.existsSync(logsDir)) goToNextStage();
  process.exit(status);
};

/**
 * Stage 4 - Run Regression Tests
 *
 * Exec yarn command for run regression generation
 */
const runRegressionTests = () => {
  if (!checkCorrectStage(4)) return;

  const fileTest = `${e2eFolder}/cytestion.cy.js`;
  if (!fs.existsSync(fileTest)) {
    console.log(`No test file found to run.`);
    process.exit(0);
  }

  const options = ['test-file', fileTest, '--browser'];
  if (browser) {
    options.push(browser);
  } else {
    options.push('firefox');
  }
  const execution = spawn.sync('yarn', options, {
    stdio: 'inherit',
  });

  console.log(`Regression Test Performed! (${fileTest})`);
  console.timeEnd('Time execution');
  saveCoverageReport();

  const logsDir = 'cypress/logs';
  if (fs.existsSync(logsDir)) goToNextStage();
  process.exit(execution.status);
};

/**
 * Stage 5 - Run Failures Tests
 *
 * Exec yarn command for run failures tests
 */
const runFailuresTests = () => {
  if (!checkCorrectStage(5)) return;

  console.time('Time execution');
  createFileTestFailures();
  execSync(`rm -r cypress/logs`);
  const fileTestFailures = `${e2eFolder}/cytestion.failures.cy.js`;

  if (fs.existsSync(fileTestFailures)) {
    const options = ['test-file', fileTestFailures, '-c', 'video=true'];
    if (browser) {
      options.push('--browser');
      options.push(browser);
    }
    const execution = spawn.sync('yarn', options, {
      stdio: 'inherit',
    });

    console.log(`Failures Test Performed! (${fileTestFailures})`);
    console.timeEnd('Time execution');
    createFileTestFailures();
    process.exit(execution.status);
  }
  process.exit(1);
};

const saveCoverageReport = () => {
  const coverageFolderPath = './coverage';
  const destinationPath = `${e2eFolder}/`;
  if (fs.existsSync(coverageFolderPath)) {
    fs.mkdirSync(destinationPath, { recursive: true });
    execSync(`cp -r "${coverageFolderPath}" "${destinationPath}"`);
  }
};

const createFileTestFailures = () => {
  const logsDir = 'cypress/logs';
  if (fs.existsSync(logsDir)) {
    const filesLogs = fs.readdirSync(logsDir);
    const testTitles = filesLogs.map((file) => JSON.parse(fs.readFileSync(`${logsDir}/${file}`))?.title);

    const fileTest = fs.readFileSync(`${e2eFolder}/cytestion.cy.js`).toString();
    const tests = fileTest.split(' it(');
    const header = tests.shift();
    const result = [header];
    tests.forEach((test) => testTitles.some((title) => test.includes(title)) && result.push(test));
    if (result.length === 1) {
      console.log('Could not find the tests that failed.');
      process.exit(1);
    }
    let fileTestFailures = result.join(' it(');
    if (!(tests[tests.length - 1] === result[result.length - 1])) fileTestFailures += '\n});';
    fs.writeFileSync(`${e2eFolder}/cytestion.failures.cy.js`, fileTestFailures);
    execSync(`yarn format-file ${e2eFolder}/cytestion.failures.cy.js`, { stdio: 'pipe' });
    if (result.length / tests.length > 0.5) {
      console.log('More than 50% of existing tests have failed. Re-execution is very costly. Finishing.');
      process.exit(1);
    }
  }
};

const printPrettyWelcome = () => {
  console.time('Time execution');
  console.info('\033[1;92mWelcome to Cytestion!\033[0;0m ' + `Version: ${pjson.version}. ` + 'A tool for automated GUI testing of web applications, using the cypress framework');
};

const infoPrettyPrint = (message) => {
  console.info('\033[1;92mINFO:\033[0;0m ' + message);
};

const checkCorrectStage = (stageNumber) => {
  return currentStage.id === stageNumber;
};

const setStage = (stageNumber) => {
  currentStage = stages.find((stage) => stage.id === stageNumber);

  infoPrettyPrint(`${currentStage.name}`);
};

const goToNextStage = () => {
  let nextStageNumber;
  if (currentStage.id === 2 && nodeEnv === 'regression') {
    nextStageNumber = currentStage.id + 2;
  } else if (currentStage.id === 3 && nodeEnv === 'generation') {
    nextStageNumber = currentStage.id + 2;
  } else {
    nextStageNumber = currentStage.id + 1;
  }
  setStage(nextStageNumber);
  currentStage = stages.find((stage) => stage.id === nextStageNumber);

  currentStage.func();
};

const checkEnvironmentVariables = () => {
  if (!nodeEnv) {
    console.log('NODE_ENV variable not found.');
    process.exit(1);
  } else if (executionMode === 'production') {
    if (!baseUrl || !baseUrlApi || !userLogin || !userPassword || (!loginUrl && (!keycloakUrl || !clientSecret))) {
      console.log('It is necessary to provide all the environment variables to run in production mode.');
      process.exit(1);
    }
  } else if (executionMode === 'development') {
    if (!baseUrl || !baseUrlApi) {
      console.log('It is necessary to provide BASE_URL and BASE_URL_API environment variables to run in development mode.');
      process.exit(1);
    }
  }
  printEnvironmentVariables();
};

const printEnvironmentVariables = () => {
  if (executionMode) console.log('EXECUTION_MODE=' + executionMode);
  console.log('NODE_ENV=' + nodeEnv);
  console.log('BROWSER=' + browser ?? 'firefox');
  console.log('BASE_URL=' + baseUrl);
  console.log('BASE_URL_API=' + baseUrlApi);
  if (userLogin) console.log('USER_LOGIN=' + userLogin);
  if (userPassword) console.log('USER_PASSWORD=**********');
  if (loginUrl) console.log('LOGIN_URL=' + loginUrl);
  if (keycloakUrl) console.log('KEYCLOAK_URL=' + keycloakUrl);
  if (clientSecret) console.log('CLIENT_SECRET=**********');
  console.log('E2E_FOLDER=' + e2eFolder);
  if (fixturesFolder) console.log('FIXTURES_FOLDER=' + fixturesFolder);
  if (errorMessage) console.log('ERROR_MESSAGE=' + errorMessage);
  if (check400) console.log('CHECK_400=' + check400);
  if (check500) console.log('CHECK_500=' + check500);
  if (allowableCodeError) console.log('ALLOWABLE_CODE_ERROR=' + allowableCodeError);
  if (ignoreEndpointError) console.log('IGNORE_ENDPOINT_ERROR=' + ignoreEndpointError);
  if (threads) console.log('THREADS=' + threads);
  if (searchDepthValue) console.log('SEARCH_DEPTH_VALUE=' + searchDepthValue);
  if (env_blacklist) console.log('BANNED_KEY_WORDS= ' + env_blacklist.join(' , '));
};

const stages = [
  {
    id: 1,
    name: 'Verifying existence of cypress config file and set environment variables',
    func: verifyOrCreateDirectoryAndUtilFiles,
  },
  {
    id: 2,
    name: 'Checks if the tests have already been generated, inform it will be replaced.',
    func: checkTestsAlreadyGenerate,
  },
  {
    id: 3,
    name: 'Generating tests',
    func: generateTests,
  },
  {
    id: 4,
    name: 'Run regression tests',
    func: runRegressionTests,
  },
  {
    id: 5,
    name: 'Run failures tests',
    func: runFailuresTests,
  },
];

checkEnvironmentVariables();
printPrettyWelcome();
setStage(1);
verifyOrCreateDirectoryAndUtilFiles();
