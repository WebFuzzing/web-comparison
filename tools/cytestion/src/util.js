const fs = require('fs');
const path_project = require('path');
const jsdom = require('jsdom');
const { JSDOM } = jsdom;

const getPosition = (string, subString, index) => {
  return string.split(subString, index).join(subString).length;
};

const getContentBetween = (string, begin, end) => {
  let cutString = string.substring(string.indexOf(begin) + begin.length);
  cutString = cutString.substring(0, cutString.indexOf(end));
  return cutString;
};

const getIdByIdx = (string, idx) => {
  let cutString = string.substr(idx);
  cutString = cutString.substring(cutString.indexOf('"') + 1);
  cutString = cutString.substring(0, cutString.indexOf('"'));
  return cutString;
};

const getTagOfIdx = (string, idx) => {
  let cutString = string.substring(string.lastIndexOf('<', idx));
  const tagName = cutString.substring(0, cutString.indexOf(' '));
  const closeTag = tagName.replace('<', '</');
  const idxCloseTag = cutString.indexOf(closeTag);
  if (idxCloseTag !== -1) {
    const tempCutString = cutString.substring(0, idxCloseTag + closeTag.length + 1);
    const countNameTag = (tempCutString.match(new RegExp(tagName, 'g')) || []).length;
    if (countNameTag > 1) {
      return cutString.substring(0, getPosition(cutString, closeTag, countNameTag) + closeTag.length + 1);
    } else {
      return tempCutString;
    }
  } else {
    cutString = cutString.substring(0, cutString.indexOf('>') + 1);
  }
  return cutString;
};

const extractTwoTagsAbove = (string, tag) => {
  const idx = string.indexOf(tag);
  let cutString = string.substring(0, string.lastIndexOf('<', idx) - 1);
  cutString = cutString.substring(0, cutString.lastIndexOf('<', idx) - 1);
  return string.substring(cutString.lastIndexOf('<', idx), string.lastIndexOf('<', idx));
};

const getRootTestFile = () => {
  const pathToBaseTest = '../data/base-files/cytestion-base.cy.js';
  return fs.readFileSync(path_project.resolve(__dirname, pathToBaseTest)).toString();
};

const getRootTestFileProd = () => {
  const pathToBaseTest = '../data/base-files/cytestion-base-prod.cy.js';
  return fs.readFileSync(path_project.resolve(__dirname, pathToBaseTest)).toString();
};

const dataProcessor = (codeList) => {
  return codeList.map((elem) => {
    const obj = {};
    obj.codeText = elem;
    obj.actualId = getContentBetween(elem, 'const actualId = [`', '`];').replace(/`/g, '').split(',');
    return obj;
  });
};

const getParentContent = (name) => {
  const pathToTmp = '../tmp/';
  const rawdata = fs.readFileSync(`${path_project.resolve(__dirname, pathToTmp)}/translate.json`);
  const translate = JSON.parse(rawdata);
  let nameSplit = name.split('->');
  const initialSplitSize = nameSplit.length;
  if (initialSplitSize > 1) nameSplit.pop();
  const keyFile = Object.keys(translate).find((elem) => {
    return translate[elem] === nameSplit.join('->');
  });
  return fs.readFileSync(path_project.resolve(__dirname, pathToTmp) + '/' + keyFile).toString();
};

const extractUrl = (file) => {
  const start = file.content.indexOf('$$') + 2;
  const end = file.content.lastIndexOf('$$');
  return file.content.substring(start, end);
};

const extractParentUrl = (file) => {
  const start = file.parentContent.indexOf('$$') + 2;
  const end = file.parentContent.lastIndexOf('$$');
  return file.parentContent.substring(start, end);
};

const filterFilesByBaseUrl = (baseUrl, filesTmp) => {
  const filteredFiles = filesTmp.filter((file) => {
    const url = extractUrl(file);
    return url.includes(baseUrl);
  });
  return filteredFiles;
};

const canContinue = (code, filesTmp) => {
  if (code.codeText.includes('skip')) return false;

  const actualFile = filesTmp.find((file) => file.name === code.actualId.join('->'));
  const parentFile = filesTmp.find((file) => file.name === code.actualId.filter((el, idx) => idx < code.actualId.length - 1).join('->'));

  if (actualFile && parentFile) {
    if (actualFile.content === parentFile.content) return false;
  }
  return true;
};

const willNotGenerateDuplicate = (url, selector, tag, catalog) => {
  let willNot = true;
  url = removeTrailingSlash(url);
  if (catalog[url]) {
    willNot = !catalog[url].find((elem) => elem.selector === selector);
    if (willNot) catalog[url].push({ selector: selector, tag: tag });
  } else {
    catalog[url] = [{ selector: selector, tag: tag }];
  }
  return willNot;
};

const putSkipInTests = (codeList) => {
  codeList.forEach((code) => {
    code.codeText = code.codeText.replace(/ it\(`/g, ' it.skip(`');
  });
};

const filterAndClearCodeList = (codeList, result = []) => {
  if (codeList.length > 0) {
    const turnCode = codeList.shift();
    const isSubset = [...result, ...codeList].some((code) => turnCode.actualId.every((id) => code.actualId.includes(id)));
    if (!isSubset) {
      turnCode.codeText = turnCode.codeText
        .replace(/ it.skip\(`/g, ' it(`')
        .replace(/cy.writeContent(actualId);\n/g, '')
        .replace(/clickIfExist/g, 'clickCauseExist')
        .replace('cy.writeContent(actualId);', '')
        .replace(/^.*const actualId = .*$\n/gm, '')
        .replace(/^\s*[\r\n]/gm, '');
      result.push(turnCode);
    }
    return filterAndClearCodeList(codeList, result);
  } else {
    return result;
  }
};

/**
 * Function capable of comparing two html and returning the different tags contained in the content.
 * Unused. but it proved to be more efficient than node dependencies found.
 * Will be kept here and potentially extracted and exposed in the form of node dependency.
 */
const getDiffContent = (content, parentContent) => {
  let result = [];
  const domParent = new JSDOM(parentContent);
  const dom = new JSDOM(content);
  const parentElements = [].slice.call(domParent.window.document.querySelectorAll('*')).map((elem) => {
    return {
      headTag: elem.outerHTML.split('>')[0],
      tag: elem.outerHTML,
    };
  });
  const elements = [].slice.call(dom.window.document.querySelectorAll('*')).map((elem) => {
    return {
      headTag: elem.outerHTML.split('>')[0],
      tag: elem.outerHTML,
    };
  });

  elements.forEach((elem) => {
    if (!parentElements.map((a) => a.headTag).some((o) => o.includes(elem.headTag))) {
      if (!result.some((r) => r.includes(elem.tag))) {
        result.push(elem.tag);
      }
    }
  });

  return result.reduce((r, tag) => `${r}${tag.toString()}`, '');
};

function getInnerText(input) {
  const matches = input.match(/<[^>]*>([^<]+)<\/[^>]*>/g);
  if (!matches) return undefined;
  const lastMatch = matches[matches.length - 1];
  const textContent = lastMatch.replace(/<[^>]*>/g, '')
    .trim()
    .replace('&nbsp;', '')
    .replace(/[\r\n]+/g, ' ')
    .replace(/ +/g, ' ')
  return getValueEllipsis(textContent);
}

function getValueEllipsis(value, size = 50) {
  return value.length > size ? value.substring(0, size) + '...' : value;
}

function objectifiesTagHtml(tagString) {
  let firstSpace = tagString.indexOf(' ');
  if (firstSpace == -1) firstSpace = tagString.length;
  const tagStringCut = tagString.substring(0, firstSpace);
  const result = { tag: tagStringCut.replace('<', '') };
  let string = tagString.substring(firstSpace + 1);
  let flag = false;
  let currentKey,
    currentString = '';
  for (const char of string) {
    if (char === '=') {
      currentKey = currentString.trim();
      currentString = '';
    } else if (char === '"') {
      if (flag) {
        result[currentKey] = currentString;
        currentKey = '';
        currentString = '';
      }
      flag = !flag;
    } else currentString += char;
  }
  return result;
}

function filterStrings(arr) {
  return arr.filter(str => {
    return !arr.some(otherStr => {
      return otherStr !== str && str.includes(otherStr);
    });
  });
}

function removeTrailingSlash(str) {
  if (str.endsWith("/")) {
    return str.slice(0, -1);
  }
  return str;
}

module.exports = {
  getContentBetween,
  getIdByIdx,
  getTagOfIdx,
  getRootTestFile,
  getRootTestFileProd,
  dataProcessor,
  filterFilesByBaseUrl,
  canContinue,
  willNotGenerateDuplicate,
  putSkipInTests,
  filterAndClearCodeList,
  extractTwoTagsAbove,
  extractUrl,
  getInnerText,
  extractParentUrl,
  getParentContent,
  objectifiesTagHtml,
  filterStrings,
};
