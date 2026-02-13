# Cytestion

Cytestion is an exhaustive/exploratory end-to-end and GUI testing tool for web applications, utilizing the [Cypress](https://www.cypress.io/) framework. During its execution, Cytestion dynamically scans the HTML code of the current page in your web application, identifies interactable elements, and generates Cypress test cases that are then executed to detect system failures.

## Getting Started

To start using Cytestion, it is necessary to follow some steps to ensure proper functionality.

### Requirements

The requirements for Cytestion to work properly are:

- **Linux** based operating system.
- [NodeJS](https://nodejs.org/en) and [Yarn](https://yarnpkg.com/) package manager;
- [Firefox](https://www.mozilla.org/pt-BR/firefox/) browser;

### Executing Cytestion

After importing the project, you need to install the dependencies. This can be achieved by running the following command in the Cytestion folder:

```sh
yarn install
```

Cytestion uses [Dotenv](https://www.npmjs.com/package/dotenv) to read environment variables from a **.env** file. Therefore, you need to create this file and place it in the Cytestion folder, named **.env**. This file should include the necessary environment variables. Your **.env** file should look like this:

```properties
BASE_URL=<url to app in production>           # https://www.example.com
BASE_URL_API=<url to app api in production>   # https://www.example.com/api
LOGIN_URL=<login url to app in production>    # https://www.example.com/login
USER_LOGIN=<user login>                       # user.login
USER_PASSWORD=<user password>                 # 123safePass123
```

You can also add optional variables to get some extra configurations:

```properties
BROWSER=<which browser your application will be accessed and interacted with (default: firefox)>  # chrome, chromium, or firefox
E2E_FOLDER=<intended location for test files>                                                     # ../e2e/test/folder/hooray
FIXTURES_FOLDER=<intended location for fixtures files used in tests>                              # cypress/fixtures
ALLOWABLE_CODE_ERROR=<error codes that will not fail the tests case>                              # 406
CHECK_400=<whether test case fails when error codes of 400 family are captured>                   # true
CHECK_500=<whether test case fails when error codes of 500 family are captured>                   # false
ERROR_MESSAGE=<error messages that appear in the GUI causing the test case to fail>               # Unexpected Error,Exception
IGNORE_ENDPOINT_ERROR=<junction of endpoint + error code that will not fail the test case>        # endpoint/test:404
SEARCH_DEPTH_VALUE=<value indicating the maximum depth Cytestion can reach during exploration>    # 1,2,3
THREADS=<number of parallel threads the Cypress server can run (default: 2)>                      # 1,2,3
BANNED_KEYWORDS=<words ignored during site exploration>                                           # About Us, Contact Us
```

After your **.env** file is ready and set, it is time to run Cytestion. Just like all the other steps, this one is very simple!

```sh
yarn generate-test:prod
```

## Important

- To execute, you need to specify the **BASE_URL** and **BASE_URL_API**, which should be included in the **.env** configuration file;
- The generated artifact will be a test file called `cytestion.cy.js`, which will be placed in the `E2E_FOLDER`;
- We use Firefox as the default browser to prevent failures during prolonged executions. If you choose to use the optional `BROWSER` variable, you will need to install the corresponding browser on the host machine;
- The `ALLOWABLE_CODE_ERROR` env lets you provide an error code your application may throw. The test executions will not fail when this code is captured by Cypress.

### Details

The test generation process begins with a visit to the provided base URL. Next, the HTML of the page is explored to identify actionable elements, from which new tests are created. Exploration is carried out using the [IDUBS](https://drive.google.com/file/d/11OJLZ7XhS_PRFhTgTnhrK-9xNCqZIS1E/view) algorithm, which provides an efficient and time-effective approach. The location of actionable elements is determined by the [UAES](https://drive.google.com/file/d/1A9gY-TKd5O37c_-yX3bEWNbuMojHTwl6/view) method, which uses tags, attributes, and class names associated with these elements. The tester can and should enrich the page with descriptive and semantically meaningful attributes to enhance the tool's accuracy. The goal is to explore all possible paths by clicking buttons and filling and submitting all forms encountered during the analysis.

## Cypress Provides

At the end of the execution, Cypress provides the following files in the `e2e` folder:

- Information on test coverage, provided that the application is [instrumented with Istanbul](https://docs.cypress.io/guides/tooling/code-coverage#Instrumenting-code). These data will be in the `coverage` folder.
- A catalog of selectors for application elements, useful for creating tests, available in the `catalog.json` file.
- A file containing all the tests executed by the tool during the execution.

## Running Test Files

Running test files is simple. You will just have to ensure your environment variables are all correct. It is very important that you specify the location of your tests using the `E2E_FOLDER` variable.

After that, you just need to run the following commands in the Cypress folder:

```sh
yarn setup-cypress
```

```sh
yarn cypress
```

If everything went well, you will see a screen displaying the contents of your E2E_FOLDER. Select a test file, and then you will be able to see your tests running.