from typing import Optional
from bs4 import BeautifulSoup
import pyppeteer.page
from pyppeteer.element_handle import ElementHandle
from models.webstate import Webstate
from models.action import Action
import asyncio

def _element_filter_formatter(x):
    def __remove_digits(s):
        return ''.join([i for i in s if not i.isdigit()])

    return f'{x.name} {x.contents}' + str(
        {__remove_digits(k): __remove_digits(v) if k != 'href' else v for k, v in x.attrs.items()})



async def _retrieve_webstate(page: pyppeteer.page.Page, page_html: str, page_url: str) -> Webstate:
    parser = BeautifulSoup(page_html, "html.parser")

    action_element_htmls: list[str] = list(
        map(lambda x: _element_filter_formatter(x),
            parser.select('input, a, button, select > option')))
    element_handles: list[ElementHandle] = await page.querySelectorAll(
        'input, a, button, select')

    actionable_elements: tuple[Optional[Action]] = tuple(
        [None if await e.boundingBox() is None else Action(e, s, page_url) for (e, s) in
         zip(element_handles, action_element_htmls)])
    actionable_elements: tuple[Action] = tuple(set(filter(None, actionable_elements)))  # filtering hidden actions

    tags = [f'{tag.name} ' + str({k: v for k, v in tag.attrs.items() if v}) for tag in parser()]
   
    return Webstate(page_url, page_html, actionable_elements, tuple(tags))


class Preprocessing:

    def __init__(self) -> None:
        self.webstate_set: set[Webstate] = set()

    async def __get_page_content(self, page: pyppeteer.page.Page, retries=0):
        if retries > 10:
            return await page.content()
        else:
            try:
                return await page.content()
            except:
                await asyncio.sleep(0.5)
                return await self.__get_page_content(page, retries + 1)

    async def extract_webstate_and_is_new_state(self, page: pyppeteer.page.Page) -> tuple[Webstate, bool]:
        page_url = page.url
        page_html = await page.content()
        webstate = await _retrieve_webstate(page, page_html, page_url)
        for prev_webstate in self.webstate_set:
            if prev_webstate.url.rstrip('/') != page_url.rstrip('/'):
                continue
            if webstate.is_similar_state(prev_webstate):
                return (Webstate(prev_webstate.url, prev_webstate.html,
                                 webstate.valid_actions, prev_webstate.tags),
                        False)  # return previous webstate due to similarity
        self.webstate_set.add(webstate)  # update webstate set records

        return webstate, True

