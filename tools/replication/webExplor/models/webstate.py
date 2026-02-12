from pyppeteer.element_handle import ElementHandle
from hashlib import md5

from models.action import Action


def _lcsubstring_length(a, b):
    table = {}
    l = 0
    for i, ca in enumerate(a, 1):
        for j, cb in enumerate(b, 1):
            if ca == cb:
                table[i, j] = table.get((i - 1, j - 1), 0) + 1
                if table[i, j] > l:
                    l = table[i, j]
    return l


class Webstate:
    SIMILARITY_THRESHOLD = 0.8

    def __init__(self, url: str, html: str, valid_actions: tuple[Action] = None, tags: tuple = None):
        self.url: str = url
        self.html: str = html
        self.valid_actions: tuple[Action] = [] if valid_actions is None else valid_actions
        self.tags: tuple = [] if tags is None else tags

        m = md5()
        for s in tuple(self.url) + self.tags:
            m.update(str(s).encode())
        self._hash = int(m.hexdigest(), 16)

    def __repr__(self) -> str:
        return f'{self._hash}'

    def __hash__(self):
        return self._hash

    def __eq__(self, other: object) -> bool:
        return self.__hash__() == other.__hash__()

    def __ne__(self, other):
        return not (self == other)

    def is_similar_state(self, other) -> bool:
        # calculate using gestalt pattern matching from difflib package
        if type(other) is not Webstate:
            return False

        similarity = (2 * _lcsubstring_length(self.tags, other.tags)) / (
                len(self.tags) + len(other.tags))
        # similarity = SequenceMatcher(None, webstate1.tags, webstate2.tags).ratio()
        # print(webstate1.tags)
        # print(webstate2.tags)
        # print('similarity: ' + str(similarity) + ' urls: ' + webstate1.url + ' -> ' + webstate2.url)
        return similarity >= self.SIMILARITY_THRESHOLD
