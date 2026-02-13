export function hashCode(value) {
  let hash = 0,
    i = 0,
    len = value?.length || 0;
  while (i < len) {
    hash = ((hash << 5) - hash + value.charCodeAt(i++)) << 0;
  }
  return hash + 2147483647 + 1;
}

export function levenshteinDistance(source, target) {
  let result = [];
  let i, j;
  for (i = 0; i <= source.length; i++) {
    result.push([i]);
  }
  for (j = 1; j <= target.length; j++) {
    result[0].push(j);
  }
  for (i = 1; i <= source.length; i++) {
    for (j = 1; j <= target.length; j++) {
      result[i].push(0);
      if (source[i - 1] == target[j - 1]) {
        result[i][j] = result[i - 1][j - 1];
      } else {
        let minimum = Math.min(result[i - 1][j] + 1, result[i][j - 1] + 1);
        minimum = Math.min(minimum, result[i - 1][j - 1] + 1);
        result[i][j] = minimum;
      }
    }
  }
  return result[source.length][target.length];
}

export function objectifiesTagHtml(tagString) {
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

export function getInnerText(input) {
  const matches = input.match(/<[^>]*>([^<]+)<\/[^>]*>/g);
  if (!matches) return undefined;
  const lastMatch = matches[matches.length - 1];
  const textContent = lastMatch
    .replace(/<[^>]*>/g, '')
    .trim()
    .replace('&nbsp;', '')
    .replace(/[\r\n]+/g, ' ')
    .replace(/ +/g, ' ');
  return getValueEllipsis(textContent);
}

export function getValueEllipsis(value, size = 50) {
  return value.length > size ? value.substring(0, size) + '...' : value;
}

export function handleImageLink($id) {
  let href = $id.is('a') ? $id.attr('href') : $id.find('a').attr('href');
  if (!href) {
    const parentA = $id.closest('a');
    if (parentA.length > 0) {
      href = parentA.attr('href');
      const isImageLink = href && (href.endsWith('.jpg') || href.endsWith('.png'));
      if (isImageLink) parentA.attr('download', '');
    }
  } else {
    const isImageLink = href && (href.endsWith('.jpg') || href.endsWith('.png'));
    if (isImageLink) $id.is('a') ? $id.attr('download', '') : $id.find('a').attr('download', '');
  }
}