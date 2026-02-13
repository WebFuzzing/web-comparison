const fs = require('fs');
const util = require('./util');
const path_project = require('path');
const ahocorasick = require('ahocorasick');
const rawHtmlSnippets = fs.readFileSync(path_project.resolve(__dirname, '../config/html-snippets.json'));
const htmlSnippets = JSON.parse(rawHtmlSnippets);
const ac = { tag: new ahocorasick(htmlSnippets.tag), attributes: new ahocorasick(htmlSnippets.attributes), class: new ahocorasick(htmlSnippets.class) };
const rawLocatorKeys = fs.readFileSync(path_project.resolve(__dirname, '../config/locator-keys.json'));
const locatorKeys = JSON.parse(rawLocatorKeys)['locator-keys'];
const rawdata = fs.readFileSync(path_project.resolve(__dirname, '../config/dictionary.json'));
const json = JSON.parse(rawdata);
const keysData = Object.keys(json);
const env_blacklist =
  process.env.BANNED_KEYWORDS &&
  process.env.BANNED_KEYWORDS.replace('\n', '')
    .split(',')
    .map((e) => e.trim());
const acBlackPropertyList = env_blacklist && new ahocorasick(env_blacklist);

const search = (testObj) => {
  const getByAttribute = (tagObj, triedLocators = []) => {
    const objectifiedTag = util.objectifiesTagHtml(tagObj.tag.split('>')[0]);
    const attrFounded = locatorKeys.filter((loc) => !triedLocators.includes(loc)).find((attr) => Object.keys(objectifiedTag).includes(attr));
    if (attrFounded && !objectifiedTag[attrFounded].includes('uuid')) {
      tagObj.id = objectifiedTag[attrFounded];
      tagObj.typeId = attrFounded + '=';
      const existInParent = [...setParentResults].find((tag) => tag.includes(`${tagObj.typeId}"${tagObj.id}"`));
      const existInCurrent = testObj.idClick.find((elem) => elem.id === tagObj.id && elem.typeId === tagObj.typeId);
      if (!existInCurrent && !existInParent) {
        if (tagObj.isInput) processTypeForm(tagObj, testObj);
        else testObj.idClick.push(tagObj);
      } else {
        getByAttribute(tagObj, [...triedLocators, attrFounded]);
      }
    } else {
      // try create a selector using the children tags.
      childrenTag = tagObj.tag.substring(tagObj.tag.indexOf('>') + 1);
      if (childrenTag) {
        tagObj.tag = childrenTag;
        getByAttribute(tagObj);
      }
    }
  };

  let setResults = new Set();
  let setParentResults = new Set();

  locateActionableTags(testObj.content, setResults);
  testObj.generation > 0 && locateActionableTags(testObj.parentContent, setParentResults);

  testObj.idClick = [];
  testObj.idText = [];
  testObj.idsForm = [];

  [...setResults].forEach((tag) => {
    const tagObj = { tag: tag, content: testObj.content, parentContent: testObj.parentContent };
    pointAsInput(tagObj);
    const text = util.getInnerText(tag);
    const existInCurrent = testObj.idText.find((tagObj) => tagObj.id === text);
    const existInParent = [...setParentResults].find((tag) => util.getInnerText(tag) === text);
    if (text && !existInCurrent && !existInParent && !tagObj.isInput) {
      tagObj.id = text;
      tagObj.typeId = 'inner-text';
      testObj.idText.push(tagObj);
    } else getByAttribute(tagObj);
  });
};

const pointAsInput = (tagObj) => {
  let tag = tagObj.tag;
  const idxInput = tag.indexOf('<input');
  const idxTextarea = tag.indexOf('<textarea');
  const idxSelect = tag.indexOf('<select');
  tagObj.isInput = idxInput !== -1 || idxTextarea !== -1 || idxSelect !== -1;
  if (tagObj.isInput) {
    if (idxInput !== 0) {
      tagObj.insideInputText = ' input';
    } else if (idxTextarea !== 0) {
      tagObj.insideInputText = ' textarea';
    } else if (idxSelect !== 0) {
      tagObj.insideInputText = ' select';
    }
  }
};

const processTypeForm = (elem, testObj) => {
  let key;
  let idxClass;
  let actualTag = elem.tag;
  const typeString = 'type=';
  const idxType = elem.tag.indexOf(typeString);
  if (elem.tag.includes('<select')) {
    elem.classId = 'input-select';
  } else if (idxType !== -1) {
    const typeInput = util.getIdByIdx(elem.tag, idxType);
    if (typeInput === 'date') {
      elem.classId = 'input-date';
    } else if (typeInput === 'number') {
      elem.classId = 'input-number';
    } else if (typeInput === 'submit') {
      elem.classId = 'input-submit';
    } else if (typeInput === 'input-checkbox') {
      elem.classId = 'input-submit';
    } else {
      elem.classId = 'input';
    }
  } else {
    const classString = 'class=';
    while (!key && idxClass !== -1) {
      idxClass = actualTag.indexOf(classString);
      if (idxClass !== -1) {
        const classInput = util.getIdByIdx(actualTag, idxClass);
        key = keysData.find((key) => classInput.includes(key));
        if (key) elem.classId = json[key];
        if (elem.classId === 'input') {
          if (elem.id.includes('input-cpf')) elem.classId = 'input-cpf';
          if (elem.id.includes('input-cnpj')) elem.classId = 'input-cnpj';
        }
        if (!key) {
          actualTag = actualTag.substr(idxClass + classString.length);
        }
      } else {
        key = keysData.find((key) => util.extractTwoTagsAbove(elem.content, elem.tag).includes(key));
        if (key) elem.classId = json[key];
        else elem.classId = 'input';
      }
    }
  }
  testObj.idsForm.push(elem);
};

const locateActionableTags = (content, setResults) => {
  let results = [];
  let blackProperty = [];
  if (acBlackPropertyList) acBlackPropertyList.search(content).forEach((b) => blackProperty.push(util.getTagOfIdx(content, b[0])));
  Object.entries(ac).forEach(([kind, ahoDictionary]) => {
    let listResult = ahoDictionary.search(content);

    if (kind == 'attributes') {
      listResult = listResult.filter((elem) => {
        const index = elem[0];
        const attribute = elem[1][0];
        if (attribute.includes('=')) {
          if (attribute.at(-1) == '=') {
            let attributeValue = content[index + 1];
            do {
              attributeValue += content[index + 1 + attributeValue.length];
            } while (["'", '"', '>'].every((elem) => attributeValue.at(-1) != elem));
            return ["'", '"'].some((elem) => attributeValue.at(-1) == elem);
          } else {
            return content[index - attribute.length] == ' ' && [' ', '>'].some((elem) => content[index + 1] == elem);
          }
        } else return false;
      });
    } else if (kind == 'class') {
      listResult = listResult.filter((elem) => {
        const index = elem[0];
        let stringCheck = '';
        while (!stringCheck.includes('=ssalc') && !stringCheck.includes('<')) stringCheck += content[index - stringCheck.length];
        return !stringCheck.includes('<');
      });
    } else if (kind == 'tag') {
      listResult = listResult.filter((elem) => {
        const tag = elem[1][0];
        if (tag === '<a ') {
          return util.getTagOfIdx(content, elem[0]).includes('href=');
        }
        return true;
      });
    }

    let listTags = listResult.map((elem) => util.getTagOfIdx(content, elem[0]));

    // remove not actionable elements
    listTags = listTags.filter((tag) => !tag.includes('readonly') && !tag.includes('disabled') && !tag.includes('visibility: hidden') && !tag.includes('mailto:') && !tag.includes('rel="nofollow"'));
    // remove tags inside tags that we do not want to see
    listTags = listTags.filter((tag) => blackProperty.every((elem) => !elem.includes(tag) && !tag.includes(elem)));
    const checkPage = (tag) => {
      const blockPage = ['page', 'página', 'pagina'];
      const lowerTag = tag.toLowerCase();
      return blockPage.some(
        (val) =>
          lowerTag.includes(val) &&
          lowerTag
            .match(new RegExp(val + '\\s\\d'))
            ?.shift()
            .split(' ')
            .at(-1) > 2
      );
    };
    listTags = listTags.filter((tag) => !checkPage(tag));

    results[kind] = listTags;
    results = results.concat(listTags);
  });
  results.reverse();
  util.filterStrings(results).forEach((tag) => setResults.add(tag));
};

module.exports = {
  search,
};
