from models.action import Action
from models.webstate import Webstate
from hashlib import md5

class Failure():
    def __init__(self, text: str, url: str, action: Action = None, webstate: Webstate = None):
        self.text = text
        self.url = url
        self.action = action
        self.webstate = webstate

        m = md5()
        for s in (self.text, self.action, self.webstate):
            m.update(str(s).encode())
        self._hash = int(m.hexdigest(), 16)

    # def __str__(self) -> str:
    #     return f'{self.page_url} -> {self.outer_html}'
    #
    def __str__(self) -> str:
        return f'{self._hash} : {self.url} -> {self.text}'
    
    def __repr__(self) -> str:
        return f'{self._hash} : {self.url} -> {self.text}'

    def __hash__(self):
        return self._hash

    def __eq__(self, other: object) -> bool:
        return self.__hash__() == other.__hash__()

    def __ne__(self, other):
        return not(self == other)