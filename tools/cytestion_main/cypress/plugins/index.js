const fs = require('fs');

module.exports = (on) => {
  on(`task`, {
    isFileExisted(filePath) {
      return fs.existsSync(filePath);
    },
    writeFile({ filePath, data }) {
      fs.writeFileSync(filePath, data);
      return null;
    },
    failed() {
      return null;
    },
  });
  on('before:browser:launch', (browser, launchOptions) => {
    if (browser.name === 'electron') {
      launchOptions.args['disable-gpu'] = true;
      launchOptions.args['disable-dev-shm-usage'] = true;
    }
    return launchOptions;
  });
};
