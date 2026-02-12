import asyncio
import logging
import random
import time
import traceback
from collections import defaultdict
from datetime import datetime
from typing import Optional
from urllib.parse import urlparse
from faker import Faker
import json

import pyppeteer.page
from pyppeteer.network_manager import Request
from pyppeteer.dialog import Dialog
from pyppeteer import launch

from models.action import Action
from models.failure import Failure
from models.form_group import FormGroup
from models.webstate import Webstate
from preprocessing import Preprocessing
from util import md5_hash, merge_intervals

Testcase = list[tuple[Webstate, Optional[Action]]]

class Crawler:
    MAXIMUM_STEPS = 8
    TIME_BUDGET_MINUTES = 30
    IS_HEADLESS_START = True

    def __init__(self, home_url: str, project_code: str, session_dir: str, time_budget: int):
        self.TIME_BUDGET_MINUTES = time_budget
        self.preprocessing: Preprocessing = Preprocessing()
        self.home_url: str = home_url
        self.project_code: str = project_code
        self.session_dir: str = session_dir
        self.log_name = f'res/logs/ebat/{self.project_code}_{datetime.now().strftime("%m%d-%H%M%S")}'
        self.home_state = None
        self.is_unqiue_failure = False
        self.browser: Optional[pyppeteer.page.Browser] = None
        self.active_page: Optional[pyppeteer.page.Page] = None
        self.invalid_actions: dict[Action, bool] = defaultdict(lambda: False, {})
        self.webstate_action_exploration_dict: dict[tuple[Webstate, Action], int] = defaultdict(lambda: 0,
                                                                                                {})  # 0 => unexplored, 1 => active, 2 => complete
        self.webstate_form_state_dict: dict[tuple[Webstate, Action], int] = defaultdict(lambda: 0,
                                                                                        {})  # 0 => all fields blank, 1 => some fields filled, 2 => all fields filled
        self.application_failures: list[Failure] = []
        self.coverage_dict: dict[str, list[tuple[int, int]]] = defaultdict(lambda: [],
                                                                           {})  # js script to list of executed ranges
        self.app_scripts_dict: dict[str, str] = dict()

        self.actions_log_file = open(self.log_name + '_actions.log', "w")

        self.root_domain: str = urlparse(self.home_url).netloc
        self.failed_test_cases: list[Testcase] = []
        self.test_case_iterator = 0
        self.start_time: time = time.time()
        self.no_new_state_interval: time = time.time()


        logging.basicConfig(
            filename=self.log_name + '.log',
            filemode='w',
            format='%(asctime)s %(levelname)-8s %(message)s',
            level=logging.WARNING,
            datefmt='%Y-%m-%d %H:%M:%S')

    async def init_browser(self) -> None:

        if self.browser is not None:
            await self.browser.close()

        self.browser = await launch(headless=self.IS_HEADLESS_START,
                                    userDataDir=self.session_dir,
                                    args=['--no-sandbox', '--disable-setuid-sandbox'])


    async def _restart_env(self, wait: bool):
        await self.init_browser()
        self.active_page = (await self.browser.pages())[0]
        this = self

        async def handle_console_error(message: pyppeteer.page.ConsoleMessage):
            if message.type == 'error':
                failure: Failure = Failure(message.text.replace('\n', '\t').replace('\r', '\t'), this.active_page.url)
                logging.error("Failure: " + str(failure))

                if failure not in this.application_failures:
                    self.is_unqiue_failure = True
                    this.application_failures.append(failure)


        self.active_page.on('console', lambda message: asyncio.ensure_future(handle_console_error(message)))

        async def handle_request_error(http_request: pyppeteer.network_manager.Request):
            failing = False
            failure = None
            req_failure = http_request.failure()
            res = http_request.response
            if req_failure is not None:
                failing = True
                failure = Failure(req_failure["errorText"].replace('\n', '\t').replace('\r', '\t'),
                                  this.active_page.url)
            if res is not None:
                if 400 <= res.status < 600:
                    failing = True
                    err_text = (await res.text()).replace("\n", "\t").replace('\r', '\t')
                    failure = Failure(f'status code({res.status}) -> {err_text}', this.active_page.url)

            if failing:
                logging.error("Failure: " + str(failure))
                if failure not in this.application_failures:
                    this.application_failures.append(failure)


        self.active_page.on('requestfailed', lambda request: asyncio.ensure_future(handle_request_error(request)))
        self.active_page.on('requestfinished', lambda request: asyncio.ensure_future(handle_request_error(request)))

        async def handle_dialog(dialog: Dialog):
            if random.uniform(0, 1) > 0.5:
                await dialog.accept(promptText=Faker().text()[: random.randint(0, 50)])
            else:
                await dialog.dismiss()

        self.active_page.on(
            'dialog',
            lambda dialog: asyncio.ensure_future(handle_dialog(dialog))
        )

        await self.active_page.coverage.startJSCoverage(options={'resetOnNavigation': False})

        await self.active_page.goto(self.home_url, {'waitUntil': 'networkidle0'} if wait else {})
        await self.active_page.setCacheEnabled(True)


        await self._initial_setup()

    async def _initial_setup(self):

        if random.uniform(0, 1) > 0.1:
            try:
                if self.project_code == 'trello' and self.active_page.url == 'http://localhost:4000/sign_in':
                    signin_button = await self.active_page.querySelector('button[type="submit"]')
                    await signin_button.click()
                    await self.active_page.waitForSelector('#authentication_container')

                elif self.project_code == 'pagekit':
                    username_input = await self.active_page.querySelector('input[type="text"]')
                    await username_input.type('admin')

                    password_input = await self.active_page.querySelector('input[type="password"]')
                    await password_input.type('asdfghjkl123')

                    login_button = await self.active_page.querySelector('button')
                    await login_button.click()
                elif self.project_code == 'dimeshift':
                    signin_button = await self.active_page.querySelector('input[type="submit"]')
                    await signin_button.click()
            except:
                pass  # if auth session holds and element not found, no need to handle error as already logged in

    def _select_epsilon_greedy_action(self, webstate) -> Action:

        sorted_actions = sorted(webstate.valid_actions,
                                key=lambda act: (
                                    [1, 2, 0][self.webstate_action_exploration_dict[(webstate, act)]] if not
                                    self.invalid_actions[act] else -1,
                                    random.random()),
                                reverse=True)  # 2 => exploring, 1 => undiscovered, 0 => complete
        return sorted_actions[0]

    async def save_code_coverage(self):
        jsCoverage = []
        try:
            jsCoverage = await self.active_page.coverage.stopJSCoverage()
        except:
            pass

        for entry in jsCoverage:
            self.coverage_dict[entry['text']] = self.coverage_dict[entry['text']] + [(r['start'], r['end']) for r in
                                                                                     entry['ranges']]

    def report_statistics(self):
        total_bytes = 0
        used_bytes = 0

        coverages = {k: merge_intervals(v) for (k, v) in self.coverage_dict.items()}
        for script in coverages.keys():
            total_bytes += len(script)
            for r in coverages[script]:
                used_bytes += r[1] - r[0] - 1

        percentage = (used_bytes / total_bytes) * 100
        logging.warning(f'(Failure Count, Coverage Percentage) : ({len(self.application_failures)}, {percentage})')

    async def start(self):
        logging.warning("Starting")
        time_elapsed = (time.time() - self.start_time) / 60

        while time_elapsed <= self.TIME_BUDGET_MINUTES:
            try:
                await self.crawl()  # crawl will close program upon finishing
                await self.save_code_coverage()
            except:
                # traceback.print_exc()
                pass

            time_elapsed = (time.time() - self.start_time) / 60



        self.actions_log_file.close()
        logging.warning("Finished")
        self.report_statistics()


        for f in self.application_failures:
            logging.error(str(f))

        if self.browser is not None:
            await self.browser.close()

    async def crawl(self):
        await self._restart_env(True)

        prev_webstate: Webstate = (await self._get_page_webstate())[0]
        self.home_state = prev_webstate

        action_sequence = [None]
        testcase: Testcase = []
        discovered_states: list[Webstate] = [prev_webstate]
        action_sequence_iterator = 0

        # for duplicate action reduction
        webstate_unique_action_discovered: dict[Webstate, Optional[list[Action, int]]] = defaultdict(
            lambda: None, {})  # webstate non duplicate action list with discovery status
        action_discovered_dict: dict[Action, bool] = defaultdict(lambda: False, {})

        # # redo entire process
        rootstate_actions_exploration_left = len([a for a in prev_webstate.valid_actions or [] if
                                                  self.webstate_action_exploration_dict[
                                                      (prev_webstate, a)] < 2 and not self.invalid_actions[a]])
        if rootstate_actions_exploration_left == 0:
            logging.warning('redoing process')
            self.webstate_action_exploration_dict.clear()


        while True:
          
            action: Action = action_sequence.pop(0)
            try:
                self.is_unqiue_failure = False
                await self._perform_action(prev_webstate, action)
            except:
                pass
            # append action action to test_case
            for s in action.interaction_sequence if action is not None else [None]:
                testcase.append((prev_webstate, s))
          
            w: tuple[
                Webstate, bool] = await self._get_page_webstate()  # returns webstate and if new state discovered
            next_webstate = w[0]

            if urlparse(next_webstate.url).netloc != self.root_domain:  # action is useless for testing
                print('invalid action detected', action)
                self.invalid_actions[action] = True
                break

            # steps after visiting a state

            is_next_state_new_in_execution_trace = webstate_unique_action_discovered[
                                                       next_webstate] is None
            if is_next_state_new_in_execution_trace:
                non_duplicate_actions = [a for a in next_webstate.valid_actions if
                                         not action_discovered_dict[
                                             a]]  # state actions - discovered actions
                webstate_unique_action_discovered[next_webstate] = non_duplicate_actions

                for a in non_duplicate_actions:  # make actions found
                    action_discovered_dict[a] = True

            next_webstate.valid_actions = [a for a in webstate_unique_action_discovered[
                next_webstate] if not self.invalid_actions[
                a]]  # what if new action found for similar state???

    

            if next_webstate not in discovered_states and next_webstate != prev_webstate:
                discovered_states.append(next_webstate)
          

            if type(action) is FormGroup:
                self.webstate_form_state_dict[(prev_webstate, action)] = self.webstate_form_state_dict[(
                    prev_webstate,
                    action)] + 1
                self.webstate_action_exploration_dict[
                    (prev_webstate,
                     action)] = 1 if self.webstate_form_state_dict[(prev_webstate, action)] < 2 else 2
            else:
                nextstate_actions_exploration_left = len([a for a in next_webstate.valid_actions or [] if
                                                          self.webstate_action_exploration_dict[
                                                              (next_webstate, a)] < 2])
                self.webstate_action_exploration_dict[
                    (prev_webstate,
                     action)] = 1 if nextstate_actions_exploration_left > 0 and discovered_states.index(
                    prev_webstate) < discovered_states.index(next_webstate) else 2
             


            if not len(next_webstate.valid_actions or []):
                break

            if len(action_sequence) == 0:
                action_sequence = [self._select_epsilon_greedy_action(next_webstate)]

          
            self.actions_log_file.write(
                f'{prev_webstate.__str__()}, {action.__str__()}, {next_webstate.__str__()}, {next_webstate.url}\n')

            prev_webstate = next_webstate

            #################
            action_sequence_iterator = action_sequence_iterator + 1
            if action_sequence_iterator > self.MAXIMUM_STEPS:
                break

        test_case_iterator = self.test_case_iterator + 1

    async def _get_page_webstate(self) -> tuple[Webstate, bool]:

        webstate: tuple[Webstate, bool] = await self.preprocessing.extract_webstate_and_is_new_state(self.active_page)
        return webstate

    async def _perform_action(self, webstate: Webstate, valid_action: Optional[Action]):
   
        if valid_action is None:
            return
        tag = await valid_action.interact(self.webstate_form_state_dict[(webstate, valid_action)])
        if tag == "A":
            await self.active_page.waitForNavigation({'timeout': 1000})

        return
