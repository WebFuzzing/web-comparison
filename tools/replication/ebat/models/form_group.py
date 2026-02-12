from pyppeteer.element_handle import ElementHandle
from models.action import Action
from hashlib import md5
import random


class FormGroup(Action):
    def __init__(self, element_handle: ElementHandle, outer_html: str, page_url: str, actions: tuple[Action] = None):
        super().__init__(element_handle, outer_html, page_url)
        self.outer_html = outer_html
        self.page_url = page_url
        self.inner_actions = [] if actions is None else actions
        # self.g_iid = -math.log(-math.log(numpy.random.uniform(0.0, 1.0)))
        m = md5()
        for s in [self.outer_html] + [str(a.__hash__()) for a in
                                      self.inner_actions]:  # removed url from encoding for duplicate removal
            m.update(s.encode())
        self._hash = int(m.hexdigest(), 16)
        self.interaction_sequence: list[Action] = []
    # def __str__(self) -> str:
    #     return f'{self.page_url} -> {self.outer_html}'
    #
    def __str__(self) -> str:
        return f'{self._hash} -> {self.outer_html.split(">")[0]}'

    def __repr__(self) -> str:
        return f'{self._hash} -> {self.outer_html.split(">")[0]}'

    def __hash__(self):
        return self._hash

    def __eq__(self, other: object) -> bool:
        return self.__hash__() == other.__hash__()

    def __ne__(self, other):
        return not (self == other)

    async def interact(self, state): # 0 => all fields blank, 1 => some fields filled, >=2 => all fields filled
        submit_button_handle = None
        self.interaction_sequence = []
        for a in sorted(self.inner_actions, key=lambda k: random.random()): # random interaction sequence
            if "'type': 'submit'" in a.outer_html:
                submit_button_handle = a
                continue
            if state == 0:
                await a.interact(1)
            elif state == 1:
                is_clear = random.randint(0, 2)
                await a.interact(is_clear)
                if not is_clear:
                    self.interaction_sequence.append(a)
            else:
                await a.interact(0)
                self.interaction_sequence.append(a)

            self.interaction_sequence.append(a)
        await submit_button_handle.click()
        self.interaction_sequence.append(submit_button_handle)
        return "FORM"
